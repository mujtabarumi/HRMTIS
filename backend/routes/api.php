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
    Route::get('/test','TestController@test');

    Route::post('login','AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('profile/password/change', 'AuthController@changePassword');

    /*Employee Info*/
    Route::post('employee/get','EmployeeController@getAllEmployee');


    //department Info
    Route::get('department/get','DepartmentController@get');
    Route::post('department/post','DepartmentController@postDepartment');

    //Company Info
    Route::get('company/get','CompanyController@get');

    //Shift
    Route::post('employee/shift/get','EmployeeController@getAllEmployeeForAttendance');
    Route::post('employee/leaveteam/get','EmployeeController@leaveTeam');

    Route::get('shift/get','shiftController@getShiftName');
    Route::post('shift/post','shiftController@createShift');
    Route::post('user/shift/get','shiftController@getUserShift');
    Route::post('/getAllShift','shiftController@getAllShift');
    Route::post('shift/assign','shiftController@assignToShift');



});


