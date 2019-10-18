
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>

        <th colspan="12" style="text-align: center;">Multiple Roster Report 1 -( {{\Carbon\Carbon::parse($startDate)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($endDate)->format('Y-m-d')}} )</th>

    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="4"style="text-align: center;vertical-align: middle;" >Name: {{$allE->empFullname}}</th>
        <th colspan="3"style="text-align: center;vertical-align: middle;" >ID: {{$allE->attDeviceUserId}}</th>
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th colspan="4"style="text-align: center;vertical-align: middle;" >Department: {{$allE->departmentName}}</th>
        <th colspan="3"style="text-align: center;vertical-align: middle;" >Designation: {{$allE->designationTitle}}</th>
    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Date</th>

        @foreach($rosrLog->where('fkemployeeId',$allE->id) as $RL)
            <th class="Border" colspan="5" style="text-align: center;vertical-align: middle;" >{{$RL->inTime}}-{{$RL->outTime}}</th>
        @endforeach



    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($rosrLog->where('fkemployeeId',$allE->id) as $RL)

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20">Late Day / Hours</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Adjustment</th>
        @endforeach


    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($rosrLog->where('fkemployeeId',$allE->id) as $RL)

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        @endforeach

    </tr>


    </thead>
    <tbody>


    </tbody>
</table>

</body>
</html>