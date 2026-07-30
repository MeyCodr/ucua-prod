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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;

class TicketController extends Controller
{
    public function ShowTicketForm()
    {
        $site = Site::orderBy('name', 'asc')->get();
        $department = Department::with('subdepartment')->orderBy('name', 'asc')->get();
        $plant = Plant::orderBy('name', 'asc')->get();
        $hodUsers = Division::with('head_div')->get();
        $condition = Unsafe::where('is_enabled', 1)->where('is_condition', 1)->orderBy('name', 'asc')->get();
        $act = Unsafe::where('is_enabled', 1)->where('is_act', 1)->orderBy('name', 'asc')->get();
        $stop_cult = StopCulture::all();
        $zero_harm = ZeroHarmRule::all();
        $rank = Rank::all();

        return view('Ticket.NewTicketForm', [
            'sites' => $site,
            'departments' => $department,
            'plants' => $plant,
            'hods' => $hodUsers,
            'conditions' => $condition,
            'acts' => $act,
            'stop_cults' => $stop_cult,
            'zero_harms' => $zero_harm,
            'ranks' => $rank,
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
            $yearlyCount = Ticket::whereYear('created_at', $year)->lockForUpdate()->count();
            $increment = str_pad($yearlyCount + 1, 5, '0', STR_PAD_LEFT);
            $ticket_id = $year . $month . $increment;

            // Create new ticket
            $ticket = new Ticket;
            $ticket->ticket_id = $ticket_id;
            $ticket->name = $validated['name'];
            $ticket->email = $validated['email'];
            $ticket->phone_number = $validated['phone_number'];
            $ticket->staff_id = $validated['staff_id'];
            $ticket->site_id = $validated['site_id'];
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
            $ticket->gm_res_id = $validated['gm_res_id'];
            $ticket->ucua_id = $validated['entry_unsafe_condition_act'];
            $ticket->ucua_type = $validated['entry_unsafe'];
            $ticket->ucua_other = ($validated['entry_unsafe'] == 0)
                ? ($validated['entry_unsafe_condition_act'] === 'unsafe_act' ? $validated['unsafe_act_other'] : $validated['unsafe_cond_other'])
                : null;
            $ticket->description = $validated['description'];
            $ticket->stop_cult_id = $validated['stop_cult_id'];
            $ticket->zero_harm_id = $validated['zero_harm_id'];
            $ticket->rank_id = $validated['rank_id'];
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

        // Get PICs
        $dep_res = Department::find($ticket->dept_res_id);
        $head_dep = $dep_res ? User::find($dep_res->user_head_id) : null;
        $sub_dep = SubDepartment::find($ticket->sub_dept_res_id);
        $head_sub_dep = $sub_dep ? User::find($sub_dep->user_head_id) : null;
        $plant_inv = Plant::find($ticket->plant_inv_id);
        $head_plant = $plant_inv ? User::find($plant_inv->user_head_id) : null;
        $hod = $dep_res->division->head_div ?? null;

        $users = collect($head_dep ? $head_dep->email : null)
            ->merge(collect($head_sub_dep ? $head_sub_dep->email : null))
            ->merge(collect($head_plant ? $head_plant->email : null))
            ->merge(collect($hod ? $hod->email : null));

        // dd($head_dep, $head_sub_dep, $head_plant, $hod, $users);

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
                return in_array($role, ['hodiv', 'hodept', 'hop', 'hos']);
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
                return in_array($role, ['hodiv', 'hodept', 'hop', 'hos']);
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
                return in_array($role, ['hodiv', 'hodept', 'hop', 'hos']);
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

        $tickets = Ticket::with([
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
        ])->whereIn('id', $approval)->orderBy('created_at', 'desc')->paginate(10);

        $category = $request->category;

        return view('Ticket.ListTickets', compact('tickets', 'tabs', 'category'));
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

        $mode = $request->mode;
        $category = $request->category;

        return view('Ticket.SelectedTicket', compact(
            'ticket',
            'approvalStatues',
            'isShowButton',
            'mode',
            'category',
            'approveButtonText',
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
        ])->where('staff_id', $staff_id);

        if ($request->status == 'Open') {
            $query->where('status', 'Open');
        } elseif ($request->status == 'Closed') {
            $query->where('status', 'Closed');
        } elseif ($request->status == 'Declined') {
            $query->where('status', 'Declined');
        } else {
            return redirect()->route('ShowSearchTicketForm')->withErrors(['status' => 'Invalid status selected.']);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10)->appends(['staff_id' => $staff_id]);

        $point_sum = PointHistory::where([['staff_id', $staff_id], ['action', 'New']])->sum('points');
        $point_minus = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem']])->sum('points');
        //check null
        $point = isset($point_sum) || isset($point_minus) ? $point_sum ?? 0 - $point_minus : 0;

        return view('submitter.submission_list', [
            'tickets' => $tickets,
            'staff_id' => $staff_id,
            'tabs' => $tabs,
            'status' => $request->status,
            'point' => $point,
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
                $query->where('department_id', $request->department_id);
            })
            ->when($request->filled('site_id'), function ($query) use ($request) {
                $query->where('site_id', $request->site_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($query) use ($search) {
                    $query->where('ticket_id', 'like', "%{$search}%")
                        ->orWhere('staff_id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc');
    }

    public function ShowAllSubmissions(Request $request)
    {
        $ticket = $this->filteredTicketsQuery($request)
            ->paginate(10)
            ->withQueryString();

        return view('Ticket.all_submissions', [
            'tickets' => $ticket,
            'departments' => Department::orderBy('name', 'asc')->get(),
            'plants' => Plant::orderBy('name', 'asc')->get(),
            'filters' => $request->only(['department_id', 'site_id', 'status', 'date_from', 'date_to', 'search']),
        ]);
    }

    public function ShowExportPage(Request $request)
    {
        $filters = $request->only(['department_id', 'site_id', 'status', 'date_from', 'date_to', 'search']);

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

        return view('Ticket.submission_detail', [
            'ticket' => $ticket
        ]);
    }
}
