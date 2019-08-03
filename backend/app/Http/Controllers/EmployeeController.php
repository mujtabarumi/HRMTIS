<?php

namespace App\Http\Controllers;

use App\Employee;
use App\EmployeeInfo;
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
            ->where('resignDate', null);
//            ->where('employeeinfo.fkCompany' , auth()->user()->fkCompany);

        $datatables = Datatables::of($employee);
        return $datatables->make(true);
    }
    public function getAllEmployeeInfo(){
        return $employee = Employee::select('employeeinfo.firstName','employeeinfo.lastName','employeeinfo.middleName','employeeinfo.EmployeeId','designations.title','departments.departmentName','employeeinfo.id as empid')
            ->leftjoin('designations','designations.id','=','employeeinfo.fkDesignation')
            ->leftjoin('departments','departments.id','=','employeeinfo.fkDepartmentId')
            ->where('resignDate', null)
            ->get();

    }

    public function getAllEmployeeForAttendance(Request $r){

        $employee = Employee::select('shiftlog.startDate','shiftlog.weekend','shift.shiftName','employeeinfo.firstName','employeeinfo.middleName','employeeinfo.lastName','employeeinfo.EmployeeId','employeeinfo.id as empid')

            ->leftjoin('shiftlog','shiftlog.fkemployeeId','=','employeeinfo.id')
            ->leftjoin('shift','shift.shiftId','=','shiftlog.fkshiftId')

            ->where('employeeinfo.fkActivationStatus', 1)

            ->where('shiftlog.endDate',null);


        $datatables = Datatables::of($employee);
        return $datatables->make(true);
    }
}
