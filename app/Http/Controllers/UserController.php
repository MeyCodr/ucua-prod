<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\FormUser;
use App\Models\Department;
use App\Models\Group;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::orderBy('id', 'asc');

        if ($request->filled('name')) {
            $user->where('name', 'LIKE', "%{$request->name}%");
        }
        if ($request->filled('email')) {
            $user->where('email', 'LIKE', "%{$request->email}%");
        }

        $users = $user->paginate(10);

        return view('user.list', ['users' => $users, 'pageTitle' => 'Users', 'pageNum' => $users->currentPage()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "New User";
        $formType = 'New';
        $formRoute = route('User.store');
        $methodField = 'POST';
        $role = Group::all();
        $department = Department::with('subdepartment')->orderBy('name', 'asc')->get();

        return view('user.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'roles' => $role,
            'departments' => $department
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormUser $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->designation = $request->designation;
        $user->is_enabled = 1;
        $user->is_locked = 0;
        $user->num_failed_login_attempt = 0;
        $user->password = Hash::make($request->password); // hash password
        $user->password_expiry_date = Carbon::now()->addDays(90)->toDateTimeString();
        $user->save();

        $user->groups()->attach($request->role_id);
        $user->department()->attach($request->department_id);

        return redirect()->route('User.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $User)
    {
        return view('user.selected', ['user' => $User, 'pageNum' => $request->pageNum, 'isShowButtons' => true, 'approveButtonText' => 'Approve']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $User)
    {
        $pageTitle = "Edit User #" . $User->id;
        $formType = 'Edit';
        $formRoute = route('User.update', ['User' => $User->id]);
        $methodField = 'PUT';
        $role = Group::all();$role = Group::all();
        $department = Department::with('subdepartment')->orderBy('name', 'asc')->get();


        return view('user.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'user' => $User,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'roles' => $role,
            'departments' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormUser $request, User $User)
    {
        $User->name = $request->name;
        $User->phone_number = $request->phone_number;
        $User->designation = $request->designation;
        $User->is_enabled = 1;
        $User->is_locked = 0;
        $User->num_failed_login_attempt = 0;
        $User->save();

        $User->groups()->sync($request->role_id);
        $User->department()->sync($request->department_id);

        return redirect()->route('User.show', ['User' => $User->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('User.index');
    }

    public function sendMailPasswordResetLink(Request $request)
    {
        Password::sendResetLink(
            $request->only('email')
        );

        return redirect()->back();
    }
}
