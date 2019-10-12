<?php

namespace App\Console\Commands;

use App\AttendanceData;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TimeSwapCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TimeSwap:Twice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Request to HR/Admin to change Roster Time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $currentDate=Carbon::now()->format('Y-m-d');
//
//        $allEmp = Employee::select('employeeinfo.id as empId','shiftlog.inTime' ,'attemployeemap.attDeviceUserId', 'employeeinfo.inDeviceNo', 'employeeinfo.outDeviceNo')
//            ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
//            ->leftJoin('shiftlog',function($join) use($currentDate) {
//
//                $join->on('shiftlog.fkemployeeId', '=', 'employeeinfo.id');
//                $join->where('shiftlog.startDate', '=', $currentDate);
//                $join->where('shiftlog.endDate', '=', $currentDate);
//            })
//
//            ->orderBy('employeeinfo.id', 'ASC')
//            ->whereNotNull('employeeinfo.fkDepartmentId')
//            ->get();
//
//        foreach ($allEmp as $aE){
//
//            AttendanceData::select('')
//
//        }

    }
}
