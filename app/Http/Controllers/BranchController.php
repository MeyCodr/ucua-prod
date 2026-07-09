<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Branch;
use App\Models\Group;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $state = State::orderBy('name', 'asc')->get();

        return view('branch.index', [
            'states' => $state,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = State::orderBy('name', 'asc')->get();
        $role = Group::where('name', 'branch_pic')->first();
        $pic = $role->users;
        $formMode = 'New';
        return view('branch.create', [
            'states' => $state,
            'formMode' => $formMode,
            'pic_branches' => $pic,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'branch_name' => 'required|string',
            'pic1' => 'required',
            'is_enabled' => 'required',
        ]);

        $branch = new Branch();
        $branch->state_id = $request->state_id;
        $branch->name = $request->branch_name;
        $branch->is_enabled = $request->is_enabled;
        $branch->save();

        $branch->pic_branch()->attach($request->pic1);

        Alert::alert('Saved!', 'bg-green-200');

        return redirect()->route('StateBranch.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($state_id, $branch_id)
    {
        $state = State::find($state_id);
        $branch = Branch::with('state', 'pic_branch')->find($branch_id);

        $group = Group::where('name', 'branch_pic')->first();

        // Get all users in the group
        $allUsers = $group->users;

        // Get current PICs for the branch
        $currentPics = $branch->pic_branch->pluck('id')->toArray();

        // Filter out users who are already PICs
        $availableUsers = $allUsers->filter(function ($user) use ($currentPics) {
            return !in_array($user->id, $currentPics);
        });

        return view('branch.show', [
            'state' => $state,
            'branch' => $branch,
            'users' => $availableUsers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::with('state', 'pic_branch')->find($id);

        $state = State::orderBy('name', 'asc')->get();
        $formMode = 'Edit';
        return view('branch.edit', [
            'branch' => $branch,
            'states' => $state,
            'formMode' => $formMode,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'state_id' => 'required',
            'branch_name' => 'required|string',
            'is_enabled' => 'required',
        ]);

        $branch = Branch::with('state', 'pic_branch')->find($id);
        $branch->state_id = $request->state_id;
        $branch->name = $request->branch_name;
        $branch->is_enabled = $request->is_enabled;
        $branch->save();

        Alert::alert('Saved!', 'bg-green-200');

        return redirect()->route('branch_detail', ['state_id' => $branch->state_id, 'branch_id' => $branch->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);
        $branch->delete();

        $branch->pic_branch()->detach();

        Alert::alert('Deleted!', 'bg-green-200');

        return redirect()->route('StateBranch.index');
    }

    public function add_pic(Request $request, $id)
    {
        $branch = Branch::find($id);
        $branch->pic_branch()->attach($request->pic_id);

        Alert::alert('New PIC added!', 'bg-green-200');

        return redirect()->route('branch_detail', ['state_id' => $branch->state_id, 'branch_id' => $branch->id]);
    }

    public function remove_pic(Request $request)
    {
        $branch = Branch::find($request->branch_id);
        $user = User::find($request->user_id);

        $branch->pic_branch()->detach($user->id);

        Alert::alert('PIC removed!', 'bg-green-200');

        return redirect()->route('branch_detail', ['state_id' => $branch->state_id, 'branch_id' => $branch->id]);
    }
}
