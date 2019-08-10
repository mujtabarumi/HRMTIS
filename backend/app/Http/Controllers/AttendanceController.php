<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Employee;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use DB;
use Excel;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
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
    public function getAttendenceDataForHR(Request $r){

        ini_set('max_execution_time', 1444);



        $fromDate=$r->startDate;
        $toDate= $r->endDate;

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
        // return response()->json($toDate);

        $results = DB::select( DB::raw("select em.employeeId,ad.id,s.inTime,em.attDeviceUserId,sl.multipleShift
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(min(ad.accessTime),'%Y-%m-%d %H:%i') checkIn
            , case when TIME(sl.outTime) > TIME (sl.inTime) THEN 'nextDay' ELSE date_format(max(ad.accessTime),'%Y-%m-%d %H:%i') END checkOut
            , case when s.inTime is not null and SUBTIME(date_format(min(ad.accessTime),'%H:%i'),s.inTime) > '00:20:01' then 'Y' else 'N' end late
            , case when s.inTime is not null then date_format(SUBTIME(date_format(min(ad.accessTime),'%H:%i'),s.inTime),'%H:%i') else '0' end lateTime
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            left join shift s on sl.fkshiftId = s.shiftId
            where date_format(ad.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'
            group by ad.attDeviceUserId, date_format(ad.accessTime,'%Y-%m-%d')"));



        $results=collect($results);

//        return $results;


        $excelName="test";
        $filePath=public_path ()."/exportedExcel";
//        $fileName="AppliedCandidateList".date("Y-m-d_H-i-s");
        $fileName="HRTest".date("Y-m-d_H-i-s");
        $fileInfo=array(
            'fileName'=>$fileName,
            'filePath'=>$fileName,
        );


        $check=Excel::create($fileName,function($excel)use ($results,$dates,$allEmp,$fromDate,$toDate, $startDate, $endDate) {

            $excel->sheet('test', function ($sheet) use ($results,$dates,$allEmp, $fromDate,$toDate,$startDate, $endDate) {
//                    $sheet->freezePane('B4');
//                    $sheet->setStyle(array(
//                        'font' => array(
//                            'name' => 'Calibri',
//                            'size' => 10,
//                            'bold' => false
//                        )
//                    ));
                $sheet->loadView('Excel.attendenceTestRumi', compact('results','fromDate', 'toDate','dates','allEmp',
                    'startDate','endDate'));
            });

        })->store('xls',$filePath);

        return response()->json($fileName);



    }

}
