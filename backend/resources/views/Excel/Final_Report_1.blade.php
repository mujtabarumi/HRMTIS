
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <th style="vertical-align: middle;text-align: center;" colspan="7">Final Report 1</th>
    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" colspan="4">Name: {{$allE->empFullname}}</th>
        <th style="text-align: center;vertical-align: middle;" colspan="3">Name: {{$allE->attDeviceUserId}}</th>
    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" width="25">Date</th>
        <th style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
        <th style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
        <th style="text-align: center;vertical-align: middle;" width="15">Late</th>
        <th style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>
        <th style="text-align: center;vertical-align: middle;" width="25">Round Working Hour</th>
        <th style="text-align: center;vertical-align: middle;" width="15">Adjustment</th>
        <th style="text-align: center;vertical-align: middle;" width="15">Attendance</th>

    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" width="25"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="25"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>

    </tr>


    </thead>
    <tbody>

    @php
        $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;$T_weekend=0;
    @endphp

    @foreach($dates as $date)

        @php
            $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;
            $weekend=0;

        @endphp

        <tr >

            <td style="text-align: center;vertical-align: middle;" width="25" class="Border">
                {{$date['date']}}({{$date['day']}})
            </td>
            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first())
                @php
                    $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                    $previousday=\Carbon\Carbon::parse($date['date'])->subDays(1)->format('Y-m-d');

                @endphp

                <td style="text-align: center;vertical-align: middle;" width="15">

                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)

                        {{
                            \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                            ->first()->accessTime2)->format('H:i')
                        }}

                    @elseif(
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime !=null &&
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime
                           )

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime =='00:00:00')

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                            ->where('accessTime','>=','21:00:00')->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                            @php
                                                $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                    ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime2);
                                            @endphp

                                            {{$FINALIN->format('H:i')}}

                                @endif

                            @else

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime2);
                                    @endphp

                                    {{$FINALIN->format('H:i')}}

                                @endif

                            @endif

                    @else

                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->where('fkAttDevice',$allE->inDeviceNo)
                                ->where('accessTime','>=','19:00:00')->first())

                            @php
                                $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->where('fkAttDevice',$allE->inDeviceNo)
                                    ->where('accessTime','>=','19:00:00')->first()->accessTime2);
                            @endphp

                            {{$FINALIN->format('H:i')}}

                        @endif

                    @endif

                </td>
                <td style="text-align: center;vertical-align: middle;" width="15">

                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime == null)

                        {{
                            \Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->last()->accessTime2)->format('H:i')
                        }}
                    @elseif(
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime != null  &&
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime !=null &&
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                                    $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime
                    )

                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime =='00:00:00')

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                            ->where('accessTime','<=','18:00:00')->where('fkAttDevice',$allE->outDeviceNo)->first())

                                @php
                                    $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','<=','18:00:00')->where('fkAttDevice',$allE->outDeviceNo)->last()->accessTime2);
                                @endphp

                                {{$FINALOUT->format('H:i')}}


                            @endif

                        @else

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime < '11:00:00')



                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                    ->first())

                                    @php
                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                            ->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}
                                @endif

                            @else

                                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$allE->outDeviceNo)
                                    ->first())

                                        @php
                                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                            ->where('accessTime','<=','04:00:00')->where('fkAttDevice',$allE->outDeviceNo)
                                                ->last()->accessTime2);
                                        @endphp

                                        {{$FINALOUT->format('H:i')}}

                                @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                    ->first())

                                    @php
                                        $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','<=','23:59:59')->where('fkAttDevice',$allE->outDeviceNo)
                                            ->last()->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}

                                @endif


                            @endif

                        @endif
                    @else
                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                    ->where('accessTime','<=','13:00:00')->where('fkAttDevice',$allE->outDeviceNo)->first())

                                @php
                                    $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                        ->where('accessTime','<=','13:00:00')->where('fkAttDevice',$allE->outDeviceNo)->last()->accessTime2);
                                @endphp

                                {{$FINALOUT->format('H:i')}}


                            @endif
                    @endif
                </td>
                <td style="text-align: center;vertical-align: middle;" width="15">


                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)


                    @elseif(
                        $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime != null &&
                       $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime <
                       $results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->outTime
                   )

                        @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime=='00:00:00')

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$previousday)
                                        ->where('accessTime','>=','20:00:00')->where('accessTime','<=','23:59:59')
                                        ->where('fkAttDevice',$allE->inDeviceNo)->first()
                               )

                            @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->where('accessTime','>=','00:00:00')->where('accessTime','<=','3:59:59')
                                        ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('accessTime','>=','00:00:00')->where('accessTime','<=','3:59:59')
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime);
                                         $ins=\Carbon\Carbon::createFromFormat('H:i:s','00:00:00');

                                    @endphp

                                    @if($access >'00:00:00' && $access < '3:59:59')

                                        @if($access->diffInMinutes($ins) >= 21 )

                                            {{$access->diff($ins)->format('%H:%i')}}

                                        @endif

                                    @endif
                            @endif

                        @else

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                    ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                @php
                                    $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                        ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime);
                                    $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->first()->inTime)
                                @endphp

                                @if($access > $ins)

                                    @if($access->diffInMinutes($ins) >= 21 )

                                        {{$access->diff($ins)->format('%H:%i')}}

                                    @endif
                                @endif




                            @endif




                        @endif

                   @else

                            @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                ->where('fkAttDevice',$allE->inDeviceNo)->first())

                                    @php
                                        $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                            ->where('fkAttDevice',$allE->inDeviceNo)->first()->accessTime);
                                        $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                                                ->first()->inTime)
                                    @endphp

                                    @if($access->diffInMinutes($ins) >= 21 )

                                        {{$access->diff($ins)->format('%H:%i')}}

                                    @endif



                            @elseif($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)->first())

                                @php
                                    $access=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                        ->first()->accessTime);
                                    $ins=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$nextday)
                                            ->first()->inTime)
                                @endphp

                                @if($access->diffInMinutes($ins) >= 21 )

                                    {{$access->diff($ins)->format('%H:%i')}}

                                @endif




                            @endif


                   @endif



                </td>

                <td style="text-align: center;vertical-align: middle;" width="15">

                    @if($FINALIN != null && $FINALOUT != null)

                        @php
                            $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);

                        @endphp

                        {{$FINALWORKINGHOUR->format('%H:%i')}}

                    @endif

                </td>

                <td style="text-align: center;vertical-align: middle;" width="25">

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
                <td style="text-align: center;vertical-align: middle;" width="15">

                    @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate != null)
                        @php

                            $adjustment++;
                            $T_adjustment=($adjustment+$T_adjustment);
                        @endphp
                        {{$results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->adjustmentDate}}
                    @endif



                </td>

                @if($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])->first()->inTime == null)
                    <td class="cell" style="color: firebrick"  width="15">
                        roster not found
                        <br>

                        @php

                            $FINALIN=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                            ->first()->accessTime2);
                            $FINALOUT=\Carbon\Carbon::parse($results->where('employeeId',$allE->id)->where('attendanceDate',$date['date'])
                            ->last()->accessTime2);
                        @endphp

                        First: {{$FINALIN->format('H:i')}}<br>
                        Last: {{$FINALOUT->format('H:i')}}<br>
                        @if($FINALIN != null && $FINALOUT != null)

                            @php
                                $FINALWORKINGHOUR=$FINALOUT->diff($FINALIN);

                            @endphp

                            WorkingHour: {{$FINALWORKINGHOUR->format('%H:%i')}}

                        @endif


                    </td>
                @else

                    <td class="cell" style="color: firebrick" width="15">
                        P
                    </td>


                @endif

             @endif

        </tr>
    @endforeach


    </tbody>
</table>

</body>
</html>