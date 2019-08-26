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


  constructor(private renderer: Renderer,public http: HttpClient, private token:TokenService ,
              public route:ActivatedRoute, private router: Router,private spinner: NgxSpinnerService) { }


  ngOnInit() {

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


  // getData(){
  //   const token=this.token.get();
  //
  //
  //
  //   this.dtOptions = {
  //     ajax: {
  //       url: Constants.API_URL+'report/attendance'+'?token='+token,
  //       type: 'POST',
  //       data:function (d:any){
  //         d.startDate=$('#startDate').val();
  //         d.endDate=$('#endDate').val();
  //
  //       },
  //
  //
  //     },
  //
  //
  //
  //     // ajax: (form,myfunc) => {
  //     //     this.http.post(Constants.API_URL+'report/attendance'+'?token='+token,form).subscribe(resp => {
  //     //          // console.log(resp);
  //     //
  //     //         // myfunc({
  //     //         //          recordsTotal: resp.recordsTotal,
  //     //         //          recordsFiltered: resp.recordsFiltered,
  //     //         //          data: [],
  //     //         //     });
  //     //         });
  //     // },
  //
  //     columns: [
  //
  //       { data: 'empname' ,name:'empname'},
  //       // { data: 'middleName' ,name:'middleName'},
  //       // { data: 'lastName' ,name:'lastName'},
  //       { data: 'departmentName' ,name:'departmentName'},
  //       { data: 'totAttendance' , name: 'totAttendance' },
  //       { data: 'totalLate', name: 'totalLate'},
  //       { data: 'averageWorkingHour', name: 'averageWorkingHour'},
  //       { data: 'totalLeave', name: 'totalLeave'},
  //
  //       // {
  //       //
  //       //     "data": function (data: any, type: any, full: any) {
  //       //         return '<button class="btn btn-sm btn-info" data-leaveemp-id="'+data.employeeId+'">'+data.totalLeave+'</button>';
  //       //     },
  //       //     "orderable": false, "searchable":false, "name":"selected_rows"
  //       // },
  //
  //       { data: 'weekends', name: 'weekends'},
  //       { data: 'totalWeekend', name: 'totalWeekend'},
  //
  //       {
  //
  //         "data": function (data: any, type: any, full: any) {
  //           return '<button class="btn btn-sm btn-info edit-user" data-emp-id="'+data.employeeId+'">View</button>';
  //         },
  //         "orderable": false, "searchable":false, "name":"selected_rows"
  //       },
  //
  //     ],
  //     drawCallback: () => {
  //
  //       $('.edit-user').on('click', (event) => {
  //
  //         let id=event.target.getAttribute("data-emp-id");
  //         let start =$('#startDate').val();
  //         let end = $('#endDate').val();
  //
  //
  //
  //         // this.router.navigate(["report/attendance/" +id+'/'+start+'/'+end]);
  //         window.open("report/attendance/" +id+'/'+start+'/'+end, "_blank");
  //         return false;
  //       });
  //
  //
  //
  //
  //     },
  //
  //
  //     processing: true,
  //     serverSide: false,
  //     pagingType: 'full_numbers',
  //     pageLength: 10,
  //     dom: 'Bfrtip',
  //     paging: true,
  //
  //
  //
  //   };
  //
  //
  // }



  // ngAfterViewInit(): void {
  //   this.dtTrigger.next();
  //   // // this.renderer.listenGlobal('document', 'click', (event) => {
  //   //     if (event.target.hasAttribute("data-emp-id")) {
  //   //
  //   //         let id=event.target.getAttribute("data-emp-id");
  //   //        let start =$('#startDate').val();
  //   //         let end = $('#endDate').val();
  //   //
  //   //         this.router.navigate(["report/attendance/" +id+'/'+start+'/'+end]);
  //   //
  //   //     }
  //   //
  //   //     if (event.target.hasAttribute("data-leaveemp-id")) {
  //   //
  //   //         let id=event.target.getAttribute("data-leaveemp-id");
  //   //         console.log(id);
  //   //     }
  //   //
  //   // });
  //
  //   // this.dtElement.dtInstance.then(dtInstance =>{
  //   //     dtInstance.on('click', function(event){
  //   //         let row_dom = $(this).event.attr("data-emp-id");
  //   //         //let row = dtInstance.row(row_dom.employeeId).data();
  //   //             // alert(row);
  //   //         console.log(row_dom);
  //   //     })
  //   //
  //   // });
  //
  //
  // }
  // ngOnDestroy(): void {
  //   // Do not forget to unsubscribe the event
  //   this.dtTrigger.unsubscribe();
  // }
  //
  // search(){
  //   this.rerender();
  // }


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
  generateINOUTExcel(){

   // console.log(this.selectedItems);


    if (this.selectedItems.length>0){

      let empList=[];
      for (let $i=0;$i<this.selectedItems.length;$i++){
        empList.push(this.selectedItems[$i]['empid']);
      }
     // console.log(empList);

      this.spinner.show();
      const token=this.token.get();

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


        },
        error => {
          console.log(error);
          this.spinner.hide();
        }
      );

    }else {

      this.spinner.show();
      const token=this.token.get();

      this.http.post(Constants.API_URL+'report/attendanceHRINOUT'+'?token='+token,{startDate:$('#startDate').val(),endDate:$('#endDate').val()}).subscribe(data => {

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


}
