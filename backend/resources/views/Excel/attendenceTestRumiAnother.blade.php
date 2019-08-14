
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" width="10" ></th>
        <th style="text-align: center;vertical-align: middle;" width="25" >Date</th>

        @foreach($dates as $date)
            <th class="Border" colspan="5" style="text-align: center;vertical-align: middle;">{{$date['date']}}({{$date['day']}})</th>
        @endforeach

    </tr>
    <tr >

        <th style="text-align: center;vertical-align: middle;"width="10">ID</th>
        <th style="text-align: center;vertical-align: middle;"width="25">Name</th>
        @foreach($dates as $date)

            <th style="text-align: center;vertical-align: middle;background-color: #92D050"width="10">In Time</th>
            <th style="text-align: center;vertical-align: middle;background-color: #00B050"width="10">Out Time</th>

            <th style="text-align: center;vertical-align: middle;"width="10">Late Time</th>

            <th style="text-align: center;vertical-align: middle;"width="20">Total Hours Worked</th>

            <th style="text-align: center;vertical-align: middle;background-color:#757171"width="15">Attendence</th>
        @endforeach


    </tr>
    </thead>
    <tbody>
    <tr>

        <td width="10" ></td>
        <td width="25" ></td>
        <td width="10" ></td>
        <td width="10" ></td>

        <td width="10" ></td>

        <td width="20" ></td>


        <td width="15" ></td>





    </tr>


    @php

    @endphp
    @foreach($allEmp as $aE)

        <tr>


            <td class="cell" width="10">{{$aE->attDeviceUserId}}</td>
            <td class="cell" width="25">{{$aE->empFullname}}</td>
            @foreach($dates as $date)

                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())
                    <td class="cell" width="10">


                        @php
                            $in=$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime;
                            $nextIn=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime)->subHours(3)->format('H:i');
                        @endphp

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)


                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime )

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->where('accessTime','>=',$nextIn)->first())

                                {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','>=',$nextIn)->first()->accessTime)->format('H:i')}}

                            @endif

                        @else



                                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->where('accessTime','>=',$nextIn)->first())

                                    {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=',$nextIn)->first()->accessTime)->format('H:i')}}

                                @endif

                        @endif




                    </td>
                    <td class="cell" width="10">

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');

                            $out=$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime;
                           /* $nextOut=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->addHours(4)->format('H:i');*/
                            $nextOut2=\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime)->subHours(3)->format('H:i');
                        @endphp



                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)



                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                        $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->outTime )

                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                            ->where('accessTime','>=',$nextOut2)->first())

                                {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])
                                    ->where('accessTime','>=',$nextOut2)->last()->accessTime)->format('H:i')}}

                            @endif

                        @else


                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                            ->where('accessTime','>=',$nextOut2)->first())

                                {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)
                                    ->where('accessTime','>=',$nextOut2)->last()->accessTime)->format('H:i')}}

                            @endif

                        @endif


                    </td>

                    <td class="cell"  width="10">





                    </td>

                    <td class="cell" width="20">






                    </td>
                    <td class="cell"  width="15">



                    </td>
                @endif

            @endforeach



        </tr>



    @endforeach





    </tbody>
</table>

</body>
</html>