<?php

namespace App\Http\Controllers;

use App\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignationController extends Controller
{
    public function get(){
//        $designation=Designation::get();

        $designation=Designation::select('*')->where('status', 'active')->get();
        //$users = DB::table('users')->get();
       // $designation=Designation::where('status', 'active');

//        $designation = DB::Designation('designations')->where('status', 'active')->get();

//        $designation = DB::table('Designation')->where('status', 'active')->get();
        return $designation;
    }

    public function postDesignationInfo(Request $r){

        $this->validate($r,[
            'title' =>'required|max:50',
            'shortName' =>'nullable|max:20',
            'salaryGrade' =>'nullable|max:10',
            'desigLevel' =>'nullable|max:4',
            'created_by' =>'nullable|max:20',
        ]);
        if($r->id){
            $desigNationInfo = Designation::findOrFail($r->id);
        }
        else{
            $desigNationInfo = new Designation();
        }
        $desigNationInfo->title = $r->title;
        $desigNationInfo->shortName= $r->shortName;
        $desigNationInfo->salaryGrade = $r->salaryGrade;
        $desigNationInfo->desigLevel= $r->desigLevel;
        $desigNationInfo->created_by = auth()->user()->id;
        $desigNationInfo->save();
        return response()->json(["message"=>"Designation Saved"]);

    }

}
