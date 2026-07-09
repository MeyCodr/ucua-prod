<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use App\Models\SubDepartment;
use Illuminate\Http\Request;

class SubDepartmentController extends Controller
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
    public function create($div_id, $dept_id)
    {
        $pageTitle = "New Sub Department";
        $formType = 'New';
        $formRoute = route('SubDepartment.store', ['div_id' => $div_id, 'dept_id' => $dept_id]);
        $methodField = 'POST';

        $head_subdepartment = Group::where('name', 'hosubdept')->first()->users;

        return view('subdepartment.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'div_id' => $div_id,
            'dept_id' => $dept_id,
            'head_subdepartments' => $head_subdepartment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $div_id, $dept_id)
    {
        $subdepartment = new SubDepartment();
        $subdepartment->department_id = $dept_id;
        $subdepartment->name = $request->name;
        $subdepartment->short_name = $request->short_name;
        if ($request->user_head_id == 'No Head') {
            $subdepartment->user_head_id = null;
        } else {
            $subdepartment->user_head_id = $request->user_head_id;
        }
        $subdepartment->save();

        // Update Department to have_sub_department = 1
        $department = Department::findOrFail($dept_id);
        $department->have_sub_department = 1;
        $department->save();

        return redirect()->route('Department.show', ['div_id' => $div_id, 'Department' => $dept_id])->with('success', 'Sub Department created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($div_id, $dept_id, $subdept_id)
    {
        $subdepartment = SubDepartment::findOrFail($subdept_id);

        return view('subdepartment.selected', [
            'subdepartment' => $subdepartment,
            'pageTitle' => 'Sub Department Details',
            'isShowButtons' => true,
            'div_id' => $div_id,
            'dept_id' => $dept_id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($div_id, $dept_id, $subdept_id)
    {
        $subdepartment = SubDepartment::findOrFail($subdept_id);

        $pageTitle = "Edit Sub Department #" . $subdepartment->id;
        $formType = 'Edit';
        $formRoute = route('SubDepartment.update', ['div_id' => $div_id, 'dept_id' => $dept_id, 'SubDepartment' => $subdepartment->id]);
        $methodField = 'PUT';

        $head_subdepartment = Group::where('name', 'hosubdept')->first()->users;

        return view('subdepartment.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'subdepartment' => $subdepartment,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'div_id' => $div_id,
            'dept_id' => $dept_id,
            'head_subdepartments' => $head_subdepartment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $div_id, $dept_id, $subdept_id)
    {
        $subdepartment = SubDepartment::findOrFail($subdept_id);
        $subdepartment->name = $request->name;
        $subdepartment->short_name = $request->short_name;
        if ($request->user_head_id == 'No Head') {
            $subdepartment->user_head_id = null;
        } else {
            $subdepartment->user_head_id = $request->user_head_id;
        }
        $subdepartment->save();

        return redirect()->route('SubDepartment.show', ['div_id' => $div_id, 'dept_id' => $dept_id, 'SubDepartment' => $subdepartment->id])->with('success', 'Sub Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($div_id, $dept_id, $subdept_id)
    {
        $subdepartment = SubDepartment::findOrFail($subdept_id);
        $subdepartment->user_head_id = null;
        $subdepartment->save();
        $subdepartment->delete();

        $countSubDept = SubDepartment::where('department_id', $dept_id)->count();
        if ($countSubDept == 0) {
            $department = Department::findOrFail($dept_id);
            $department->have_sub_department = 0;
            $department->save();
        }

        return redirect()->route('Department.show', ['div_id' => $div_id, 'Department' => $dept_id])->with('success', 'Sub Department deleted successfully.');
    }
}
