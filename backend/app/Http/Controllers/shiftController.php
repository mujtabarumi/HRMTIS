<?php

namespace App\Http\Controllers;

use App\Shift;
use App\ShiftLog;
use DateTime;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use DB;

class shiftController extends Controller
{
    function getDatesFromRange(Request $r, $format = 'Y-m-d') {
        $array = array();
        $start=$r->startDate;
        $end=$r->endDate;

        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat='l';
        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach($period as $date) {
            $newArray=array(
                'date'=>  $date->format($format),
                'day'=>$date->format($anotherFormat),
            );
            array_push($array,$newArray);
//            $array['date'] = $date->format($format);
//            $array['day'] = $date->format($anotherFormat);
        }
        return $array;
    }
    function getDatesFromRangeAssignedShift(Request $r, $format = 'Y-m-d') {

//        return $shiftName = ShiftLog::select('shiftlog.*',DB::raw("GROUP_CONCAT(shift.shiftName) AS shiftName"))
//            ->leftJoin('shift',"shift.shiftId",'shiftlog.fkshiftId')
//            ->where('shiftlog.fkemployeeId','=',$r->empId)
//            ->whereDate('shiftlog.startDate', '=', '2019-08-02')
//            ->where(function ($query) use ($format){
//                $query->where('shiftlog.endDate', '=', '2019-08-02');
////                    ->orWhere('endDate', '=', 1);
//            })
//            ->orderBy('shiftlog.shiftlogId','ASC')
//            ->get();


        $array = array();
        $start=$r->startDate;
        $end=$r->endDate;

        $interval = new \DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $anotherFormat='l';
        $period = new \DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach($period as $date) {
            $shiftName = ShiftLog::select('shiftlog.*',DB::raw("GROUP_CONCAT(shift.shiftName) AS shiftName"))
                ->leftJoin('shift',"shift.shiftId",'shiftlog.fkshiftId')
                ->where('shiftlog.fkemployeeId','=',$r->empId)
                ->whereDate('shiftlog.startDate', '=', $date->format($format))
                ->where(function ($query) use ($format,$date){
                    $query->where('shiftlog.endDate', '=', $date->format($format));
//                    ->orWhere('endDate', '=', 1);
                })
                ->orderBy('shiftlog.shiftlogId','ASC')
                ->first();
            $newArray=array(
                'date'=>  $date->format($format),
                'day'=>$date->format($anotherFormat),
                'shiftName'=>$shiftName['shiftName'],
                'shiftLogId'=>$shiftName['shiftlogId'],
                'empId'=>$shiftName['fkemployeeId'],
            );
            array_push($array,$newArray);
//            $array['date'] = $date->format($format);
//            $array['day'] = $date->format($anotherFormat);
        }
        return $array;
    }

    public function getShiftName(){
        $shift = Shift::orderBy('shiftId','desc')->get();

        return response()->json($shift);
    }
    public function createShift(Request $r){


        $this->validate($r,[
            'shiftName' => 'required|max:20',
            'inTime' => 'required',
            'outTime' => 'required',
        ]);
        if($r->shiftId ==null){
            $shift = new Shift();
        }
        else{
            $shift = Shift::findOrFail($r->shiftId);
        }

        $shift->shiftName= $r->shiftName;
        $shift->inTime = $r->inTime  ;
        $shift->outTime = $r->outTime;
        $shift->crateBy= auth()->user()->id;
        $shift->fkcompanyId= auth()->user()->fkCompany;
        $shift->save();

        return Response()->json("Success");
    }
    public function getUserShift(Request $r){
        $shiftName = ShiftLog::where('fkemployeeId','=',$r->fkemployeeId)->orderBy('shiftlogId','desc')->first();
        return response()->json($shiftName);
    }

    public function getAllShift(Request $r){
        $shift = Shift::where('fkcompanyId',auth()->user()->fkCompany);
        $datatables = Datatables::of($shift);
        return $datatables->make(true);
    }
    public function getEmpShiftForUpdate(Request $r){


        return $shift = ShiftLog::where('fkemployeeId','=',$r->empId)
            ->whereDate('startDate', '>=', Carbon::parse($r->startDate)->format('y-m-d'))
            ->where(function ($query) use ($r){
                $query->where('endDate', '<=', Carbon::parse($r->endDate)->format('y-m-d'));
//                    ->orWhere('endDate', '=', 1);
            })
//            ->whereDate('endDate', '<=', Carbon::parse($r->endDate)->format('y-m-d'))
            ->get();

    }
    public function assignToShift(Request $r){


        $days=array();
        for ($i=0;$i<count($r->weekends);$i++){
            array_push($days,$r->weekends[$i]['item_id']);
        }
        $tags = implode(',',$days);
        $date= Carbon::parse($r->startDate)->format('y-m-d');
        $subDate=  Carbon::parse($r->startDate)->subDays(1)->format('y-m-d');

//        return Response()->json($subDate);

//        ShiftLog::whereIn('fkemployeeId',$r->allEmp)
//            ->where('endDate',null)
//            ->update(['endDate'=>$subDate]);

        foreach ($r->allEmp as $empId){

            foreach ($r->newShiftLog as $sfL){

                $log=new ShiftLog();

                $log->fkemployeeId=$empId;
                $log->fkshiftId=$sfL[0]['shift'];
                $log->startDate=$sfL[0]['date'];
                $log->endDate=$sfL[0]['date'];
                $log->weekend=$tags;

                $log->save();

            }

        }
        return Response()->json("Success");
    }
    public function updateShiftAssignedLog(Request $r){

        $shiftDelete=ShiftLog::where('fkemployeeId',$r->empId)->whereDate('startDate',$r->date)->whereDate('endDate',$r->date)->whereIn('fkshiftId',$r->shiftId)->delete();


        foreach ($r->shiftId as $shiftsId){

            $shiftLog=new ShiftLog();

            $shiftLog->fkemployeeId=$r->empId;
            $shiftLog->startDate=$r->date;
            $shiftLog->endDate=$r->date;
            $shiftLog->fkshiftId=$shiftsId;

            $shiftLog->save();

        }

//        if ($r->shiftLogId !=""){
//            $shiftLog=ShiftLog::findOrFail($r->shiftLogId);
//        }else{
//            $shiftLog=new ShiftLog();
//        }
//        $shiftLog->fkemployeeId=$r->empId;
//        $shiftLog->startDate=$r->date;
//        $shiftLog->endDate=$r->date;
//        $shiftLog->fkshiftId=$r->shiftId;
//
//        $shiftLog->save();


        return Response()->json("Success");
    }
    public function deleteShiftAssignedLog(Request $r){


//        $shiftLog=ShiftLog::destroy($r->shiftLogId);

        $shiftLog=ShiftLog::where('fkemployeeId',$r->empId)->whereDate('startDate',$r->date)->whereDate('endDate',$r->date)->delete();

        return Response()->json("Success");
    }
}