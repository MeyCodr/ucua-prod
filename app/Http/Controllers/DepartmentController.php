<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use App\Models\Plant;
use App\Models\SubDepartment;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($div_id)
    {
        $pageTitle = "New Department";
        $formType = 'New';
        $formRoute = route('Department.store', ['div_id' => $div_id]);
        $methodField = 'POST';

        $head_department = Group::where('name', 'hodept')->first()->users;

        return view('department.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'div_id' => $div_id,
            'head_departments' => $head_department,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $div_id)
    {
        $department = new Department();
        $department->division_id = $div_id;
        $department->name = $request->name;
        $department->short_name = $request->short_name;
        if ($request->user_head_id == 'No Head') {
            $department->user_head_id = null;
        } else {
            $department->user_head_id = $request->user_head_id;
        }
        $department->have_plant = 0;
        $department->have_sub_department = 0;
        $department->save();

        return redirect()->route('Division.show', ['Division' => $div_id])
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($div_id, $dept_id)
    {
        $department = Department::with('head_department', 'division', 'plant', 'subdepartment')->where('division_id', $div_id)->findOrFail($dept_id);

        return view('department.selected', [
            'department' => $department,
            'pageTitle' => 'Department Details',
            'isShowButtons' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($div_id, $dept_id)
    {
        $department = Department::with('head_department', 'division', 'plant', 'subdepartment')->where('division_id', $div_id)->findOrFail($dept_id);
        $pageTitle = "Edit Department #" . $department->id;
        $formType = 'Edit';
        $formRoute = route('Department.update', ['div_id' => $div_id, 'Department' => $department->id]);
        $methodField = 'PUT';
        $head_department = Group::where('name', 'hodept')->first()->users;

        return view('department.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'department' => $department,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'div_id' => $div_id,
            'head_departments' => $head_department,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $div_id, $dept_id)
    {
        $department = Department::where('division_id', $div_id)->findOrFail($dept_id);
        $department->name = $request->name;
        $department->short_name = $request->short_name;
        if ($request->user_head_id == 'No Head') {
            $department->user_head_id = null;
        } else {
            $department->user_head_id = $request->user_head_id;
        }
        $department->save();

        return redirect()->route('Department.show', ['div_id' => $div_id, 'Department' => $department->id])
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($div_id, $dept_id)
    {
        $department = Department::where('division_id', $div_id)->findOrFail($dept_id);
        if ($department->have_sub_department) {
            $department->subdepartment()->delete();
        }
        if ($department->have_plant) {
            $department->plant()->delete();
        }
        $department->delete();

        return redirect()->route('Department.show', ['div_id' => $div_id, 'Department' => $department->id])
            ->with('success', 'Department deleted successfully.');
    }
}
