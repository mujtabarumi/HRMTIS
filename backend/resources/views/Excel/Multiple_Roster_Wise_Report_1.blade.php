
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
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Adjustment</th>
        @endforeach


    </tr>
    <tr>
        <th class="Border"style="text-align: center;vertical-align: middle;" width="25"></th>
        @foreach($RosterInfo as $RI)

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

            <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
        @endforeach

    </tr>


    </thead>
    <tbody>

    @foreach($dates as $date)

        @php
            $FINALIN=null;$FINALOUT=null;$FINALWORKINGHOUR=null;$ROUNDFINALWORKINGHOUR=null;$weekendCount=0;$adjustment=0;$holiDay=0;$next=false;
            $weekend=0;$late=0;$LateHour=0;$FINALWORKINGHOUR2=0;$offDay=0;$govHoliday=0;$leave=0;$present=0;

        @endphp

        <tr>
            <td style="text-align: left;vertical-align: middle;" width="25" class="Border">
                {{$date['date']}}({{$date['day']}})
            </td>



            @foreach($RosterInfo as $RI)
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

                                @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->inDeviceNo) as $in)

                                        @if($RI->inTime <= $in->inTime && $RI->outTime >= $in->outTime && $RI->inTime <= $in->accessTime && $RI->outTime >= $in->accessTime)


                                        @php
                                            $FINALIN=\Carbon\Carbon::parse($in->accessTime2);
                                        @endphp

                                        {{$FINALIN->format('H:i')}}

                                        @endif
                                    @php
                                    $i++;
                                    @endphp

                                @endforeach

                            @endif



                        </td>
                        <td class="Border"style="text-align: center;vertical-align: middle;" width="15">

                            @php
                                $ii=0;
                                $len=count($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo));
                            @endphp

                            @foreach($results->where('attendanceDate',$date['date'])->where('employeeId',$allE->id)->where('fkAttDevice',$allE->outDeviceNo) as $out)

                                @if($RI->inTime <= $out->inTime && $RI->outTime >= $out->outTime && $RI->inTime <= $out->accessTime && $RI->outTime >= $out->accessTime)



                                    @php
                                        $FINALOUT=\Carbon\Carbon::parse($out->accessTime2);
                                    @endphp

                                    {{$FINALOUT->format('H:i')}}


                                @endif
                                @php
                                    $ii++;
                                @endphp

                            @endforeach

                        </td>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="20">

                        </th>
                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

                        <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

                    @endif


                @else

                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="20"></th>
                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15"></th>

                    <th class="Border"style="text-align: center;vertical-align: middle;" width="15">Future Date</th>

                @endif

            @endforeach
        </tr>

    @endforeach




    </tbody>
</table>

</body>
</html>