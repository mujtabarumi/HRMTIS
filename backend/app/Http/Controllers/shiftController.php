<?php

namespace App\Http\Controllers;

use App\Shift;
use App\ShiftLog;
use DateTime;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

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

    public function getShiftName(){
        $shift = Shift::get();

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
        return response()->json($shift);
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
                $log->fkshiftId=$sfL['shift'];
                $log->startDate=$sfL['date'];
                $log->endDate=$sfL['date'];
                $log->weekend=$tags;

                $log->save();

            }

        }
        return Response()->json("Success");
    }
}