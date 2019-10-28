<?php

namespace App\Http\Controllers;

use App\Shift;
use App\StaticRosterLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StaticRosterController extends Controller
{

    public function getStaticRosterInfo(Request $r){

        return $staticRoster=StaticRosterLog::select('EmpDuty.id as EmployeeId',DB::raw("CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,'')) AS empFullname")
            ,'static_rosterlog.rosterLogId'
        )
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
//            ->groupBy('static_rosterlog.day')
//            ->groupBy('static_rosterlog.fkshiftId')
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







    }

}
