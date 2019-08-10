<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Employee;
use App\ShiftLog;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;
use Carbon\Carbon;

class TestController extends Controller
{

    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
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
    public function testRumi($fromDate,$toDate){
        ini_set('max_execution_time', 0);
        $start = microtime(true);
//
//        $fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
//        $toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
//        $fromDate ='2019-06-01';
//        $toDate = '2019-06-30';



//         return $results=AttendanceData::select(
//            'attemployeemap.employeeId','shift.shiftId',
//
//           'employeeinfo.fkDepartmentId',
//
//            DB::raw("LOWER(date_format(attendancedata.accessTime,'%W'))day"),
//            DB::raw("date_format(attendancedata.accessTime,'%Y-%m-%d') attendanceDate"),
//            DB::raw("date_format(min(attendancedata.accessTime),'%H:%i:%s %p') checkIn"),
//            DB::raw("date_format(max(attendancedata.accessTime),'%H:%i:%s %p') checkOut"),
//
//            DB::raw("case when SUBTIME(date_format(min(attendancedata.accessTime),'%H:%i'),shift.inTime) > '00:00:01' then 'Y' else 'N' end late"),
//            DB::raw("TIME_FORMAT(SUBTIME(date_format(min(attendancedata.accessTime),'%H:%i'),shift.inTime),'%H:%i')  as lateTime"),
//            DB::raw("SUBTIME(date_format(max(attendancedata.accessTime),'%H:%i:%s'),date_format(min(attendancedata.accessTime),'%H:%i:%s')) workingTime")
//
//         )
//            ->leftJoin('attemployeemap',function($join) use ($fromDate,$toDate){
//                $join->on('attendancedata.attDeviceUserId', '=', 'attemployeemap.attDeviceUserId')
//                    ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'");
//            })
//
//            ->leftJoin('employeeinfo','employeeinfo.id','attemployeemap.employeeId')
//
//            ->leftJoin('roster_log',function($join) use ($fromDate,$toDate){
//                $join->on('roster_log.fkemployeeId', '=', 'attemployeemap.employeeId')
//                    ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between date_format(roster_log.startDate,'%Y-%m-%d') and ifnull(date_format(roster_log.endDate,'%Y-%m-%d'),curdate())");
//            })
////             ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'")
//
//             ->leftJoin('shift',function($join) {
//
//                 $join
////                     ->on("shift.shiftId",'=','roster_log.thursday')
//                        ->whereRaw("CASE WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=saturday THEN shift.shiftId = roster_log.saturday
//                        WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=sunday THEN shift.shiftId = roster_log.sunday
//                        WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=monday THEN shift.shiftId = roster_log.monday
//                        WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=tuesday THEN shift.shiftId = roster_log.tuesday
//                        WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=wednessday THEN shift.shiftId = roster_log.saturday
//                        WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=thursday THEN shift.shiftId = roster_log.thursday
//                        WHEN LOWER(date_format(attendancedata.accessTime,'%W'))=friday THEN shift.shiftId = roster_log.friday"
//                     );
//
//
//            })
//
////             ->leftJoin('shift', 'roster_log.'DB::raw("LOWER(date_format(attendancedata.accessTime,"%W"))")', '=', 'report.tabelId')
//
////             ->leftJoin(DB::raw("(LOWER(date_format(attendancedata.accessTime,'%W')) day ) T"),function($join){
////                $join->on("T.day","=", "shift.shiftId");
////            })
//
////            ->leftJoin('shift','shift.shiftId','shiftlog.fkshiftId')
//            ->where('attemployeemap.employeeId','!=',null)
//
//            ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'")
//            ->groupBy("attendancedata.attDeviceUserId",DB::raw("date_format(attendancedata.accessTime,'%Y-%m-%d')"))
//         ->get();





        $startDate=Carbon::parse($fromDate);
        $endDate=Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);


         $allEmp=Employee::select('employeeinfo.id','attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
            'actualJoinDate','practice','weekend')
             ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->whereNull('resignDate')
            ->get();
        $toDate = Carbon::parse($toDate)->addDay(1);


         $sfl=ShiftLog::select("*")
            ->where(function ($query) use($fromDate,$toDate){
                $query->whereDate('startDate', '>=',$fromDate )
                    ->orWhere('endDate', '<=', $toDate);
            })
             ->get();




        return $rt=DB::select( DB::raw("select ad.accessTime,em.attDeviceUserId,em.employeeId,sl.inTime,sl.outTime
            
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            where date_format(ad.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'"));

         $rt=colect($rt);




//       return  $results = DB::select( DB::raw("select em.employeeId,ad.id,s.inTime,em.attDeviceUserId,sl.multipleShift
//            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
//            , date_format(min(ad.accessTime),'%Y-%m-%d %H:%i') checkIn
//            , case when s.inTime is not null and TIME(s.outTime) > TIME (s.inTime)  then date_format(max(ad.accessTime),'%Y-%m-%d %H:%i')
//             when s.inTime is not null and TIME(s.outTime) > TIME (s.inTime) and sl.multipleShift is not null then date_format(max(ad.accessTime),'%Y-%m-%d %H:%i')  else 'previousDay' end checkOut,
//             case when s.inTime is not null and SUBTIME(date_format(min(ad.accessTime),'%H:%i'),s.inTime) > '00:20:01' then 'Y' else 'N' end late
//            , case when s.inTime is not null then date_format(SUBTIME(date_format(min(ad.accessTime),'%H:%i'),s.inTime),'%H:%i') else '0' end lateTime
//            ,case when date_format(min(ad.accessTime),'%Y-%m-%d %H:%i')  > '08:00:00' AND date_format(min(ad.accessTime),'%Y-%m-%d %H:%i')  <  '22:00:00'  then 'Y' else 'N' end late
//            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
//            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
//            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
//            left join shift s on sl.fkshiftId = s.shiftId
//
//            where date_format(ad.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'
//            group by ad.attDeviceUserId, date_format(ad.accessTime,'%Y-%m-%d')"));
//
//
//         $results=collect($results);

//        return $results;


        $excelName="test";
        $filePath=public_path ()."/exportedExcel";
//        $fileName="AppliedCandidateList".date("Y-m-d_H-i-s");
        $fileName="HRTest".date("Y-m-d_H-i-s");
        $fileInfo=array(
            'fileName'=>$fileName,
            'filePath'=>$fileName,
        );


//        $check=Excel::create($fileName,function($excel)use ($results,$dates,$allEmp,$fromDate,$toDate, $startDate, $endDate) {
//
//                $excel->sheet('test', function ($sheet) use ($results,$dates,$allEmp, $fromDate,$toDate,$startDate, $endDate) {
////                    $sheet->freezePane('B4');
////                    $sheet->setStyle(array(
////                        'font' => array(
////                            'name' => 'Calibri',
////                            'size' => 10,
////                            'bold' => false
////                        )
////                    ));
//                    $sheet->loadView('Excel.attendenceTestRumi', compact('results','fromDate', 'toDate','dates','allEmp',
//                       'startDate','endDate'));
//                });
//
//        })->store('xls',$filePath);

        $check=Excel::create($fileName,function($excel)use ($sfl,$rt,$dates,$allEmp,$fromDate,$toDate, $startDate, $endDate) {

                $excel->sheet('test', function ($sheet) use ($sfl,$rt,$dates,$allEmp, $fromDate,$toDate,$startDate, $endDate) {

                    $sheet->loadView('Excel.attendenceTestRumiAnother', compact('results','fromDate', 'toDate','dates','allEmp',
                       'startDate','endDate'));
                });

        })->store('xls',$filePath);

        return $time = microtime(true) - $start;
    }

}
