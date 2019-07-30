import {Component, OnInit, Input} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Constants} from "../../../../constants";
import {TokenService} from "../../../../services/token.service";
import {Department} from "../../../../model/department.model";
declare var $ :any;
@Component({
  selector: 'app-add-department',
  templateUrl: './add-department.component.html',
  styleUrls: ['./add-department.component.css']
})
export class AddDepartmentComponent implements OnInit {
  @Input('master') data:any;

  departments:any[];
  departmentField={} as Department;

  constructor(public http: HttpClient,private token:TokenService) { }

  ngOnInit() {
    this.getDepartments();
  }

  getDepartments(){
    const token=this.token.get();
    this.http.get(Constants.API_URL+'department/get'+'?token='+token).subscribe(data => {
        this.departments=<any[]>data;
      },
      error => {
        console.log(error);
      }
    );
  }

  editDept(dept){
    // console.log(dept);
    this.departmentField=dept;
  }

  checkId(){
    if(Object.keys(this.departmentField).length === 0){
      return true;
    }
    else {
      if(this.departmentField.id ==null){
        return true;
      }
      return false;
    }

  }
  onSubmit(){
    console.log(this.departmentField);
    const token=this.token.get();
    this.http.post(Constants.API_URL+'department/post'+'?token='+token,this.departmentField).subscribe(data => {
      //  console.log(data);
        this.getDepartments();

        // $.alert({
        //   title: 'Success!',
        //   type: 'Green',
        //   content: 'Leave Rejected',
        //   buttons: {
        //     tryAgain: {
        //       text: 'Ok',
        //       btnClass: 'btn-red',
        //       action: function () {
        //       }
        //     }
        //   }
        // });

      },
      error => {
        console.log(error);
      }
    );

  }
  reset(){
    this.departmentField={} as Department;
  }


}
