<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
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
        $pageTitle = "New Plant";
        $formType = 'New';
        $formRoute = route('Plant.store', ['div_id' => $div_id, 'dept_id' => $dept_id]);
        $methodField = 'POST';

        $head_plant = Group::where('name', 'hop')->first()->users;

        return view('plant.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'div_id' => $div_id,
            'dept_id' => $dept_id,
            'head_plants' => $head_plant,
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
        $plant = new Plant();
        $plant->department_id = $dept_id;
        $plant->name = $request->name;
        $plant->short_name = $request->short_name;
        if ($request->user_head_id == 'No Head') {
            $plant->user_head_id = null;
        } else {
            $plant->user_head_id = $request->user_head_id;
        }
        $plant->save();

        // Update Department to have_plant = 1
        $department = Department::findOrFail($dept_id);
        $department->have_plant = 1;
        $department->save();

        return redirect()->route('Department.show', ['div_id' => $div_id, 'Department' => $dept_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($div_id, $dept_id, $plant_id)
    {
        $plant = Plant::findOrFail($plant_id);

        return view('plant.selected', [
            'plant' => $plant,
            'pageTitle' => 'Plant Details',
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
    public function edit($div_id, $dept_id, $plant_id)
    {
        $plant = Plant::findOrFail($plant_id);
        $pageTitle = "Edit Plant";
        $formType = 'Edit';
        $formRoute = route('Plant.update', ['div_id' => $div_id, 'dept_id' => $dept_id, 'Plant' => $plant->id]);
        $methodField = 'PUT';

        $head_plant = Group::where('name', 'hop')->first()->users;

        return view('plant.form', [
            'pageTitle' => $pageTitle,
            'formType' => $formType,
            'formRoute' => $formRoute,
            'methodField' => $methodField,
            'div_id' => $div_id,
            'dept_id' => $dept_id,
            'head_plants' => $head_plant,
            'plant' => $plant,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $div_id, $dept_id, $plant_id)
    {
        $plant = Plant::findOrFail($plant_id);
        $plant->name = $request->name;
        $plant->short_name = $request->short_name;
        if ($request->user_head_id == 'No Head') {
            $plant->user_head_id = null;
        } else {
            $plant->user_head_id = $request->user_head_id;
        }
        $plant->save();

        return redirect()->route('Plant.show', ['div_id' => $div_id, 'dept_id' => $dept_id, 'Plant' => $plant->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($div_id, $dept_id, $plant_id)
    {
        $plant = Plant::findOrFail($plant_id);
        $plant->user_head_id = null;
        $plant->save();
        $plant->delete();

        $countPlant = Plant::where('department_id', $dept_id)->count();
        if ($countPlant == 0) {
            $department = Department::findOrFail($dept_id);
            $department->have_plant = 0;
            $department->save();
        }

        return redirect()->route('Department.show', ['div_id' => $div_id, 'Department' => $dept_id]);
    }
}
