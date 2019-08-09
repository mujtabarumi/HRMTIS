import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
declare var $ :any;

@Component({
  selector: 'app-edit-assigned-shift',
  templateUrl: './edit-assigned-shift.component.html',
  styleUrls: ['./edit-assigned-shift.component.css']
})
export class EditAssignedShiftComponent implements OnInit {
  employee:any;
  assignedLog:any;
  dropdownSettings = {};
  dropdownSettings2 = {};
  selectedItems = [];
  selectedItems2 = [];
  newSelectedItems2 = [];
  empId:number;
  startDate:string;
  endDate:string;
  dates:any;
  shift:any;
  shiftObj:any={
    shiftLogId:"",
    shiftId:"",
    empId:"",
    date:'',
    inTime:"",
    outTime:"",
    deviceUserId:"",
    adjustment:"",
    adjustmentDate:"",
    leave:"",
  };
  AdjustmentCheckBox=false;
  LeaveCheckBox=false;
  modalRef:any;
  constructor(private modalService: NgbModal,private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router)
  { }

  ngOnInit() {
    this.dropdownSettings = {
      singleSelection: true,
      idField: 'empid',
      textField: 'firstName',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection:true,
    };
    this.dropdownSettings2 = {
      singleSelection: false,
      idField: 'shiftId',
      textField: 'shiftName',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection:true,
    };

    this.getAllEployee();
    this.getShift();
    this.dates=[];
  }
  toggleAdjustment(e){
  this.AdjustmentCheckBox = e.target.checked;
    if (this.AdjustmentCheckBox==false){
        this.shiftObj.adjustmentDate="";
    }
  }
  toggleLeave(e){
  this.LeaveCheckBox = e.target.checked;
  }
  getAllEployee(){

    const token=this.token.get();


    this.http.get(Constants.API_URL+'employee/getAll'+'?token='+token).subscribe(data => {
        this.employee=data;
        // console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }
  onItemSelect(value){

    this.assignedLog=[];
    // console.log(this.selectedItems);

  }
  onItemSelect2(value){

    console.log(value.shiftId);
    this.shiftObj.inTime="";
    this.shiftObj.outTime="";
    //this.selectedItems2=value;
    var index = this.selectedItems2.indexOf(value.shiftId);

    if (index > -1) {
      this.selectedItems2.splice(index, 1);

    }else {
      this.selectedItems2.push(value.shiftId);
    }
    console.log(this.selectedItems2);

  }
  onSelectAll2(value){
    this.selectedItems2=[];
    this.shiftObj.inTime="";
    this.shiftObj.outTime="";
    for(var i = 0; i < value.length; i++) {

      this.selectedItems2.push(value[i].shiftId);

    }
    console.log(this.selectedItems2);

  }
  onDeSelectAll2(value){
    this.selectedItems2=[];
    this.shiftObj.inTime="";
    this.shiftObj.outTime="";

    }
  findAttendence(){

    if(this.startDate ==null || this.endDate ==null || this.selectedItems.length==0){
      alert("Empty");
    }
    else {
      // new Date(this.employeeJoiningForm.actualJoinDate).toLocaleDateString();

      let form={
        empId:this.selectedItems[0]['empid'],
        startDate:new Date(this.startDate).toLocaleDateString(),
        endDate:new Date(this.endDate).toLocaleDateString(),

      };
      const token=this.token.get();

      this.http.post(Constants.API_URL+'dateRanges/AssignedShift'+'?token='+token,form).subscribe(data1 => {
          this.assignedLog=data1;
          console.log(data1);



        },
        error => {
          console.log(error);
        }
      );


    }


  }
  changeAssignShift(){

    if( this.shiftObj.empId ==null){
      alert("Empty");
    }
    else {

      console.log(this.shiftObj);

      let form={
        empId:this.shiftObj.empId,
        date:this.shiftObj.date,
        shiftLogId:this.shiftObj.shiftLogId,
        shiftId:this.selectedItems2,
        inTime:this.shiftObj.inTime,
        outTime:this.shiftObj.outTime,
        adjustment:this.shiftObj.adjustment,
        adjustmentDate:this.shiftObj.adjustmentDate,
        leave:this.shiftObj.leave,


      };
     console.log(form);
      const token=this.token.get();

      this.http.post(Constants.API_URL+'shift/assigned-shift-update'+'?token='+token,form).subscribe(data => {
          console.log(data);

          $.alert({
            title: data,
            content: 'Update Successfull',
          });
          this.findAttendence();
          this.selectedItems2=[];
          this.modalRef.close();




        },
        error => {
          console.log(error);
        }
      );



    }


  }
  getShift(){
    const token=this.token.get();

    this.http.get(Constants.API_URL+'shift/get'+'?token='+token).subscribe(data => {
        this.shift=data;
        // console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }

  edit(shiftlogid,date,empId,content){

    let i=0;
    for(i;i<this.assignedLog.length;i++){
      if(this.assignedLog[i].shiftLogId==shiftlogid){

        this.shiftObj.shiftLogId=shiftlogid;
        this.shiftObj.shiftId=this.assignedLog[i].shiftId;
        this.shiftObj.empId=this.selectedItems[0]['empid'];
        this.shiftObj.date=date;
        this.shiftObj.inTime=this.assignedLog[i].inTime;
        this.shiftObj.outTime=this.assignedLog[i].outTime;
        this.shiftObj.deviceUserId=this.assignedLog[i].attDeviceUserId;
        break;
      }
    }
    console.log(this.assignedLog);
    console.log(shiftlogid);
    this.modalRef =  this.modalService.open(content, { size: 'lg'});

  }
  delete(shiftlogid,date,empId){

    let i=0;
    for(i;i<this.assignedLog.length;i++){
      if(this.assignedLog[i].shiftLogId==shiftlogid){

        this.shiftObj.shiftLogId=shiftlogid;
        this.shiftObj.shiftId=this.assignedLog[i].shiftId;
        this.shiftObj.empId=this.selectedItems[0]['empid'];
        this.shiftObj.date=this.assignedLog[i].date;
        break;
      }
    }

    if (shiftlogid == null){
      $.alert({
        title: "Alert",
        content: 'There is no shit for this user on this day',
      });
    }else{

      let form={
        empId:this.selectedItems[0]['empid'],
        date:this.shiftObj.date,
        shiftLogId:this.shiftObj.shiftLogId,



      };
      const token=this.token.get();

      this.http.post(Constants.API_URL+'shift/assigned-shift-delete'+'?token='+token,form).subscribe(data => {
          console.log(data);

          $.alert({
            title: data,
            content: 'Update Successfull',
          });

          this.findAttendence();


        },
        error => {
          console.log(error);
        }
      );




    }


  }

  // openLg(content) {
  //   this.shiftObj={};
  //   this.modalRef =  this.modalService.open(content, { size: 'lg'});
  //
  // }

}
