<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use App\Shift;
use App\Swap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;
use Auth;

class swapController extends Controller
{
    public function getAllswapRequest(Request $r)
    {
        $getAllswapRequest=Swap::select('swap_details.*','shift_by.shiftName as shift_byName','shift_for.shiftName as shift_forName',DB::raw("CONCAT(COALESCE(empi.firstName,''),' ',COALESCE(empi.middleName,''),' ',COALESCE(empi.lastName,'')) AS empFullnameBy"),
                        DB::raw("CONCAT(COALESCE(empinfo.firstName,''),' ',COALESCE(empinfo.middleName,''),' ',COALESCE(empinfo.lastName,'')) AS empFullnameFor"))

            ->leftJoin('shift as shift_by','shift_by.shiftId','swap_details.swap_by_shift')
            ->leftJoin('shift as shift_for','shift_for.shiftId','swap_details.swap_for_shift')
            ->leftJoin('employeeinfo as empi','empi.id','swap_details.swap_by')
            ->leftJoin('employeeinfo as empinfo','empinfo.id','swap_details.swap_for');


        $datatables = Datatables::of($getAllswapRequest);
        return $datatables->make(true);

    }
    public function getAllShiftByRequesterDepartment(){

         $emp=Employee::select('fkDepartmentId','id')->where('fkUserId',Auth::user()->id)->first();

        return Shift::where('fkDepartmentId',$emp['fkDepartmentId'])->get();



    }
    public function getAllemployeeByRequesterDepartment(){


          $emp=Employee::select('fkDepartmentId','id')->where('fkUserId',Auth::user()->id)->first();

        return Employee::select(DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),'id')->where('fkDepartmentId',$emp['fkDepartmentId'])->get();



    }
    public function getEmpSwapReq(Request $r){

        $emp=Employee::select('fkDepartmentId','id')->where('fkUserId',Auth::user()->id)->first();


        $getAllswapRequest=Swap::select('swap_details.*','shift_by.shiftName as shift_byName','shift_for.shiftName as shift_forName',DB::raw("CONCAT(COALESCE(empi.firstName,''),' ',COALESCE(empi.middleName,''),' ',COALESCE(empi.lastName,'')) AS empFullnameBy"),
            DB::raw("CONCAT(COALESCE(empinfo.firstName,''),' ',COALESCE(empinfo.middleName,''),' ',COALESCE(empinfo.lastName,'')) AS empFullnameFor"))

            ->where('swap_details.swap_by',$emp['id'])

            ->leftJoin('shift as shift_by','shift_by.shiftId','swap_details.swap_by_shift')
            ->leftJoin('shift as shift_for','shift_for.shiftId','swap_details.swap_for_shift')
            ->leftJoin('employeeinfo as empi','empi.id','swap_details.swap_by')
            ->leftJoin('employeeinfo as empinfo','empinfo.id','swap_details.swap_for');


        $datatables = Datatables::of($getAllswapRequest);
        return $datatables->make(true);


    }
    public function submitNewSwapRequestByEmp(Request $r){




        $emp=Employee::select('fkDepartmentId','id')->where('fkUserId',Auth::user()->id)->first();

         $swapByShift=Shift::findOrFail($r->swap_by_shift);
         $swapForShift=Shift::findOrFail($r->swap_for_shift);

         if ($r->id!="")
         {
             $swap=Swap::findOrFail($r->id);
         }else{
             $swap=new Swap();
         }



        $swap->swap_by=$emp['id'];
        $swap->swap_by_date=Carbon::parse($r->swap_by_Date)->format('Y-m-d');
        $swap->swap_by_shift=$r->swap_by_shift[0]['shiftId'];

        $swap->swap_by_inTime=$swapByShift[0]['inTime'];
        $swap->swap_by_outTime=$swapByShift[0]['outTime'];

        $swap->swap_for=$r->swap_for[0]['id'];
        $swap->swap_for_date=Carbon::parse($r->swap_for_date)->format('Y-m-d');
        $swap->swap_for_shift=$r->swap_for_shift[0]['shiftId'];

        $swap->swap_for_inTime=$swapForShift[0]['inTime'];
        $swap->swap_for_outTime=$swapForShift[0]['outTime'];

//        $swap->departmentHeadApproval=null;
//        $swap->HR_adminApproval=0;

        $swap->created_by=$emp['id'];

        $swap->save();

        return 0;



    }
    public function editSwapRequest(Request $r){

        return $getSwapRequest=Swap::select('swap_details.*','shift_by.shiftName as shift_byName','shift_for.shiftName as shift_forName',DB::raw("CONCAT(COALESCE(empi.firstName,''),' ',COALESCE(empi.middleName,''),' ',COALESCE(empi.lastName,'')) AS empFullnameBy"),
            DB::raw("CONCAT(COALESCE(empinfo.firstName,''),' ',COALESCE(empinfo.middleName,''),' ',COALESCE(empinfo.lastName,'')) AS empFullnameFor"))
            ->leftJoin('shift as shift_by','shift_by.shiftId','swap_details.swap_by_shift')
            ->leftJoin('shift as shift_for','shift_for.shiftId','swap_details.swap_for_shift')
            ->leftJoin('employeeinfo as empi','empi.id','swap_details.swap_by')
            ->leftJoin('employeeinfo as empinfo','empinfo.id','swap_details.swap_for')
            ->where('swap_details.id',$r->id)
            ->first();



    }
    public function acceptSwapReq(Request $r){

        

    }
}
