
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th style="vertical-align: middle;text-align: center;" colspan="7">Final Report 2</th>
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th style="text-align: center;vertical-align: middle;" colspan="4">Name: {{$allE->empFullname}}</th>
        <th style="text-align: center;vertical-align: middle;" colspan="3">ID: {{$allE->attDeviceUserId}}</th>
    </tr>
    <tr>
        <td style="vertical-align: middle;text-align: center;"></td>
        <th style="text-align: center;vertical-align: middle;" colspan="4">Department: {{$allE->departmentName}}</th>
        <th style="text-align: center;vertical-align: middle;" colspan="3">Designation: </th>
    </tr>

    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Date</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>


    </tr>


    </thead>
    <tbody>
    @foreach($dates as $date)
        <tr>

            <td class="Border"style="text-align: center;vertical-align: middle;" width="25">
                {{$date['date']}}({{$date['day']}})
            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo) as $O)
                    @php
                        $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                    @endphp

                    {{$FINALIN->format('H:i')}}
                    <br>

                @endforeach

            </td>
            <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo) as $O)
                    @php
                        $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                    @endphp

                    {{$FINALIN->format('H:i')}}
                    <br>

                @endforeach

            </td>

        </tr>
    @endforeach





    </tbody>
</table>
</body>
</html>