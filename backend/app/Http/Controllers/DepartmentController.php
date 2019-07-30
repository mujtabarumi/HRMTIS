<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function get(){
        $departments=Department::get();
        return $departments;
    }

    /*
    * Error In Created_by field
    * it send the null value
    * not get anything from the api function call
    * auth()->user()->id non object property
    */


    public function postDepartment(Request $r){
//        return $r;
        $this->validate($r,[
            'departmentName' => 'required|max:255',
            'deptLevel' => 'nullable|max:6',
        ]);
        if($r->id){
            $department = Department::findOrFail($r->id);
        }
        else{
            $department = new Department();
            $department->createdBy = auth()->user()->id;
        }

        $department->fkCompany = auth()->user()->fkCompany;
        $department->departmentName = $r->departmentName;
        $department->deptLevel = $r->deptLevel;

        $department->save();
        return response()->json(["message" =>'Department Updated']);
    }
}
