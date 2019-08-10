
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
        $late=0;$finalLate=0;$offDay=0;$finalOffDay=0;$holiDay=0;$finalholiDay=0;$tAb=0;$finaltAb=0;
        $checkOUTCAL=0;
    @endphp
    @foreach($allEmp as $aE)

        <tr>


            <td class="cell" width="10">{{$aE->attDeviceUserId}}</td>
            <td class="cell" width="25">{{$aE->empFullname}}</td>
            @foreach($dates as $date)
                @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first())

                    <td class="cell" width="10">

                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'previousDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )

                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'previousDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )

                            {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkIn)->format('H:i')}}

                        @else
                            {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkIn)->format('H:i')}}
                        @endif


                    </td>
                    <td class="cell" width="10">
                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'previousDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime == null )



                        @elseif($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut == 'previousDay' && $results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->inTime != null )

                            @php
                                $nextday=\Carbon\Carbon::parse($date['date'])->addDays(1)->format('Y-m-d');
                                $previousday=\Carbon\Carbon::parse($date['date'])->subDay(1);
                            @endphp
                        @if($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)->first())

                                {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$nextday)->first()->checkIn)->format('H:i')}}


                        @else

                        @endif
                        @else

                            {{\Carbon\Carbon::parse($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->checkOut)->format('H:i')}}


                        @endif

                     </td>

                        <td class="cell <?php if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->late =='Y'){?> late <?php }?>"  width="10">


                            @if($results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->late== 'Y' )

                                {{$results->where('employeeId',$aE->id)->where('attendanceDate',$date['date'])->first()->lateTime}}

                            @endif


                        </td>

                        <td class="cell" width="20">






                        </td>
                    <td class="cell"  width="15">

                        P

                    </td>

             @else

                    <td class="cell" width="10"></td>
                    <td class="cell" width="10"></td>

                    <td class="cell" width="10"></td>

                    <td class="cell" width="20"></td>



                        <td class="cell" style="color:#e81809" width="15">

                            A

                        </td>


                @endif
            @endforeach



        </tr>



    @endforeach





    </tbody>
</table>

</body>
</html>