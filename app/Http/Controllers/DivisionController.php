<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Group;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortable = [
            'id' => 'divisions.id',
            'name' => 'divisions.name',
            'head_div' => 'users.name',
            'created_at' => 'divisions.created_at',
        ];

        $sortField = $request->input('sort', 'id');
        $sortField = array_key_exists($sortField, $sortable) ? $sortField : 'id';
        $sortDirection = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        $division = Division::with('head_div');

        if ($sortField === 'head_div') {
            $division->select('divisions.*')
                ->leftJoin('users', 'users.id', '=', 'divisions.user_head_id');
        }

        if ($request->filled('name')) {
            $division->where('divisions.name', 'LIKE', "%{$request->name}%");
        }

        $divisions = $division->orderBy($sortable[$sortField], $sortDirection)
            ->paginate(20);

        return view('division.list', [
            'divisions' => $divisions,
            'pageTitle' => 'Divisions',
            'pageNum' => $divisions->currentPage(),
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "New Division";
        $formType = 'New';
        $formRoute = route('Division.store');
        $methodField = 'POST';

        $head_div = Group::where('name', 'hodiv')->first()->users;

        return view('division.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'head_divs' => $head_div,
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
        $division = new Division();
        $division->name = $request->name;
        if ($request->user_head_id == 'No Head') {
            $division->user_head_id = null;
        } else {
            $division->user_head_id = $request->user_head_id;
        }
        $division->save();

        return redirect()->route('Division.index')->with('success', 'Division created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $division = Division::with('head_div', 'department')->findOrFail($id);

        return view('division.selected', [
            'division' => $division,
            'pageTitle' => 'Division Details',
            'isShowButtons' => true,
            'approveButtonText' => 'Approve'
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
        $division = Division::findOrFail($id);

        $pageTitle = "Edit Division #" . $division->id;
        $formType = 'Edit';
        $formRoute = route('Division.update', ['Division' => $division->id]);
        $methodField = 'PUT';

        $head_div = Group::where('name', 'hodiv')->first()->users;

        return view('division.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'division' => $division,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'head_divs' => $head_div,
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
        $division = Division::findOrFail($id);
        $division->name = $request->name;
        if ($request->user_head_id == 'No Head') {
            $division->user_head_id = null;
        } else {
            $division->user_head_id = $request->user_head_id;
        }
        $division->save();

        return redirect()->route('Division.index')->with('success', 'Division updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->user_head_id = null;
        $division->save();
        $division->delete();

        return redirect()->route('Division.index')->with('success', 'Division deleted successfully.');
    }
}
