import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
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
  selectedItems = [];
  empId:number;
  startDate:string;
  endDate:string;
  dates:any;
  shift:any;
  constructor(private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router)
  { }

  ngOnInit() {
    this.dropdownSettings = {
      singleSelection: true,
      idField: 'empid',
      textField: 'firstName',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true
    };

    this.getAllEployee();
    this.getShift();
    this.dates=[];
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

  //this.empId=value;
    // console.log(this.selectedItems);

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

      this.http.post(Constants.API_URL+'dateRanges'+'?token='+token,form).subscribe(data1 => {
          this.dates=data1;


        },
        error => {
          console.log(error);
        }
      );

      this.http.post(Constants.API_URL+'shift/assigned-shift-show'+'?token='+token,form).subscribe(data => {
          console.log(data);
          this.assignedLog=data;





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

}
