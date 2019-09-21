
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <th style="text-align: center;vertical-align: middle;" colspan="5">Final Report 1</th>
    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" colspan="3">Name: {{$allE->empFullname}}</th>
        <th style="text-align: center;vertical-align: middle;" colspan="2">Name: {{$allE->attDeviceUserId}}</th>
    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" width="25">Date</th>
        <th style="text-align: center;vertical-align: middle;" width="15">IN Time</th>
        <th style="text-align: center;vertical-align: middle;" width="15">OUT Time</th>
        <th style="text-align: center;vertical-align: middle;" width="15">Late</th>
        <th style="text-align: center;vertical-align: middle;" width="15">Working Hour</th>

    </tr>
    <tr>
        <th style="text-align: center;vertical-align: middle;" width="25"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>
        <th style="text-align: center;vertical-align: middle;" width="15"></th>

    </tr>


    </thead>
    <tbody>

    @foreach($dates as $date)
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
                        @endif
                </td>
                <td style="text-align: center;vertical-align: middle;" width="15"></td>
                <td style="text-align: center;vertical-align: middle;" width="15"></td>

             @endif

        </tr>
    @endforeach


    </tbody>
</table>

</body>
</html>