
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

        @foreach($RosterInfo as $RI)
            <th class="Border" colspan="5" style="text-align: center;vertical-align: middle;" >{{$RI->inTime}}-{{$RI->outTime}}</th>
        @endforeach



    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($RosterInfo as $RI)

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20">Late Day / Hours</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Round Working Hour</th>


        @endforeach
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Total Hour</th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25">Attendance</th>

    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($RosterInfo as $RI)

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>



        @endforeach
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
    </tr>


    </thead>
    <tbody>




    @foreach($dates as $date)

        @php
            $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;$T_weekend=0;$T_late=0;$T_LateHour=0;$T_FinalWorkHour=0;
        $T_offDay=0;$T_govHoliday=0;$T_leave=0;$T_present=0;$C_RL=0;
        @endphp



        <tr>
            <td style="text-align: left;vertical-align: middle;" width="25" class="Border">
                {{$date['date']}}({{$date['day']}})
            </td>




            @foreach($RosterInfo as $RI)
                @php
                    $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;
                    $weekend=0;$late=0;$LateHour=0;$FINALWORKINGHOUR2=0;$offDay=0;$govHoliday=0;$leave=0;$present=0;$access=null;$ins=null;
                    $C_RL++;

                @endphp

                @if($date['date'] <= \Carbon\Carbon::now()->format('Y-m-d'))

                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first())

                        @php
                            $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                            $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');


                        @endphp
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)

                                {{
                                    \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->first()->accessTime2)->format('H:i')
                                }}

                            @else

                                @php
                                    $i=0;
                                @endphp

                                @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                    ->where('accessTime','>=' ,$RI->inTime)->where('accessTime','<=', $RI->outTime) as $in)

                                    @if($i==0)

                                        @php
                                            $FINALIN=\Carbon\Carbon::parse($in->accessTime2);
                                        @endphp

                                        {{$FINALIN->format('H:i')}}

                                    @endif

                                    @php
                                        $i++;
                                    @endphp

                                @endforeach

                                @php
                                    $i=0;
                                @endphp

                            @endif



                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                            @php
                                $ii=0;
                                $len=count($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)
                                ->where('fkAttDevice',$allE->outDeviceNo)->where('accessTime','>=' ,$RI->inTime)->where('accessTime','<=', $RI->outTime));
                            @endphp

                            @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo)
                             ->where('accessTime','>=' ,$RI->inTime)->where('accessTime','<=', $RI->outTime) as $out)

                                @if($ii==($len-1))

                                    @php
                                        $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}




                                @endif

                                @php
                                    $ii++;
                                @endphp





                            @endforeach


                            @php
                                $ii=0;
                            @endphp

                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="20">

                            @php
                                $i=0;
                            @endphp

                            @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo)
                                ->where('accessTime','>=' ,$RI->inTime)->where('accessTime','<=', $RI->outTime) as $in)

                                @if($i==0)

                                    @php
                                        $access=\Carbon\Carbon::parse($in->accessTime);
                                        $ins=\Carbon\Carbon::parse($in->inTime);
                                    @endphp


                                @endif

                                @php
                                    $i++;
                                @endphp

                            @endforeach

                            @php
                                $i=0;
                            @endphp

                            @if($access !=null && $ins!=null && $access > $ins)

                                @if($access->diffInMinutes($ins) >= 21 )


                                    {{$access->diff($ins)->format('%H:%i')}}

                                @endif
                            @endif


                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                            @if($FINALIN != null && $FINALOUT != null)

                                @php
                                    $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);
                                    $FINALWORKINGHOUR2=$FINALOUT->diffInMinutes($FINALIN);
                                    $T_FinalWorkHour=($FINALWORKINGHOUR2+$T_FinalWorkHour);

                                @endphp

                                {{$FINALWORKINGHOUR->format('%H:%i')}}

                            @endif





                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="25">

                            @if($FINALWORKINGHOUR != null)
                                @php
                                    $ROUNDFINALWORKINGHOUR=\Carbon\Carbon::createFromTime($FINALWORKINGHOUR->format('%H'),$FINALWORKINGHOUR->format('%i'),0);
                                @endphp

                                @if($ROUNDFINALWORKINGHOUR->minute >=25)

                                    @php
                                        $ROUNDFINALWORKINGHOUR->minute(0);
                                        $ROUNDFINALWORKINGHOUR->addHour();
                                        $T_roundworkinghour=($T_roundworkinghour+$ROUNDFINALWORKINGHOUR->hour);
                                    @endphp

                                @else

                                    @php
                                        $ROUNDFINALWORKINGHOUR->minute(0);
                                        $T_roundworkinghour=($T_roundworkinghour+$ROUNDFINALWORKINGHOUR->hour);

                                    @endphp

                                @endif

                                {{$ROUNDFINALWORKINGHOUR->format('H:i')}}

                            @endif

                        </td>



                    @else

                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>


                    @endif


                @else

                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>




                @endif

                @php
                    $late=0;$LateHour=0;
                   $ROUNDFINALWORKINGHOUR=null;$adjustment=0;
                   $FINALWORKINGHOUR2=0;

                   $offDay=0;$govHoliday=0;
                   $leave=0;
                   $present=0;$access=null;$ins=null;




                @endphp




            @endforeach

            <th class="Border"style="text-align: center;vertical-align: middle;" width="25">
                {{$T_roundworkinghour}}
            </th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">

                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first())

                    p


                @else

                    @if($allLeave->where('fkEmployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())


                            {{$allLeave->where('fkEmployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first()->categoryName}}



                    @elseif($allWeekend->where('fkemployeeId',$allE->id)->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())



                            Day Off




                    @elseif($govtHoliday->where('startDate','<=',$date['date'])->where('endDate','>=',$date['date'])->first())



                            Govt Holiday

                    @else
                        Absent
                    @endif



                @endif

            </th>
        </tr>



    @endforeach




    </tbody>
</table>

</body>
</html>