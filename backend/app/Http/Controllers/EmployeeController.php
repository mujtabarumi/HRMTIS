<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    public function getAllEmployee(Request $r){
        $employee = Employee::select('employeeinfo.firstName','employeeinfo.lastName','employeeinfo.middleName','employeeinfo.EmployeeId','designations.title','departments.departmentName','employeeinfo.id as empid')
            ->leftjoin('designations','designations.id','=','employeeinfo.fkDesignation')
            ->leftjoin('departments','departments.id','=','employeeinfo.fkDepartmentId')
            ->where('resignDate', null)
            ->where('employeeinfo.fkCompany' , auth()->user()->fkCompany);

        $datatables = Datatables::of($employee);
        return $datatables->make(true);
    }
}
