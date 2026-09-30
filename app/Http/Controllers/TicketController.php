<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubmitNewTicket;
use App\Models\Alert;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\Approval;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\NewTicketInvoice;
use App\Mail\PendingApproval;
use App\Mail\ApproverRespond;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Division;
use App\Models\Unsafe;
use App\Models\Location;
use App\Models\Plant;
use App\Models\PointHistory;
use App\Models\Rank;
use App\Models\Site;
use App\Models\State;
use App\Models\StopCulture;
use App\Models\SubDepartment;
use App\Models\User;
use App\Models\ZeroHarmRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;

class TicketController extends Controller
{
    public function ShowTicketForm()
    {
        $department = Department::with('subdepartment')->orderBy('name', 'asc')->get();
        $plant = Plant::orderBy('name', 'asc')->get();
        $condition = Unsafe::where('is_enabled', 1)->where('is_condition', 1)->orderBy('name', 'asc')->get();
        $act = Unsafe::where('is_enabled', 1)->where('is_act', 1)->orderBy('name', 'asc')->get();
        $stop_cult = StopCulture::all();

        return view('Ticket.NewTicketForm', [
            'departments' => $department,
            'plants' => $plant,
            'conditions' => $condition,
            'acts' => $act,
            'stop_cults' => $stop_cult,
        ]);
    }

    public function SubmitNewTicket(SubmitNewTicket $request)
    {
        // Get validated input
        $validated = $request->validated();

        // Start DB transaction for safe ticket_id generation
        DB::beginTransaction();

        try {
            // Generate ticket_id
            $year = now()->format('Y');
            $month = now()->format('m');
            // Continue from the highest number used this year (ticket_id = YYYYMM + 5-digit yearly sequence).
            // A row count would reuse an existing number once any ticket has been deleted.
            $lastSequence = (int) Ticket::withTrashed()
                ->where('ticket_id', 'like', $year . '%')
                ->lockForUpdate()
                ->max(DB::raw('CAST(SUBSTRING(ticket_id, 7) AS UNSIGNED)'));
            $increment = str_pad($lastSequence + 1, 5, '0', STR_PAD_LEFT);
            $ticket_id = $year . $month . $increment;

            // Create new ticket
            $ticket = new Ticket;
            $ticket->ticket_id = $ticket_id;
            $ticket->name = $validated['name'];
            $ticket->email = $validated['email'];
            $ticket->phone_number = $validated['phone_number'];
            $ticket->staff_id = $validated['staff_id'];
            $selectedDepartment = $validated['department_id'];
            
            if ($selectedDepartment === '0') {
                // User selected "Others"
                $ticket->department_id = 0;
                $ticket->sub_department_id = 0;
                $ticket->department_other = $validated['other_department'];
            } elseif (Str::startsWith($selectedDepartment, 'dept_')) {
                // User selected a main department
                $ticket->department_id = Str::after($selectedDepartment, 'dept_');
                $ticket->sub_department_id = 0;
                $ticket->department_other = null;
            } elseif (Str::startsWith($selectedDepartment, 'sub_')) {
                // User selected a sub-department
                $ticket->sub_department_id = Str::after($selectedDepartment, 'sub_');

                // Get parent department ID from sub-department
                $subDept = SubDepartment::find($ticket->sub_department_id);
                $ticket->department_id = $subDept ? $subDept->department_id : 0;

                $ticket->department_other = null;
            }
            $ticket->affected_area = $validated['affected_area'];
            $selectedDepartmentRes = $validated['dept_res_id'];
            if ($selectedDepartmentRes === '0') {
                // User selected "Others"
                $ticket->dept_res_id = 0;
                $ticket->sub_dept_res_id = 0;
                $ticket->dept_res_other = $validated['dept_res_other'];
            } elseif (Str::startsWith($selectedDepartmentRes, 'dept_')) {
                // User selected a main department
                $ticket->dept_res_id = Str::after($selectedDepartmentRes, 'dept_');
                $ticket->sub_dept_res_id = 0;
                $ticket->dept_res_other = null;
            } elseif (Str::startsWith($selectedDepartmentRes, 'sub_')) {
                // User selected a sub-department
                $ticket->sub_dept_res_id = Str::after($selectedDepartmentRes, 'sub_');

                // Get parent department ID from sub-department
                $subDeptRes = SubDepartment::find($ticket->sub_dept_res_id);
                $ticket->dept_res_id = $subDeptRes ? $subDeptRes->dept_res_id : 0;

                $ticket->dept_res_other = null;
            }
            $ticket->plant_inv_id = $validated['plant_inv_id'];
            $ticket->ucua_id = $validated['entry_unsafe_condition_act'];
            $ticket->ucua_type = $validated['entry_unsafe'];
            $ticket->ucua_other = ($validated['entry_unsafe'] == 0)
                ? ($validated['entry_unsafe_condition_act'] === 'unsafe_act' ? $validated['unsafe_act_other'] : $validated['unsafe_cond_other'])
                : null;
            $ticket->description = $validated['description'];
            $ticket->stop_cult_id = $validated['stop_cult_id'];
            $ticket->action_taken = $validated['action_taken'];
            $ticket->bbs_action = $validated['bbs_action'];
            $ticket->bbs_methodology = isset($validated['bbs_methodology']) ? implode(', ', $validated['bbs_methodology']) : null;
            $ticket->status = 'Open';
            $ticket->pending_at_level = 1;
            $ticket->dateline = Carbon::now()->addWeeks(2)->toDateTimeString();
            $ticket->dateline_extension = null;
            $ticket->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create ticket: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'Failed to create ticket. Please try again.']);
        }

        // Handle attachments
        $filesToAttach = collect([]);
        $filesToAttach2 = collect([]);

        $attachmentBefore = collect($request->file('attachment_before'));
        $attachmentCorrection = collect($request->file('attachment_correction'));

        $attachmentBefore->each(function ($file) use ($ticket, $filesToAttach) {
            $randomStr = Str::random(40);
            $fileName = $randomStr . '.' . $file->extension();
            $filePath = 'ticket/ticket_' . $ticket->id . "/1/" . $fileName;

            $newAttach = new TicketAttachment;
            $newAttach->ticket_id = $ticket->id;
            $newAttach->file_name = $file->getClientOriginalName();
            $newAttach->level = 1;
            $newAttach->file_rand_name = $fileName;
            $newAttach->file_path = $filePath;
            $newAttach->save();

            Storage::disk('public')->putFileAs('ticket/ticket_' . $ticket->id . "/1/", $file, $fileName);

            $filesToAttach->push((object)[
                'path' => $filePath,
                'extension' => $file->extension()
            ]);
        });

        $attachmentCorrection->each(function ($file) use ($ticket, $filesToAttach2) {
            $randomStr = Str::random(40);
            $fileName = $randomStr . '.' . $file->extension();
            $filePath = 'ticket/ticket_' . $ticket->id . "/2/" . $fileName;

            $newAttach = new TicketAttachment;
            $newAttach->ticket_id = $ticket->id;
            $newAttach->file_name = $file->getClientOriginalName();
            $newAttach->level = 2;
            $newAttach->file_rand_name = $fileName;
            $newAttach->file_path = $filePath;
            $newAttach->save();

            Storage::disk('public')->putFileAs('ticket/ticket_' . $ticket->id . "/2/", $file, $fileName);

            $filesToAttach2->push((object)[
                'path' => $filePath,
                'extension' => $file->extension()
            ]);
        });

        // Create approvals
        $approval1 = new Approval;
        $approval1->ticket_id = $ticket->id;
        $approval1->approver_level = 1;
        $approval1->action = "Verified by";
        $approval1->group_id = 4;
        $approval1->approver_id = null;
        $approval1->approver_status = "Pending";
        $approval1->approver_remark = null;
        $approval1->respond_at = null;
        $approval1->save();

        $approval2 = new Approval;
        $approval2->ticket_id = $ticket->id;
        $approval2->approver_level = 2;
        $approval2->action = "Completed by";
        $approval2->group_id = 2;
        $approval2->approver_id = null;
        $approval2->approver_status = "Pending";
        $approval2->approver_remark = null;
        $approval2->respond_at = null;
        $approval2->save();

        // Get PICs: the head of the responsible department, plus the sub-department head when the
        // ticket is routed to a sub-department. The head of division (GM) and the plant head are no
        // longer notified - keep this in sync with $level1Pics in components/Timeline.blade.php.
        $dep_res = Department::find($ticket->dept_res_id);
        $head_dep = $dep_res ? User::find($dep_res->user_head_id) : null;
        $sub_dep = SubDepartment::find($ticket->sub_dept_res_id);
        $head_sub_dep = $sub_dep ? User::find($sub_dep->user_head_id) : null;

        $users = collect([$head_dep->email ?? null, $head_sub_dep->email ?? null])
            ->filter()
            ->unique()
            ->values()
            ->all();

        $unsafeEntry = Unsafe::find($ticket->ucua_type);

        // Send emails
        Mail::to($users)
            ->cc('shephn@phn.com.my')
            ->queue(new PendingApproval($ticket->id, $unsafeEntry->id ?? null, 1));

        Mail::to($ticket->email)
            ->queue(new NewTicketInvoice($ticket->id, $unsafeEntry->id ?? null));

        return redirect()->route('ShowSuccessTicketSubmit');
    }

    public function ShowSuccessTicketSubmit(Request $request)
    {
        return view('Ticket.SuccessTicketSubmit');
    }

    public function ShowListTickets(Request $request)
    {
        $user_role = Auth::user()->groups->pluck('name'); // Collection of role names

        $tabs = collect([
            (object) [
                'id' => 1,
                'name' => 'Pending',
                'link' => route('ShowListTickets', ['category' => 'Pending']),
                'isActive' => $request->category == 'Pending',
            ],
            (object) [
                'id' => 2,
                'name' => $user_role->contains(function ($role) {
                    return in_array($role, ['admin', 'she_admin']);
                }) ? 'Completed' : 'Verified',
                'link' => route('ShowListTickets', [
                    'category' => $user_role->contains(function ($role) {
                        return in_array($role, ['admin', 'she_admin']);
                    }) ? 'Completed' : 'Verified'
                ]),
                'isActive' => $request->category == (
                    $user_role->contains(function ($role) {
                        return in_array($role, ['admin', 'she_admin']);
                    }) ? 'Completed' : 'Verified'
                ),
            ],
            (object) [
                'id' => 3,
                'name' => 'Declined',
                'link' => route('ShowListTickets', ['category' => 'Declined']),
                'isActive' => $request->category == 'Declined',
            ],
        ]);

        $approval = collect(); // default empty

        if ($request->category == 'Pending') {
            if ($user_role->contains(function ($role) {
                return in_array($role, ['hodiv', 'hodept', 'hosubdept', 'hop', 'hos']);
            })) {
                $approval = Approval::where([
                    ['approver_level', 1],
                    ['approver_status', 'Pending']
                ])->pluck('ticket_id');
            } elseif ($user_role->contains(function ($role) {
                return in_array($role, ['admin', 'she_admin']);
            })) {
                $level_1 = Approval::where([
                    ['approver_level', 1],
                    ['approver_status', 'Verified']
                ])->pluck('ticket_id');

                $approval = Approval::whereIn('ticket_id', $level_1)
                    ->where([
                        ['approver_level', 2],
                        ['approver_status', 'Pending']
                    ])->pluck('ticket_id');
            } else {
                return redirect()->back()->withErrors(['error' => 'You do not have permission to view this page.']);
            }
        } elseif ($request->category == 'Verified' || $request->category == 'Completed') {
            if ($user_role->contains(function ($role) {
                return in_array($role, ['hodiv', 'hodept', 'hosubdept', 'hop', 'hos']);
            })) {
                $approval = Approval::where([
                    ['approver_level', 1],
                    ['approver_status', 'Verified']
                ])->pluck('ticket_id');
            } elseif ($user_role->contains(function ($role) {
                return in_array($role, ['admin', 'she_admin']);
            })) {
                $level_1 = Approval::where([
                    ['approver_level', 1],
                    ['approver_status', 'Verified']
                ])->pluck('ticket_id');

                $approval = Approval::whereIn('ticket_id', $level_1)
                    ->where([
                        ['approver_level', 2],
                        ['approver_status', 'Completed']
                    ])->pluck('ticket_id');
            } else {
                return redirect()->back()->withErrors(['error' => 'You do not have permission to view this page.']);
            }
        } elseif ($request->category == 'Declined') {
            if ($user_role->contains(function ($role) {
                return in_array($role, ['hodiv', 'hodept', 'hosubdept', 'hop', 'hos']);
            })) {
                $approval = Approval::where([
                    ['approver_level', 1],
                    ['approver_status', 'Declined']
                ])->pluck('ticket_id');
            } elseif ($user_role->contains(function ($role) {
                return in_array($role, ['admin', 'she_admin']);
            })) {
                $level_1 = Approval::where([
                    ['approver_level', 1],
                    ['approver_status', 'Declined']
                ])->pluck('ticket_id');

                $approval = Approval::whereIn('ticket_id', $level_1)
                    ->where([
                        ['approver_level', 2],
                        ['approver_status', 'Declined']
                    ])->pluck('ticket_id');
            } else {
                return redirect()->back()->withErrors(['error' => 'You do not have permission to view this page.']);
            }
        }

        $sortable = [
            'id' => 'entry_tickets.id',
            'ticket_id' => 'entry_tickets.ticket_id',
            'name' => 'entry_tickets.name',
            'department' => 'departments.name',
            'plant' => 'plants.name',
        ];

        $sortField = $request->input('sort', 'id');
        $sortField = array_key_exists($sortField, $sortable) ? $sortField : 'id';
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $ticketsQuery = Ticket::with([
            'plant',
            'plant_involve',
            'plant_involve.head_plant',
            'department',
            'sub_department',
            'dep_responsible',
            'dep_responsible.head_department',
            'sub_dep_responsible',
            'sub_dep_responsible.head_subdepartment',
            'gm_responsible',
            'stop_culture',
            'zero_harm',
            'rank',
            'site',
            'approval'
        ])->whereIn('entry_tickets.id', $approval);

        if ($sortField === 'department') {
            $ticketsQuery->select('entry_tickets.*')
                ->leftJoin('departments', 'departments.id', '=', 'entry_tickets.department_id');
        } elseif ($sortField === 'plant') {
            $ticketsQuery->select('entry_tickets.*')
                ->leftJoin('plants', 'plants.id', '=', 'entry_tickets.plant_inv_id');
        }

        $tickets = $ticketsQuery->orderBy($sortable[$sortField], $sortDirection)
            ->paginate(20)
            ->withQueryString();

        $category = $request->category;

        return view('Ticket.ListTickets', compact('tickets', 'tabs', 'category', 'sortField', 'sortDirection'));
    }

    public function ShowSelectedTicket(Request $request)
    {
        $ticket = Ticket::with([
            'plant',
            'plant_involve',
            'plant_involve.head_plant',
            'department',
            'sub_department',
            'dep_responsible',
            'dep_responsible.head_department',
            'dep_responsible.division.head_div',
            'sub_dep_responsible',
            'sub_dep_responsible.head_subdepartment',
            'gm_responsible',
            'stop_culture',
            'zero_harm',
            'rank',
            'site',
            'approval'
        ])->find($request->ticketId);

        $approvalStatues = Approval::where([['ticket_id', $request->ticketId], ['approver_level', $ticket->pending_at_level]])->first();

        // determine show button or not
        if ($request->category == 'Pending') {
            if ($approvalStatues->approver_level == 1) {
                $approveButtonText = 'Verify';
                $isShowButton = true;
            } elseif ($approvalStatues->approver_level == 2) {
                $approveButtonText = 'Complete';
                $isShowButton = true;
            } else {
                $isShowButton = false;
                $approveButtonText = null;
            }
        } else {
            $isShowButton = false;
            $approveButtonText = null;
        }

        // If an admin assigned this level-1 ticket to specific people, only they (or an admin)
        // get the Verify/Decline buttons; everyone else just sees who it is assigned to.
        $assignedNotice = null;
        $level1Approval = Approval::with('assignees')
            ->where([['ticket_id', $ticket->id], ['approver_level', 1]])
            ->first();
        if ($request->category == 'Pending'
            && $approvalStatues->approver_level == 1
            && $level1Approval && $level1Approval->assignees->isNotEmpty()) {
            $assignees = $level1Approval->assignees;
            $isAssignee = $assignees->contains('id', Auth::id());
            if ($isAssignee) {
                $others = $assignees->count() - 1;
                $assignedNotice = 'Assigned to you' . ($others > 0 ? ' and ' . $others . ' other' . ($others > 1 ? 's' : '') : '');
            } else {
                $assignedNotice = 'Assigned to ' . $assignees->sortBy('name')->pluck('name')->implode(', ');
            }

            if (!$isAssignee && !$this->isAdminUser()) {
                $isShowButton = false;
                $approveButtonText = null;
            }
        }

        $mode = $request->mode;
        $category = $request->category;

        return view('Ticket.SelectedTicket', compact(
            'ticket',
            'approvalStatues',
            'isShowButton',
            'mode',
            'category',
            'approveButtonText',
            'assignedNotice',
        ));
    }

    public function SubmitApproverRespond(Request $request)
    {
        $approverRespond = $request->approverRespond;
        $remark = $request->remark;
        $ticketId = $request->ticketId;
        $approverLevel = $request->approverLevel;

        $ticket = Ticket::find($ticketId);
        if (!$ticket) {
            return redirect()->route('ShowListTickets', ['category' => 'Pending'])
                ->withErrors(['error' => 'Ticket not found.']);
        }

        $approval = Approval::where('approver_level', $approverLevel)->where('ticket_id', $ticket->id)->first();
        if (!$approval) {
            return redirect()->route('ShowSelectedTicket', ['category' => 'Pending', 'ticketId' => $ticket->id])
                ->withErrors(['error' => 'Approval flow record not found.']);
        }

        // A level-1 ticket an admin assigned to specific people can only be answered by them or an admin.
        if ($approval->approver_level == 1 && !$this->isAdminUser()) {
            $assigneeIds = $approval->assignees()->pluck('users.id');
            if ($assigneeIds->isNotEmpty() && !$assigneeIds->contains(Auth::id())) {
                return redirect()->route('ShowSelectedTicket', ['category' => 'Pending', 'ticketId' => $ticket->id])
                    ->withErrors(['error' => 'This ticket has been assigned to other approvers.']);
            }
        }

        if (isset($approval)) {
            $approval->approver_id = Auth::user()->id;
            if ($approverRespond == "Verify") {
                $approval->approver_status = 'Verified';
            } elseif ($approverRespond == "Complete") {
                $approval->approver_status = 'Completed';
            } else {
                $approval->approver_status = 'Declined';
            }

            $approval->approver_remark = $remark;
            $approval->respond_at = Carbon::now()->toDateTimeString();
            $approval->save();

            $ticket->pending_at_level = 2;
            $ticket->save();
        }

        if ($approverRespond == "Complete" && $approval->approver_level == 2) {
            $ticket->status = "Closed";
            $ticket->pending_at_level = null;
            $ticket->save();

            $point = new PointHistory();
            $point->staff_id = $ticket->staff_id;
            $point->action = 'New';
            $point->points = 1;
            $point->approver_id = Auth::user()->id;
            $point->respond_at = Carbon::now();
            $point->save();

            Alert::alert('Completed!', 'bg-green-200');
        } elseif ($approverRespond == "Declined" && $approval->approver_level == 2) {
            $ticket->status = "Declined";
            $ticket->pending_at_level = null;
            $ticket->save();

            Alert::alert('Declined!', 'bg-green-200');
        }

        if ($approval->approver_level == 1) {
            // Save pictures
            $files_array = collect($request->attachment);
            $filesToAttach = collect([]);

            $files_array->each(function ($each) use ($ticket, $filesToAttach) {
                $randomStr = Str::random(40);
                $fileName = $randomStr . "." . $each->extension();
                $filePath = 'ticket/ticket_' . $ticket->id . "/3/" . $fileName;

                $newAttach = new TicketAttachment;
                $newAttach->ticket_id = $ticket->id;
                $newAttach->file_name = $each->getClientOriginalName();
                $newAttach->level = 3;
                $newAttach->file_rand_name = $fileName;
                $newAttach->file_path = $filePath;
                $newAttach->save();

                // Store in public disk
                Storage::disk('public')->putFileAs('ticket/ticket_' . $ticket->id . "/3/", $each, $fileName);

                $filesToAttach->push((object) [
                    'path' => $filePath,
                    'extension' => $each->extension()
                ]);
            });

            if ($approverRespond !== "Verify") {
                $ticket->status = "Declined";
                $ticket->pending_at_level = null;
                $ticket->save();

                $level_2 = Approval::where([
                    ['ticket_id', $ticket->id],
                    ['approver_level', 2]
                ])->first();
                $level_2->approver_status = "Declined";
                $level_2->save();
            }
        }

        $unsafeEntry = Unsafe::find($ticket->ucua_type);

        if ($approval->approver_level == 1) {
            if ($approverRespond == "Verify") {
                $level2Approval = Approval::with('group.users')
                    ->where('ticket_id', $ticket->id)
                    ->where([
                        ['approver_level', 2],
                        ['approver_status', 'Pending']
                    ])->first();

                $notifyEmails = collect();

                if ($level2Approval && $level2Approval->group) {
                    $notifyEmails = $level2Approval->group->users
                        ->pluck('email')
                        ->filter();
                }

                // Fallback for legacy/incomplete data when level-2 group relation is missing.
                if ($notifyEmails->isEmpty()) {
                    $notifyEmails = User::whereHas('groups', function ($query) {
                        $query->whereIn('name', ['admin', 'she_admin']);
                    })->pluck('email')->filter();
                }

                if ($notifyEmails->isNotEmpty()) {
                    Mail::to($notifyEmails->values()->all())
                        ->queue(new PendingApproval($ticket->id, $unsafeEntry->id ?? null, 1));
                }
            } else {
                Mail::to($ticket->email)->queue(new ApproverRespond($ticket->id, $unsafeEntry->id ?? null, $approval->id));
            }
        } elseif ($approval->approver_level == 2) {
            Mail::to($ticket->email)->queue(new ApproverRespond($ticket->id, $unsafeEntry->id ?? null, $approval->id));
        }

        if ($approverRespond == "Verify") {
            return redirect()->route('ShowSelectedTicket', ['category' => 'Verified', 'ticketId' => $ticket->id]);
        } elseif ($approverRespond == "Complete") {
            return redirect()->route('ShowSelectedTicket', ['category' => 'Completed', 'ticketId' => $ticket->id]);
        } else {
            return redirect()->route('ShowSelectedTicket', ['category' => 'Declined', 'ticketId' => $ticket->id]);
        }
    }

    public function SearchTicketForm(Request $request)
    {
        return view('submitter.search_submission', [
            'request' => $request
        ]);
    }


    public function ShowTicketByStaffId(Request $request)
    {
        // Get staff_id from input or session
        $staff_id = $request->input('staff_id') ?? Session::get('staff_id');

        // If no staff_id at all, redirect back
        if (!$staff_id) {
            return redirect()->route('ShowSearchTicketForm')->withErrors(['staff_id' => 'Please enter a Staff ID.']);
        }

        // Check if staff_id exists
        $exists = Ticket::where('staff_id', $staff_id)->exists();

        if (!$exists) {
            return redirect()->route('ShowSearchTicketForm')->withErrors(['staff_id' => 'No submissions found for entered Staff ID.']);
        }

        // Store staff_id in session
        Session::put('staff_id', $staff_id);

        // Tabs
        $tabs = collect([
            (object) ['id' => 1, 'name' => 'Open', 'link' => route('SearchTicketResult', ['status' => 'Open']), 'isActive' => $request->status == 'Open'],
            (object) ['id' => 2, 'name' => 'Closed', 'link' => route('SearchTicketResult', ['status' => 'Closed']), 'isActive' => $request->status == 'Closed'],
            (object) ['id' => 3, 'name' => 'Declined', 'link' => route('SearchTicketResult', ['status' => 'Declined']), 'isActive' => $request->status == 'Declined'],
        ]);

        // Filter tickets by status and staff_id
        $query = Ticket::with([
            'plant',
            'plant_involve',
            'plant_involve.head_plant',
            'department',
            'sub_department',
            'dep_responsible',
            'dep_responsible.head_department',
            'sub_dep_responsible',
            'sub_dep_responsible.head_subdepartment',
            'gm_responsible',
            'stop_culture',
            'zero_harm',
            'rank',
            'site'
        ])->where('entry_tickets.staff_id', $staff_id);

        if ($request->status == 'Open') {
            $query->where('entry_tickets.status', 'Open');
        } elseif ($request->status == 'Closed') {
            $query->where('entry_tickets.status', 'Closed');
        } elseif ($request->status == 'Declined') {
            $query->where('entry_tickets.status', 'Declined');
        } else {
            return redirect()->route('ShowSearchTicketForm')->withErrors(['status' => 'Invalid status selected.']);
        }

        // Sortable column headers. Newest first by default.
        $sortable = [
            'id' => 'entry_tickets.id',
            'ticket_id' => 'entry_tickets.ticket_id',
            'name' => 'entry_tickets.name',
            'department' => 'departments.name',
            'plant' => 'plants.name',
            'created_at' => 'entry_tickets.created_at',
            'dateline' => 'entry_tickets.dateline',
        ];

        $sortField = $request->input('sort', 'created_at');
        $sortField = array_key_exists($sortField, $sortable) ? $sortField : 'created_at';
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        if ($sortField === 'department') {
            $query->select('entry_tickets.*')
                ->leftJoin('departments', 'departments.id', '=', 'entry_tickets.department_id');
        } elseif ($sortField === 'plant') {
            $query->select('entry_tickets.*')
                ->leftJoin('plants', 'plants.id', '=', 'entry_tickets.plant_inv_id');
        }

        $tickets = $query->orderBy($sortable[$sortField], $sortDirection)
            ->orderBy('entry_tickets.id', 'desc')
            ->paginate(10)
            ->appends(['staff_id' => $staff_id, 'sort' => $sortField, 'direction' => $sortDirection]);

        // Available points: earned, minus redemptions already approved, minus requests still
        // waiting for approval. Same calculation as the Redeem page (PointRedeemController).
        $point_sum = PointHistory::where([['staff_id', $staff_id], ['action', 'New']])->sum('points');
        $point_pending = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'], ['approver_id', null], ['respond_at', null]])->sum('points');
        $point_redeemed = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'], ['approver_id', '!=', null], ['respond_at', '!=', null]])->sum('points');
        $point = $point_sum - $point_pending - $point_redeemed;

        return view('submitter.submission_list', [
            'tickets' => $tickets,
            'staff_id' => $staff_id,
            'tabs' => $tabs,
            'status' => $request->status,
            'point' => $point,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function ShowTicketDetail(Request $request, $status, $ticket_id)
    {
        $status = $request->route('status');
        $ticket_id = $request->route('ticket_id');

        $ticket = Ticket::with([
            'plant',
            'plant_involve',
            'department',
            'sub_department',
            'dep_responsible',
            'dep_responsible.head_department',
            'sub_dep_responsible',
            'sub_dep_responsible.head_subdepartment',
            'gm_responsible',
            'stop_culture',
            'zero_harm',
            'rank',
            'site'
        ])->find($ticket_id);

        if (!$ticket) {
            return redirect()->route('ShowSearchTicketForm')->withErrors(['ticket_id' => 'Ticket not found.']);
        }

        return view('submitter.detail', [
            'status' => $status,
            'ticket' => $ticket
        ]);
    }

    /** Most corrective-action ("after") photos a ticket can hold; same limit as the submit form. */
    private const MAX_CORRECTION_PHOTOS = 5;

    /**
     * Submitter action: add corrective-action ("after") pictures to their own ticket.
     *
     * The submitter pages are reached with just a Staff ID and no login, so this is deliberately
     * narrow: Open tickets only, PNG/JPEG/GIF only (no SVG), and a cap on photos per ticket.
     */
    public function UploadCorrection(Request $request, $status, $ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if (!$ticket) {
            return redirect()->route('ShowSearchTicketForm')->withErrors(['ticket_id' => 'Ticket not found.']);
        }

        $back = redirect()->route('SearchTicketDetail', ['status' => $ticket->status, 'ticket_id' => $ticket->id]);

        if ($ticket->status !== 'Open') {
            Alert::alert('Pictures can only be added while the ticket is still open.', 'bg-red-200');
            return $back;
        }

        $remaining = self::MAX_CORRECTION_PHOTOS
            - TicketAttachment::where([['ticket_id', $ticket->id], ['level', 2]])->count();
        if ($remaining <= 0) {
            Alert::alert('This ticket already has the maximum of ' . self::MAX_CORRECTION_PHOTOS . ' corrective action photos.', 'bg-red-200');
            return $back;
        }

        $request->validate([
            'attachment_correction' => ['required', 'array', 'max:' . $remaining],
            'attachment_correction.*' => ['file', 'mimes:jpeg,jpg,png,gif', 'max:50000'],
        ], [
            'attachment_correction.required' => 'Please choose at least one picture.',
            'attachment_correction.max' => 'You can add ' . $remaining . ' more photo' . ($remaining == 1 ? '' : 's') . ' to this ticket.',
            'attachment_correction.*.mimes' => 'Only PNG, JPEG & GIF pictures are supported. If your phone saves photos as HEIC, set the camera format to "Most Compatible" (iPhone) or JPEG and try again.',
            'attachment_correction.*.max' => 'Each picture must be smaller than 50MB.',
        ]);

        collect($request->file('attachment_correction'))->each(function ($file) use ($ticket) {
            $fileName = Str::random(40) . '.' . $file->extension();
            $filePath = 'ticket/ticket_' . $ticket->id . '/2/' . $fileName;

            $newAttach = new TicketAttachment;
            $newAttach->ticket_id = $ticket->id;
            $newAttach->file_name = $file->getClientOriginalName();
            $newAttach->level = 2;
            $newAttach->file_rand_name = $fileName;
            $newAttach->file_path = $filePath;
            $newAttach->save();

            Storage::disk('public')->putFileAs('ticket/ticket_' . $ticket->id . '/2/', $file, $fileName);
        });

        Alert::alert('Picture(s) uploaded.', 'bg-green-200');

        return $back;
    }

    /**
     * Shared department/plant/status/date/search filtering used by both the
     * All Submissions list and the ticket export, so the two stay in sync.
     */
    private function filteredTicketsQuery(Request $request)
    {
        return Ticket::with([
            'plant',
            'plant_involve',
            'department',
            'sub_department',
            'dep_responsible',
            'dep_responsible.head_department',
            'sub_dep_responsible',
            'sub_dep_responsible.head_subdepartment',
            'gm_responsible',
            'stop_culture',
            'zero_harm',
            'rank',
            'site',
            'attachment',
            'approval'
        ])
            ->when($request->filled('department_id'), function ($query) use ($request) {
                $query->where('entry_tickets.department_id', $request->department_id);
            })
            ->when($request->filled('plant_id'), function ($query) use ($request) {
                $query->where('entry_tickets.plant_inv_id', $request->plant_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('entry_tickets.status', $request->status);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('entry_tickets.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('entry_tickets.created_at', '<=', $request->date_to);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($query) use ($search) {
                    $query->where('entry_tickets.ticket_id', 'like', "%{$search}%")
                        ->orWhere('entry_tickets.staff_id', 'like', "%{$search}%")
                        ->orWhere('entry_tickets.name', 'like', "%{$search}%");
                });
            })
            ->orderBy('entry_tickets.created_at', 'desc');
    }

    public function ShowAllSubmissions(Request $request)
    {
        $sortable = [
            'id' => 'entry_tickets.id',
            'ticket_id' => 'entry_tickets.ticket_id',
            'name' => 'entry_tickets.name',
            'department' => 'departments.name',
            'plant' => 'plants.name',
            'status' => 'entry_tickets.status',
        ];

        $sortField = $request->input('sort', 'id');
        $sortField = array_key_exists($sortField, $sortable) ? $sortField : 'id';
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $query = $this->filteredTicketsQuery($request);

        if ($sortField === 'department') {
            $query->select('entry_tickets.*')
                ->leftJoin('departments', 'departments.id', '=', 'entry_tickets.department_id');
        } elseif ($sortField === 'plant') {
            $query->select('entry_tickets.*')
                ->leftJoin('plants', 'plants.id', '=', 'entry_tickets.plant_inv_id');
        }

        $ticket = $query->reorder($sortable[$sortField], $sortDirection)
            ->paginate(10)
            ->withQueryString();

        return view('Ticket.all_submissions', [
            'tickets' => $ticket,
            'departments' => Department::orderBy('name', 'asc')->get(),
            'plants' => Plant::orderBy('name', 'asc')->get(),
            'filters' => $request->only(['department_id', 'plant_id', 'status', 'date_from', 'date_to', 'search']),
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function ShowExportPage(Request $request)
    {
        $filters = $request->only(['department_id', 'plant_id', 'status', 'date_from', 'date_to', 'search']);

        return view('Ticket.export', [
            'departments' => Department::orderBy('name', 'asc')->get(),
            'plants' => Plant::orderBy('name', 'asc')->get(),
            'filters' => $filters,
            'matchCount' => $this->filteredTicketsQuery($request)->count(),
        ]);
    }

    public function DownloadTicketsExport(Request $request)
    {
        $tickets = $this->filteredTicketsQuery($request)->get();

        return Excel::download(new TicketsExport($tickets), 'tickets-' . now()->format('Y-m-d_His') . '.xlsx');
    }

    public function ShowDetail(Request $request, $ticket_id)
    {
        $ticket = Ticket::with([
            'plant',
            'plant_involve',
            'plant_involve.head_plant',
            'department',
            'sub_department',
            'dep_responsible',
            'dep_responsible.head_department',
            'dep_responsible.division.head_div',
            'sub_dep_responsible',
            'sub_dep_responsible.head_subdepartment',
            'gm_responsible',
            'stop_culture',
            'zero_harm',
            'rank',
            'site',
            'attachment',
            'approval'
        ])->find($ticket_id);

        if (!$ticket) {
            return redirect()->route('ShowAllSubmissions')->withErrors(['ticket_id' => 'Ticket not found.']);
        }

        // Manual assignment of the level-1 approver is only offered to admins while the ticket
        // is still waiting at level 1.
        $level1Approval = Approval::with(['assignees', 'assignedBy'])
            ->where([['ticket_id', $ticket->id], ['approver_level', 1]])
            ->first();
        $canAssign = $this->isAdminUser() && $ticket->status === 'Open' && $ticket->pending_at_level == 1;
        $assignableUsers = $canAssign ? $this->assignableApprovers() : collect();

        return view('Ticket.submission_detail', [
            'ticket' => $ticket,
            'level1Approval' => $level1Approval,
            'canAssign' => $canAssign,
            'assignableUsers' => $assignableUsers,
        ]);
    }

    /**
     * Admin action: manually assign one or more people to the level-1 approval of a ticket.
     * Submitting an empty list clears the assignment, which returns the ticket to the normal
     * behaviour where any routed head can verify it.
     */
    public function AssignApprover(Request $request, $ticketId)
    {
        if (!$this->isAdminUser()) {
            abort(403, 'Admin ONLY');
        }

        $ticket = Ticket::find($ticketId);
        if (!$ticket) {
            return redirect()->route('ShowAllSubmissions')->withErrors(['ticket_id' => 'Ticket not found.']);
        }

        $approval = Approval::where([['ticket_id', $ticket->id], ['approver_level', 1]])->first();
        if (!$approval || $ticket->status !== 'Open' || $ticket->pending_at_level != 1) {
            Alert::alert('This ticket is no longer waiting for a level-1 approver.', 'bg-red-200');
            return redirect()->route('ShowDetail', ['ticketId' => $ticket->id]);
        }

        $requestedIds = collect($request->input('approver_ids', []))
            ->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values();

        $assignable = $this->assignableApprovers()->keyBy('id');
        if ($requestedIds->contains(function ($id) use ($assignable) {
            return !$assignable->has($id);
        })) {
            Alert::alert('One of the selected users cannot be assigned as an approver.', 'bg-red-200');
            return redirect()->route('ShowDetail', ['ticketId' => $ticket->id]);
        }

        $previousIds = $approval->assignees()->pluck('users.id');
        $approval->assignees()->sync($requestedIds->all());
        $approval->assigned_by_id = $requestedIds->isNotEmpty() ? Auth::id() : null;
        $approval->assigned_at = $requestedIds->isNotEmpty() ? Carbon::now()->toDateTimeString() : null;
        $approval->save();

        if ($requestedIds->isEmpty()) {
            Alert::alert('Assignment cleared. Any routed approver can verify this ticket.', 'bg-green-200');
            return redirect()->route('ShowDetail', ['ticketId' => $ticket->id]);
        }

        $names = $requestedIds->map(function ($id) use ($assignable) {
            return $assignable[$id]->name;
        })->implode(', ');

        // Email only the people who were just added, using the same "please verify" email the
        // routed heads receive, so re-saving the list doesn't notify everyone again.
        $newlyAdded = $requestedIds->diff($previousIds)->map(function ($id) use ($assignable) {
            return $assignable[$id];
        });
        $emails = $newlyAdded->pluck('email')->filter()->unique()->values()->all();

        if (empty($emails)) {
            Alert::alert('Assigned to ' . $names . '.', 'bg-green-200');
        } else {
            try {
                Mail::to($emails)
                    ->queue(new PendingApproval($ticket->id, Unsafe::find($ticket->ucua_type)->id ?? null, 1));
                Alert::alert('Assigned to ' . $names . '. New assignees have been notified by email.', 'bg-green-200');
            } catch (\Exception $e) {
                Log::error('Failed to email assigned approvers: ' . $e->getMessage(), ['exception' => $e]);
                Alert::alert('Assigned to ' . $names . ', but the notification email could not be sent.', 'bg-yellow-200');
            }
        }

        return redirect()->route('ShowDetail', ['ticketId' => $ticket->id]);
    }

    /** Groups whose members act as level-1 approvers, and can therefore be assigned to a ticket. */
    private const LEVEL1_GROUPS = ['hodiv', 'hodept', 'hosubdept', 'hop', 'hos'];

    private function isAdminUser(): bool
    {
        return Auth::user()->groups->pluck('name')->contains(function ($role) {
            return in_array($role, ['admin', 'she_admin']);
        });
    }

    /**
     * Users an admin may assign as level-1 approver, each with a `role_label` for the dropdown.
     */
    private function assignableApprovers()
    {
        return User::with('groups')
            ->where(function ($query) {
                $query->whereNull('is_enabled')->orWhere('is_enabled', '!=', '0');
            })
            ->whereHas('groups', function ($query) {
                $query->whereIn('name', self::LEVEL1_GROUPS);
            })
            ->orderBy('name')
            ->get()
            ->each(function ($user) {
                $user->role_label = $user->groups
                    ->whereIn('name', self::LEVEL1_GROUPS)
                    ->pluck('name_display')
                    ->implode(', ');
            });
    }
}
