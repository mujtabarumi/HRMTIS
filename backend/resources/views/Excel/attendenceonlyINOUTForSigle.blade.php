
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td></td>


    </tr>

    <tr>


        @foreach($allEmp as $aE)
            <th class="Border" width="30"style="text-align: center;vertical-align: middle;">{{$aE->empFullname}}({{$aE->attDeviceUserId}})</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    <tr>

        @foreach($allEmp as $aE)
            <th class="Border" width="15"style="text-align: center;vertical-align: middle;">In Time</th>
        @endforeach


    </tr>





        <tr>
            @foreach($allEmp as $aE)
                    <td>


                        @foreach($results->where('attendanceDate',$ad['date'])->where('employeeId',$aE->id) as $O)
                            @php
                                $FINALIN=\Carbon\Carbon::parse($O->accessTime2);
                            @endphp

                                {{$FINALIN->format('H:i')}}
                            <br>

                        @endforeach


                    </td>
                @endforeach
        </tr>









    </tbody>
</table>

</body>
</html>