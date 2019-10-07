<?php

namespace App\Http\Controllers;

use App\AttEmployeeMap;
use App\Employee;



use App\ShiftLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
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
        $joinInfo->inDeviceNo = $r->inDeviceNo;
        $joinInfo->outDeviceNo = $r->outDeviceNo;

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
            'supervisor','probationPeriod','employeeinfo.fkActivationStatus','employeeinfo.inDeviceNo','employeeinfo.outDeviceNo')
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
        $employee = Employee::select('attemployeemap.attDeviceUserId','employeeinfo.firstName','employeeinfo.lastName','employeeinfo.middleName','employeeinfo.EmployeeId','designations.title','departments.departmentName','employeeinfo.id as empid'
        ,'employeeinfo.weekend')
            ->leftjoin('designations','designations.id','=','employeeinfo.fkDesignation')
            ->leftjoin('departments','departments.id','=','employeeinfo.fkDepartmentId')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('resignDate', null);
//            ->where('employeeinfo.fkCompany' , auth()->user()->fkCompany);

        $datatables = Datatables::of($employee);
        return $datatables->make(true);
    }
    public function getAllEmployeeInfo(){
        return $employee = Employee::select('employeeinfo.firstName','employeeinfo.lastName','employeeinfo.middleName','employeeinfo.EmployeeId','designations.title','departments.departmentName',
            'employeeinfo.id as empid','attemployeemap.attDeviceUserId')
            ->leftjoin('designations','designations.id','=','employeeinfo.fkDesignation')
            ->leftjoin('departments','departments.id','=','employeeinfo.fkDepartmentId')
            ->leftjoin('attemployeemap','attemployeemap.employeeId','=','employeeinfo.id')
            ->where('resignDate', null)
            ->whereNotNull('attemployeemap.attDeviceUserId')
            ->get();

    }
    public function getAllEmployeeInfoForDepartment(Request $r)
    {
        return $employee = Employee::select('employeeinfo.firstName','employeeinfo.lastName','employeeinfo.middleName','employeeinfo.EmployeeId','designations.title','departments.departmentName',
            'employeeinfo.id as empid','attemployeemap.attDeviceUserId')
            ->leftjoin('designations','designations.id','=','employeeinfo.fkDesignation')
            ->leftjoin('departments','departments.id','=','employeeinfo.fkDepartmentId')
            ->leftjoin('attemployeemap','attemployeemap.employeeId','=','employeeinfo.id')
            ->where('resignDate', null)
            ->whereNotNull('attemployeemap.attDeviceUserId')
            ->whereIn('departments.id',$r->departments)
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

    public function storeBasicInfo(Request $r){

//        return auth()->user()->fkComapny;
        //return $r;
        $this->validate($r, [
            'EmployeeId' => 'nullable|max:20',
            'firstName'   => 'required|max:50',
            'middleName'   => 'nullable|max:50',
            'lastName'   => 'nullable|max:50',
            'nickName'   => 'nullable|max:100',
            'fkDepartmentId'   => 'max:11',
            'fkDesignation'   => 'max:11',
            'fkEmployeeType'   => 'max:11',
            'email'   => 'required|max:255',
            'contactNo'   => 'nullable|max:15',
            'alterContactNo'   => 'nullable|max:15',
            'birthdate'   => 'nullable|date',
            'gender'   => 'max:1',
            'photo'   => 'max:256',
        ]);

        if($r->id){
            $employeeInfo = Employee::findOrFail($r->id);
        }
        else {

            $employeeInfo = new Employee();
            $user=new User();
            $user->email=$r->email;
            $user->userName=$r->firstName;
            $user->fkUserType="emp";
            $user->fkCompany=auth()->user()->fkComapny;
            $user->fkActivationStatus=1;
            $user->password=Hash::make('123456');
            $user->save();
            $employeeInfo->fkUserId=$user->id;
            $employeeInfo->createdBy=auth()->user()->id;
            $employeeInfo->fkCompany=auth()->user()->fkCompany;
            //  $employeeInfo->createdBy=1;
        }
        $employeeInfo->EmployeeId =$r->EmployeeId;
        $employeeInfo->firstName = $r->firstName;
        $employeeInfo->middleName =$r->middleName;
        $employeeInfo->lastName = $r->lastName;
        $employeeInfo->nickName =$r->nickName;
        $employeeInfo->fkDepartmentId=$r->department;
        $employeeInfo->fkDesignation=$r->designation;
        $employeeInfo->fkEmployeeType=$r->empType;
        $employeeInfo->email=$r->email;
        $employeeInfo->contactNo=$r->contactNo;
        $employeeInfo->alterContactNo=$r->alterContactNo;
        $employeeInfo->birthdate=$r->birthdate;
        $employeeInfo->gender =$r->gender;
        $employeeInfo->save();

        if($r->hasFile('photo')){

            if ($employeeInfo->photo != null){

                $file_path = public_path('/images').'/'.$employeeInfo->photo;
                unlink($file_path);

            }



            $images = $r->file('photo');
            $name = time().'.'.$images->getClientOriginalName();
            $destinationPath = public_path('/images');
            $images->move($destinationPath, $name);
            $employeeInfo->photo=$name;
        }
        $employeeInfo->save();

        Artisan::call('cache:clear');

        return $employeeInfo;
    }
}
