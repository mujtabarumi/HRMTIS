
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
        <th style="text-align: center;vertical-align: middle;" width="25" >Date</th>

        @foreach($dates as $date)
        <th class="Border" colspan="6" style="text-align: center;vertical-align: middle;">{{$date['date']}}({{$date['day']}})</th>
        @endforeach

    </tr>
    <tr >

        <th style="text-align: center;vertical-align: middle;"width="25">Name</th>
        @foreach($dates as $date)

        <th style="text-align: center;vertical-align: middle;background-color: #92D050"width="10">In Time</th>
        <th style="text-align: center;vertical-align: middle;background-color: #00B050"width="10">Out Time</th>
        <th style="text-align: center;vertical-align: middle;"width="5">Late</th>
        <th style="text-align: center;vertical-align: middle;"width="10">Late Time</th>

        <th style="text-align: center;vertical-align: middle;"width="20">Total Hours Worked</th>

        <th style="text-align: center;vertical-align: middle;background-color:#757171"width="15">Attendence</th>
        @endforeach


    </tr>
    </thead>
    <tbody>
    <tr>

        <td width="25" ></td>
        <td width="10" ></td>
        <td width="10" ></td>
        <td width="5" ></td>
        <td width="10" ></td>

        <td width="20" ></td>


        <td width="15" ></td>





    </tr>


    @php
        $late=0;$finalLate=0;$offDay=0;$finalOffDay=0;$holiDay=0;$finalholiDay=0;$tAb=0;$finaltAb=0;
        $checkOUTCAL=0;
    @endphp
    @foreach($allEmp as $aE)

        <tr>


            <td class="cell" width="25">{{$aE->empFullname}}</td>
            @foreach($dates as $date)
                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())
            <td class="cell" width="10">

                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )

                @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )
                    {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkIn}}
                @else
                    {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkIn}}
                @endif


            </td>
            <td class="cell" width="10">
                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )
                    @php
                        $checkOUTCAL=$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkIn;
                    @endphp
                @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )

                        {{$checkOUTCAL}}
                    @php
                        $checkOUTCAL=0;
                    @endphp
                @else
                    @if($checkOUTCAL ==0)
                            {{ $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut}}
                    @else
                            {{$checkOUTCAL}}
                    @endif
                @endif

             </td>
                <td class="cell" width="5">
                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )

                    @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )
                        {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->late}}
                    @else
                        {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->late}}
                    @endif


                </td>
                <td class="cell" width="10">

                    @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )

                    @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )
                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->late =='Y')

                            {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->lateTime}}
                        @endif
                    @else
                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->late =='Y')

                            {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->lateTime}}
                        @endif
                    @endif


                </td>

                <td class="cell" width="20">

                </td>
            <td class="cell" style="background-color: #92D050" width="15">
                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )

                        @php
                            $allWeekend=explode(',',strtolower($aE->weekend));
                        @endphp
                        @if(in_array(strtolower($date['day']), $allWeekend))


                                WeekEnd

                        @else


                                Absent

                        @endif


                @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'nextDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )
                    Present
                @else
                    Present
                @endif

            </td>

             @else

                    <td class="cell" width="10"></td>
                    <td class="cell" width="10"></td>
                    <td class="cell" width="5"></td>
                    <td class="cell" width="10"></td>

                    <td class="cell" width="20"></td>


                        @php
                        $allWeekend=explode(',',strtolower($aE->weekend));
                        @endphp
                        @if(in_array(strtolower($date['day']), $allWeekend))
                        <td class="cell" style="color: #ffffff;background-color: #f7aec2" width="15">

                            WeekEnd
                        </td>
                        @else
                            <td class="cell" style="color: #ffffff;background-color: red" width="15">
                                @php
                                    $tAb++;$finaltAb=($tAb+$finaltAb);

                                @endphp
                                Absent
                            </td>
                        @endif

                @endif
            @endforeach



        </tr>



    @endforeach





    </tbody>
</table>

</body>
</html>