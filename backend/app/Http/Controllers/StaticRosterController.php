<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Shift;
use App\ShiftLog;
use App\StaticRosterLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StaticRosterController extends Controller
{

    public function getStaticRosterInfo(Request $r){

        return StaticRosterLog::select(

            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(EmpDuty.id)) as EmpRosterIds"),
            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(EmpOffDuty.id)) as EmpRosterOffDutyIds"),
            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")

            ,'static_rosterlog.rosterLogId')
            ->leftJoin('employeeinfo as EmpDuty',function($join) {
                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNull('static_rosterlog.weekend');
            })
            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
                    ->whereNotNull('static_rosterlog.weekend');
            })
            ->where('day',$r->day)
            ->where('static_rosterlog.fkshiftId',$r->shiftId)

            ->get();

    }
    public function setStaticRosterInfo(Request $r){



        $findShift=Shift::findOrFail($r->shiftId);

        if (count($r->dutyempIds)>0){

            foreach ($r->dutyempIds as $empIds){

                $newStaticRoster=new StaticRosterLog();
                $newStaticRoster->fkemployeeId=$empIds['empid'];
                $newStaticRoster->fkshiftId=$r->shiftId;
                $newStaticRoster->day=$r->dayName;
                $newStaticRoster->inTime=$findShift->inTime;
                $newStaticRoster->outTime=$findShift->outTime;
                $newStaticRoster->save();

            }

        }
       // if (count())







    }
    public function getDataFromStaticRoster(Request $r){

//        return StaticRosterLog::select(
//
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(EmpDuty.id)) as EmpRosterIds"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(EmpOffDuty.id)) as EmpRosterOffDutyIds"),
//            DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
//            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")
//
//            ,'static_rosterlog.rosterLogId')
//            ->leftJoin('employeeinfo as EmpDuty',function($join) {
//                $join->on('EmpDuty.id', '=', 'static_rosterlog.fkemployeeId')
//                    ->whereNull('static_rosterlog.weekend');
//            })
//            ->leftJoin('employeeinfo as EmpOffDuty',function($join) {
//                $join->on('EmpOffDuty.id', '=', 'static_rosterlog.fkemployeeId')
//                    ->whereNotNull('static_rosterlog.weekend');
//            })
//            ->where('day',$r->day)
//            ->where('static_rosterlog.fkshiftId',$r->shiftId)
//
//            ->get();


            $empIds=Employee::select('id')->where('fkDepartmentId',$r->departments)->whereNull('resignDate')->get();

            foreach ($empIds as $emp)
            {

            }



    }

}
