import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
import {NgxSpinnerService} from "ngx-spinner";
import {st} from "@angular/core/src/render3";


declare var $ :any;

@Component({
  selector: 'app-attendance',
  templateUrl: './attendance.component.html',
  styleUrls: ['./attendance.component.css']
})
export class AttendanceComponent implements OnInit {
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  employee:any;
  dtOptions:DataTables.Settings={};
  dtTrigger:Subject<any>=new Subject();
  id:any;
  allEmp=[];
  shiftId:number;
  shift:any;
  dtInstance:DataTables.Api;
  startDate:string;
  endDate:string;
  noOfDays:string;
  remark:string;
  fkLeaveCategory:string;
  leaveCategories:any;
  dropdownSettings = {};
  selectedItems = [];
  attendanceData:any;
  attendanceDate:any;
  test:any;
  search:boolean;


  constructor(private renderer: Renderer,public http: HttpClient, private token:TokenService ,
              public route:ActivatedRoute, private router: Router,private spinner: NgxSpinnerService) { }


  ngOnInit() {
    this.search=false;

    this.getAllEployee();

   // this.getData();
    // console.log(new Date.today().clearTime().moveToFirstDayOfMonth());
    let nowdate = new Date();
    let monthStartDay=this.dateToYMD(new Date(nowdate.getFullYear(),nowdate.getMonth(),1));
    let monthEndDay=this.dateToYMD(new Date(nowdate.getFullYear(),nowdate.getMonth()+1,0));
    $('#startDate').val(monthStartDay);
    $('#endDate').val(monthEndDay);
    // console.log(monthEndDay);
    // console.log(monthStartDay);

    this.dropdownSettings = {
      singleSelection: false,
      idField:'empid',
      textField:'attDeviceUserId',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection:true,
    };




  }

  getAllEployee(){

    const token=this.token.get();


    this.http.get(Constants.API_URL+'employee/getAll'+'?token='+token).subscribe(data => {
        this.employee=data;
        console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }

  onItemSelect(value){

    // console.log(this.selectedItems);

  }
  onItemDeSelect(value){

  }



  dateToYMD(date) {
    let strArray=['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    let d = date.getDate();
    let m = strArray[date.getMonth()];
    let y = date.getFullYear();
    // return '' + (d <= 9 ? '0' + d : d) + '-' + m + '-' + y;
    return '' + y + '-' + m + '-' + (d <= 9 ? '0' + d : d);
  }

  dateToYMD2(date) {
    let strArray=['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    let d = date.getDate();
    let m = strArray[date.getMonth()];
    let y = date.getFullYear();
    return '' + (d <= 9 ? '0' + d : d) + '-' + m + '-' + y;
  }


  getData(){

    if (this.selectedItems.length ==0 || this.selectedItems.length>1){

        $.alert({
          title: 'Alert',
          content: 'Please select 1 employee only',
        });

    }
    else if(this.selectedItems.length ==1)
    {

      const token=this.token.get();

      let id=this.selectedItems[0]['empid'];

      this.http.post(Constants.API_URL+'report/getEmployeeAttendance'+'?token='+token,{id:id,startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

          this.spinner.hide();
          console.log(data);
          this.attendanceData=data['result'];
          this.attendanceDate=data['date'];
          this.search=true;




        },
        error => {
          console.log(error);
          this.spinner.hide();
        }
      );



      // this.dtOptions = {
      //   ajax: {
      //     url: Constants.API_URL+'report/getEmployeeAttendance'+'?token='+token,
      //     type: 'POST',
      //     data:function (d:any){
      //       d.id=id;
      //       d.startDate=$('#startDate').val();
      //       d.endDate=$('#endDate').val();
      //
      //     },
      //   },
      //   columns: [
      //
      //     { data: 'attDeviceUserId' ,name:'attDeviceUserId'},
      //     { data: 'firstName' ,name:'firstName'},
      //     { data: 'attendanceDate' ,name:'attendanceDate'},
      //     { data: 'checkInFull' , name: 'checkInFull' },
      //     { data: 'checkoutFull', name: 'checkoutFull'},
      //     { data: 'late', name: 'late'},
      //     { data: 'lateTime', name: 'lateTime'},
      //     { data: 'scheduleIn', name: 'scheduleIn'},
      //     { data: 'scheduleOut', name: 'scheduleOut'},
      //     { data: 'workingTime', name: 'workingTime'},
      //
      //
      //   ],
      //   processing: true,
      //   serverSide: true,
      //   pagingType: 'full_numbers',
      //   pageLength: 10
      // };

    }


  }


  generateDetailsExcel(){
    this.spinner.show();
    const token=this.token.get();

    this.http.post(Constants.API_URL+'report/attendanceHR'+'?token='+token,{startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

        this.spinner.hide();
        console.log(data);


        let fileName=Constants.Image_URL+'exportedExcel/'+data;

        let link = document.createElement("a");
        link.download = data+".xls";
        let uri = fileName+".xls";
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);


      },
      error => {
        console.log(error);
        this.spinner.hide();
      }
    );

  }
  generateMonthlyINOUTExcel(){

    this.spinner.show();
    const token=this.token.get();

    this.http.post(Constants.API_URL+'report/attendanceHRINOUTmonthly'+'?token='+token,{report:'monthly',startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

        this.spinner.hide();
        console.log(data);


        let fileName=Constants.Image_URL+'exportedExcel/'+data;

        let link = document.createElement("a");
        link.download = data+".xls";
        let uri = fileName+".xls";
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);


      },
      error => {
        console.log(error);
        this.spinner.hide();
      }
    );

  }
  generateINOUTExcel(){


    if (this.selectedItems.length>0){



      if($('#excelType').val()==""){

        $.alert({
          title: 'Alert',
          content: 'Please select Excel Type',
        });

      }else {

        let empList=[];
        for (let $i=0;$i<this.selectedItems.length;$i++){
          empList.push(this.selectedItems[$i]['empid']);
        }
        // console.log(empList);

        this.spinner.show();
        const token=this.token.get();

        if($('#excelType').val()=="1"){



          this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{startDate:$('#startDate').val(),endDate:$('#endDate').val(),empId:empList,report:'dailyINOUT'}).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              let fileName=Constants.Image_URL+'exportedExcel/'+data;

              let link = document.createElement("a");
              link.download = data+".xls";
              let uri = fileName+".xls";
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $("#excelType").val("");
              this.selectedItems=[];


            },
            error => {
              console.log(error);
              this.spinner.hide();
            }
          );

        }else if ($('#excelType').val()=="2"){



          this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{startDate:$('#startDate').val(),endDate:$('#endDate').val(),empId:empList}).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              let fileName=Constants.Image_URL+'exportedExcel/'+data;

              let link = document.createElement("a");
              link.download = data+".xls";
              let uri = fileName+".xls";
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $("#excelType").val("");
              this.selectedItems=[];


            },
            error => {
              console.log(error);
              this.spinner.hide();
            }
          );


        }else if($('#excelType').val()=="3"){

          this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{startDate:$('#startDate').val(),endDate:$('#endDate').val(),empId:empList,report:'monthlyINOUT'}).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              let fileName=Constants.Image_URL+'exportedExcel/'+data;

              let link = document.createElement("a");
              link.download = data+".xls";
              let uri = fileName+".xls";
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $("#excelType").val("");
              this.selectedItems=[];


            },
            error => {
              console.log(error);
              this.spinner.hide();
            }
          );

        }


      }

    }else {

      if($('#excelType').val()==""){

        $.alert({
          title: 'Alert',
          content: 'Please select Excel Type',
        });

      }
      else if($('#excelType').val()=="1"){

        this.spinner.show();
        const token=this.token.get();

        this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{report:'dailyINOUT',startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

            this.spinner.hide();
            console.log(data);


            let fileName=Constants.Image_URL+'exportedExcel/'+data;

            let link = document.createElement("a");
            link.download = data+".xls";
            let uri = fileName+".xls";
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            $("#excelType").val("");
            this.selectedItems=[];

          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );

      }
      else if ($('#excelType').val()=="2"){

        this.spinner.show();
        const token=this.token.get();

        this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{report:'dailyINOUT',startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

            this.spinner.hide();
            console.log(data);


            let fileName=Constants.Image_URL+'exportedExcel/'+data;

            let link = document.createElement("a");
            link.download = data+".xls";
            let uri = fileName+".xls";
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            $("#excelType").val("");
            this.selectedItems=[];

          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );


      }
      else if($('#excelType').val()=="3"){

        this.spinner.show();
        const token=this.token.get();

        this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{report:'monthlyINOUT',startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

            this.spinner.hide();
            console.log(data);


            let fileName=Constants.Image_URL+'exportedExcel/'+data;

            let link = document.createElement("a");
            link.download = data+".xls";
            let uri = fileName+".xls";
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            $("#excelType").val("");
            this.selectedItems=[];

          },
          error => {
            console.log(error);
            this.spinner.hide();
          }
        );


      }



    }


  }
  total(){

  }

  // rerender(){
  //   this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
  //
  //     dtInstance.destroy();
  //
  //     this.dtTrigger.next();
  //   });
  // }

  searchAttendance(){

    this.getData();

  }


}
