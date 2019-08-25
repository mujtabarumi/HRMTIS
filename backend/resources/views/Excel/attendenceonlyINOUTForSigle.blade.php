
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{url('public/css/exceltable.css')}}" rel="stylesheet">


<body>

<table class="blueTable">
    <thead>
    <tr>
        <td></td>


    </tr>

    <tr >

            <th style="text-align: center;vertical-align: middle;background-color: #92D050"width="15">Punch Time</th>





    </tr>
    </thead>
    <tbody>
    <tr>


        <td width="15" ></td>



    </tr>


    @php
        $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;
    @endphp
    @foreach($results->where('attendanceDate',$ad['date']) as $res)

        <tr>
            <td>


                    @php
                        $FINALIN=\Carbon\Carbon::parse($res->accessTime2);
                    @endphp

                    {{$FINALIN->format('H:i')}}


            </td>
        </tr>

        @php
            $T_roundworkinghour=null;$T_weekendcount=0;$T_adjustment=0;$finalholiDay=0;
        @endphp



    @endforeach



    </tbody>
</table>

</body>
</html>