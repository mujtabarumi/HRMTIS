<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([

    'middleware' => 'api',

], function (){
    Route::get('/', function () {
        return response()->json(['message' => 'Successfully Working Get','flag'=>'true']);
    });

    Route::post('/', function (Request $r) {
        return $r;

    });


    //Test
    Route::post('/dateRanges','shiftController@getDatesFromRange');
    Route::get('/test','TestController@test');



    Route::post('login','AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('profile/password/change', 'AuthController@changePassword');

    /*Employee Info*/
    Route::post('employee/get','EmployeeController@getAllEmployee');
    Route::get('employee/getAll','EmployeeController@getAllEmployeeInfo');

    //Employee basicinfo
    Route::post('employee/basicinfo','EmployeeController@getBasicinfo');
    //EmployeeType Info
    Route::get('employee_type/get','EmployeeTypeController@get');
    //employee Join Info
    Route::post('joinInfo/get','EmployeeController@getJoinInfo');

    Route::post('joinInfo/post','EmployeeController@updateJoinInfo');


    //department Info
    Route::get('department/get','DepartmentController@get');
    Route::post('department/post','DepartmentController@postDepartment');

    //Designation Info
    Route::get('designation/get','DesignationController@get');

    //Company Info
    Route::get('company/get','CompanyController@get');

    //Shift
    Route::post('employee/shift/get','EmployeeController@getAllEmployeeForAttendance');
    Route::post('employee/leaveteam/get','EmployeeController@leaveTeam');
    Route::get('shift/get','shiftController@getShiftName');
    Route::post('shift/post','shiftController@createShift');
    Route::post('user/shift/get','shiftController@getUserShift');
    Route::post('shift/assigned-shift-show','shiftController@getEmpShiftForUpdate');
    Route::post('/getAllShift','shiftController@getAllShift');
    Route::post('shift/assign','shiftController@assignToShift');
    Route::post('dateRanges/AssignedShift','shiftController@getDatesFromRangeAssignedShift');
    Route::post('shift/assigned-shift-update','shiftController@updateShiftAssignedLog');
    Route::post('shift/assigned-shift-delete','shiftController@deleteShiftAssignedLog');

    Route::get('shift/getInfo/{id}','shiftController@getShiftInfo');
    Route::post('shift/adjustmentAdd','shiftController@addjustmentShiftLog');

    //Attendance
    Route::post('report/attendanceHR','AttendanceController@getAttendenceDataForHR');

    //Leave Apply
    Route::get('leave/getLeaveCategory','LeaveController@getLeaveCategory');
    Route::post('leave/assignLeave','LeaveController@dateRanges/AssignedShift');
    Route::post('leave/assignLeavePersonal','LeaveController@assignLeavePersonal');



});


