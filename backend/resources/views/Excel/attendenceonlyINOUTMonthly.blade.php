
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
        <th style="text-align: center;vertical-align: middle;" width="25" ></th>

        @php
           $s=\Carbon\Carbon::parse($startDate);
            $e=\Carbon\Carbon::parse($endDate);
        if ($s->diffInDays($e) >=7){
        }
        @endphp


    </tr>




    </tbody>
</table>

</body>
</html>