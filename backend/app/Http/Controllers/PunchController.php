<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\ShiftLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PunchController extends Controller
{
    public function getEmpRoster(Request $r){



        return $roster=ShiftLog::where('fkemployeeId',$r->empId)->where('startDate',$r->date)->where('endDate',$r->date)->get();







    }
}
