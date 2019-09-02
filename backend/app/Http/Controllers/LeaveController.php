<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use App\LeaveCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class LeaveController extends Controller
{
    public function getLeaveCategory(){
        $category=LeaveCategory::select('id','categoryName')->get();
        return $category;
    }
    public function getMyLeave(Request $r){

        $emp=Employee::where('fkUserId',auth()->user()->id)->first();
        $leaves=Leave::select('leaves.*','leavecategories.categoryName')
            ->where('fkEmployeeId',$emp->id)
            ->whereIn('leaves.fkLeaveCategory',[1,2,3,4])
            ->leftJoin('leavecategories','leavecategories.id','leaves.fkLeaveCategory')
            ->orderBy('leaves.id','desc')
            ->get();

        return $leaves;
    }
    public function assignLeave(Request $r){
        foreach ($r->allEmp as $empid){
            $leave=new Leave();
            $leave->fkEmployeeId=$empid;
            $leave->applicationDate=date('Y-m-d');
            $leave->fkLeaveCategory=$r->fkLeaveCategory;
//           Pending, Approved, Rejected
            $leave->applicationStatus="Approved";

            $leave->endDate= Carbon::parse($r->endDate)->format('Y-m-d');
            $leave->startDate=Carbon::parse($r->startDate)->format('Y-m-d');
            $leave->noOfDays=$r->noOfDays;
            $leave->remarks=$r->remarks;
            $leave->createdBy=auth()->user()->id;
            $leave->save();

        }
        return $r;
    }

    public function getLeaveSummeryDetails(Request $r){

        $leaves=Leave::select('leaves.*','leavecategories.categoryName')
            ->where('fkEmployeeId',$r->id)
            ->leftJoin('leavecategories','leavecategories.id','leaves.fkLeaveCategory')
            ->orderBy('leaves.id','desc')
            ->get();

        return $leaves;
    }

    public function getLeaveRequests(){
        $leaves=Leave::select('leaves.*','employeeinfo.firstName','employeeinfo.middleName','employeeinfo.lastName','leavecategories.categoryName')
            ->leftJoin('employeeinfo','employeeinfo.id','leaves.fkEmployeeId')
            ->leftJoin('leavecategories','leavecategories.id','leaves.fkLeaveCategory');

        $datatables = Datatables::of($leaves);
        return $datatables->make(true);

    }

    public function changeStatus(Request $r){
        Leave::where('id',$r->id)->update(['applicationStatus'=>$r->applicationStatus]);
        return $r;
    }

    public function getIndividual(Request $r){

        return Leave::select('leaves.*','employeeinfo.firstName','employeeinfo.middleName','employeeinfo.lastName')
            ->leftJoin('employeeinfo','employeeinfo.id','leaves.fkEmployeeId')
            ->findOrFail($r->id);
    }

    public function updateIndividual(Request $r){

        $leave=Leave::findOrFail($r->id);
        $leave->applicationDate=date('Y-m-d');
        $leave->fkLeaveCategory=$r->fkLeaveCategory;
        $leave->endDate= Carbon::parse($r->endDate)->format('Y-m-d');
        $leave->startDate=Carbon::parse($r->startDate)->format('Y-m-d');
        $leave->noOfDays=$r->noOfDays;
        $leave->remarks=$r->remark;

        if($r->status){
            $leave->applicationStatus=$r->status;
        }
        if($r->rejectCause){
            $leave->rejectCause=$r->rejectCause;
        }

        $leave->save();

    }
}
