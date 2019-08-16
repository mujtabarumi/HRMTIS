<?php

namespace App\Http\Controllers;

use App\AttEmployeeMap;
use App\Employee;


use App\ShiftLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    public function updateJoinInfo(Request $r){



        $this->validate($r,[
            'accessPin' => 'nullable|max:11',
            'attDeviceUserId'   =>'max:11',
            'supervisor'   =>'max:255',

        ]);
        $days=array();
        for ($i=0;$i<count($r->weekend);$i++){
            array_push($days,$r->weekend[$i]['item_id']);
        }
        $tags = implode(',',$days);




        $joinInfo = Employee::findOrFail($r->id);
        if($r->actualJoinDate==null){
            $joinInfo->actualJoinDate = null;
        }
        else{
            $joinInfo->actualJoinDate = Carbon::parse($r->actualJoinDate)->format('Y-m-d');
        }

        if($r->resignDate==null){
            $joinInfo->resignDate = null;
        }
        else{
            $joinInfo->resignDate = Carbon::parse($r->resignDate)->format('Y-m-d');
        }


        $joinInfo->weekend = $tags;
        $joinInfo->accessPin = $r->accessPin;

        $joinInfo->supervisor = $r->supervisor;
        $joinInfo->probationPeriod = $r->probationPeriod;

        $joinInfo->fkActivationStatus = $r->fkActivationStatus;

        if($r->attDeviceUserId != null){
            if(AttEmployeeMap::where('employeeId',$r->id)){
                AttEmployeeMap::where('employeeId',$r->id)->update( ['attDeviceUserId'=>$r->attDeviceUserId]);
            }

            AttEmployeeMap::firstOrCreate([
                'attDeviceUserId' => $r->attDeviceUserId,
                'employeeId'   => $r->id,

            ]);
        }




        $joinInfo->save();


        return response()->json(["message"=>"Join Info updated"]);
    }

    public function getJoinInfo(Request $r){
        $joinInfo = Employee::select('attemployeemap.attDeviceUserId','actualJoinDate','resignDate','weekend','accessPin',
            'supervisor','probationPeriod','employeeinfo.fkActivationStatus')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('employeeinfo.id','=',$r->id)
            ->first();

        return response()->json($joinInfo);
    }

    public function getBasicinfo(Request $r){
        $basicinfo = Employee::select('EmployeeId','photo','firstName', 'middleName', 'lastName', 'fkEmployeeType','email' ,'gender', 'birthdate','contactNo','fkDesignation','fkDepartmentId','departmentName', 'title','alterContactNo')
            ->leftjoin('designations','designations.id','=','employeeinfo.fkDesignation')
            ->leftjoin('departments','departments.id','=','employeeinfo.fkDepartmentId')
            ->leftjoin('employeetypes','employeetypes.id','=','employeeinfo.fkEmployeeType')
            ->where('employeeinfo.id', $r->empid)
            ->first();

        return $basicinfo;
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
