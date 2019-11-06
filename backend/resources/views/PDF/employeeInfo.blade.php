

<html lang="en">
<head>
    <title>Employee information of {{$employee->empFullname}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        input {
            border: medium none;
            padding: 0;
        }
        .label{
            background-color: #eff0f1;
        }
        @page {
            margin-bottom:5px;margin-top: 15px;
        }
    </style>

</head>
<body style="margin-bottom:5px;">
<div style="margin-bottom:0px;" class="">
    <div style="background: #fff;margin-bottom:0px; " class="">

        <table border="0" style="width:100%; margin-top: 10px; border: none;">
            <tr>
                <td style="border: none;text-align: center"><h2 style="font-size: 24px; border: none; text-align: center"><span style="border-bottom: 1px solid #000">Employee information</span> </h2></td>
            </tr>

        </table>
        <table border="0" style="width:100%; border: none;">
            <tr>
                <td style="text-align: left; border: none;width: 85%; ">
                    <h3 style="">{{$employee->empFullname}}</h3>
                    <p style="max-width: 300px">
                        ID No:{{$employee->empId}}
                        Cell No: {{$employee->contactNo}} <br>
                        Email: {{$employee->email}} <br>
                        Department: {{$employee->departmentName}}
                        Designation: {{$employee->designationTitle}}
                    </p>

                </td>
                <td style="width: 15%; border: none; "><img height="150px" width="150px" src="{{url('public/images').'/'.$employee->photo}}" alt="employee Image"></td>
            </tr>

        </table>
    </div>
</div>





</body>
</html>
