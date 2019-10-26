<?php

namespace App\Http\Controllers;

use App\StaticRosterLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StaticRosterController extends Controller
{

    public function getStaticRosterInfo(Request $r){

        return $staticRoster=StaticRosterLog::select(DB::raw("TRIM(BOTH '  ,  ' FROM GROUP_CONCAT(COALESCE(EmpDuty.firstName,''),' ',COALESCE(EmpDuty.middleName,''),' ',COALESCE(EmpDuty.lastName,''))) as EmpRosterNames"),
            DB::raw("TRIM(BOTH '  ,' FROM GROUP_CONCAT(COALESCE(EmpOffDuty.firstName,''),' ',COALESCE(EmpOffDuty.middleName,''),' ',COALESCE(EmpOffDuty.lastName,''))) as EmpRosterOffDutyNames")
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
            ->get();

    }

}
