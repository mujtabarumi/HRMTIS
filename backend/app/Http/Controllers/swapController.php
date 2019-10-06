<?php

namespace App\Http\Controllers;

use App\Swap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;

class swapController extends Controller
{
    public function getAllswapRequest(Request $r)
    {
        $getAllswapRequest=Swap::select('swap_details.*','shift_by.shiftName as shift_byName','shift_for.shiftName as shift_forName',DB::raw("CONCAT(COALESCE(empi.firstName,''),' ',COALESCE(empi.middleName,''),' ',COALESCE(empi.lastName,'')) AS empFullnameBy"),
                        DB::raw("CONCAT(COALESCE(empinfo.firstName,''),' ',COALESCE(empinfo.middleName,''),' ',COALESCE(empinfo.lastName,'')) AS empFullnameFor"))

            ->leftJoin('shift as shift_by','shift_by.shiftId','swap_details.swap_by_shift')
            ->leftJoin('shift as shift_for','shift_for.shiftId','swap_details.swap_for_shift')
            ->leftJoin('employeeinfo as empi','empi.id','swap_details.swap_by')
            ->leftJoin('employeeinfo as empinfo','empinfo.id','swap_details.swap_for');


        $datatables = Datatables::of($getAllswapRequest);
        return $datatables->make(true);

    }
}
