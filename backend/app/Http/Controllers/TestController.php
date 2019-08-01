<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;

class TestController extends Controller
{
    public function testRumi($fromDate,$toDate){
        ini_set('max_execution_time', 0);
        $start = microtime(true);
//
//        $fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
//        $toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
//        $fromDate ='2019-06-01';
//        $toDate = '2019-06-30';



         return $results=AttendanceData::select(
            'attemployeemap.employeeId',

           'employeeinfo.fkDepartmentId',

            DB::raw("LOWER(date_format(attendancedata.accessTime,'%W'))day"),
            DB::raw("date_format(attendancedata.accessTime,'%Y-%m-%d') attendanceDate"),
            DB::raw("date_format(min(attendancedata.accessTime),'%H:%i:%s %p') checkIn"),
            DB::raw("date_format(max(attendancedata.accessTime),'%H:%i:%s %p') checkOut"),

            DB::raw("case when SUBTIME(date_format(min(attendancedata.accessTime),'%H:%i'),shift.inTime) > '00:00:01' then 'Y' else 'N' end late"),
            DB::raw("TIME_FORMAT(SUBTIME(date_format(min(attendancedata.accessTime),'%H:%i'),shift.inTime),'%H:%i')  as lateTime"),
            DB::raw("SUBTIME(date_format(max(attendancedata.accessTime),'%H:%i:%s'),date_format(min(attendancedata.accessTime),'%H:%i:%s')) workingTime")

         )
            ->leftJoin('attemployeemap',function($join) use ($fromDate,$toDate){
                $join->on('attendancedata.attDeviceUserId', '=', 'attemployeemap.attDeviceUserId')
                    ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'");
            })

            ->leftJoin('employeeinfo','employeeinfo.id','attemployeemap.employeeId')

            ->leftJoin('roster_log',function($join) use ($fromDate,$toDate){
                $join->on('roster_log.fkemployeeId', '=', 'attemployeemap.employeeId')
                    ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between date_format(roster_log.startDate,'%Y-%m-%d') and ifnull(date_format(roster_log.endDate,'%Y-%m-%d'),curdate())");
            })
             ->leftJoin('shift',function($join){

                
                $join->whereRaw("1 = shift.shiftId");
//                $join->where(DB::raw("select roster_log.day"), "=","shift.shiftId");
//                $join->on("shift.shiftId",'=','roster_log.day');
            })
//             ->leftJoin(DB::raw("(LOWER(date_format(attendancedata.accessTime,'%W')) day ) T"),function($join){
//                $join->on("T.day","=", "shift.shiftId");
//            })

//            ->leftJoin('shift','shift.shiftId','shiftlog.fkshiftId')
            ->where('attemployeemap.employeeId','!=',null)

            ->whereRaw("date_format(attendancedata.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'")
            ->groupBy("attendancedata.attDeviceUserId",DB::raw("date_format(attendancedata.accessTime,'%Y-%m-%d')"))
         ->get();

        return $results=collect($results);



        $startDate=Carbon::parse($fromDate);
        $endDate=Carbon::parse($toDate);
        $dates = $this->getDatesFromRange($startDate, $endDate);

        $allEmp=EmployeeInfo::select('id','fkDepartmentId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
            'actualJoinDate','practice','weekend')
            ->whereNull('resignDate')
            ->get();



        $results = DB::select( DB::raw("select em.employeeId,ad.id
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(min(ad.accessTime),'%H:%i') checkIn
            , date_format(max(ad.accessTime),'%H:%i') checkOut
            ,SUBTIME(date_format(max(ad.accessTime),'%H:%i:%s'),date_format(min(ad.accessTime),'%H:%i:%s')) workingTime
            , case when SUBTIME(date_format(min(ad.accessTime),'%H:%i'),s.inTime) > '00:00:01' then 'Y' else 'N' end late
            , date_format(SUBTIME(date_format(min(ad.accessTime),'%H:%i'),s.inTime),'%H:%i')  as lateTime
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


        $check=Excel::create($fileName,function($excel)use ($results,$allDepartment,$dates,$allEmp,$allLeave,$fromDate,$toDate,
            $allHoliday,$startDate, $endDate,$comments) {
            foreach ($allDepartment as $ad) {
                $excel->sheet($ad->departmentName, function ($sheet) use ($results,$ad,$dates,$allEmp,$allLeave,
                    $fromDate,$toDate,$allHoliday,$startDate, $endDate,$comments) {
                    $sheet->freezePane('B4');
                    $sheet->setStyle(array(
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 10,
                            'bold' => false
                        )
                    ));
                    $sheet->loadView('Excel.attendenceTestRumi', compact('results','fromDate', 'toDate','dates','allEmp',
                        'ad','allLeave','allHoliday','startDate','endDate','comments'));
                });
            }
        })->store('xls',$filePath);

        return $time = microtime(true) - $start;
    }
}
