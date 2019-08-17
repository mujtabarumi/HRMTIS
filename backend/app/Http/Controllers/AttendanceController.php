<?php

namespace App\Http\Controllers;

use App\AttendanceData;
use App\Employee;

use App\Leave;
use App\OrganizationCalander;
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




        $fromDate=$r->startDate;
        $toDate= $r->endDate;


        ini_set('max_execution_time', 0);

        $startDate=Carbon::parse($fromDate);
        $endDate=Carbon::parse($toDate);

        $dates = $this->getDatesFromRange($startDate, $endDate);

        $allEmp=Employee::select('employeeinfo.id','attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"),
            'actualJoinDate','practice','weekend')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->whereNull('resignDate')
            ->get();

        $allLeave=Leave::leftJoin('leavecategories', 'leavecategories.id', '=', 'leaves.fkLeaveCategory')
            ->where('applicationStatus',"Approved")
            ->whereBetween('startDate',array($fromDate, $toDate))
            ->get();

        $allLeave=collect($allLeave);

        $allHoliday=OrganizationCalander::whereMonth('startDate', '=', date('m',strtotime($fromDate)))->orWhereMonth('endDate', '=', date('m',strtotime($toDate)))->get();


        $fromDate = Carbon::parse($fromDate)->subDays(1);
        $toDate = Carbon::parse($toDate)->addDays(1);


        $results = DB::select( DB::raw("select em.employeeId,ad.id,sl.inTime,sl.outTime,sl.multipleShift,sl.adjustmentDate
            , date_format(ad.accessTime,'%Y-%m-%d') attendanceDate
            , date_format(ad.accessTime,'%H:%i:%s') accessTime
            , date_format(ad.accessTime,'%Y-%m-%d %H:%i:%s') accessTime2
            from attendancedata ad left join attemployeemap em on ad.attDeviceUserId = em.attDeviceUserId
            and date_format(ad.accessTime,'%Y-%m-%d') between '" . $fromDate . "' and '" . $toDate . "'
            left join shiftlog sl on em.employeeId = sl.fkemployeeId and date_format(ad.accessTime,'%Y-%m-%d') between date_format(sl.startDate,'%Y-%m-%d') and ifnull(date_format(sl.endDate,'%Y-%m-%d'),curdate())
            where date_format(ad.accessTime,'%Y-%m-%d') between '".$fromDate."' and '".$toDate."'
            and em.employeeId is not null"));

          $results=collect($results);




        $excelName="test";
        $filePath=public_path ()."/exportedExcel";

        $fileName="HRTest".date("Y-m-d_H-i-s");
        $fileInfo=array(
            'fileName'=>$fileName,
            'filePath'=>$fileName,
        );

        $check=Excel::create($fileName,function($excel)use ($allLeave,$results,$dates,$allEmp,$fromDate,$toDate, $startDate, $endDate) {

            $excel->sheet('test', function ($sheet) use ($allLeave,$results,$dates,$allEmp, $fromDate,$toDate,$startDate, $endDate) {

                $sheet->loadView('Excel.attendenceTestRumiAnother', compact('allLeave','results','fromDate', 'toDate','dates','allEmp',
                    'startDate','endDate'));
            });

        })->store('xls',$filePath);

        return response()->json($fileName);




    }

}
