import {Component, OnInit, Input} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Constants} from "../../../constants";
import {TokenService} from "../../../services/token.service";
import {Router} from '@angular/router';
declare var $ :any;

@Component({
  selector: 'app-basic-info',
  templateUrl: './basic-info.component.html',
  styleUrls: ['./basic-info.component.css']
})
export class BasicInfoComponent implements OnInit {

  department:any;
  designation:any;
  empType:any;
  basicinfo: any;
  result:any;
  error=[];
  employeeBasicForm:any={
    id:'',
    EmployeeId:'',
    department:'',
    designation:'',
    empType:'',
    firstName:'',
    middleName:'',
    lastName:'',
    nickName:'',
    email:'',
    contactNo:'',
    alterContactNo:'',
    birthdate:'',
    gender:'',
    photo:''

  };

  selectedFile:File;

  @Input('empid') empid: any;

  constructor(public http: HttpClient, private token:TokenService,private router: Router) { }

  ngOnInit() {

    //Getting Departments
    this.http.get(Constants.API_URL+'department/get').subscribe(data => {

        this.department=data;
      },
      error => {
        console.log(error);
      }
    );

    //Getting Designations
    this.http.get(Constants.API_URL+'designation/get').subscribe(data => {
        // console.log(data);
        this.designation=data;
      },
      error => {
        console.log(error);
      }
    );

    //Getting Employee Types
    this.http.get(Constants.API_URL+'employee_type/get').subscribe(data => {
        // console.log(data);
        this.empType=data;
      },
      error => {
        console.log(error);
      }
    );

    const token=this.token.get();
    this.http.post(Constants.API_URL+'employee/basicinfo'+'?token='+token,{ empid:this.empid}).subscribe(data => {

        // console.log(data);
        this.basicinfo  = data;
        if(data !=null){
          this.employeeBasicForm.id = this.empid;
          this.employeeBasicForm.EmployeeId = this.basicinfo.EmployeeId;
          this.employeeBasicForm.firstName = this.basicinfo.firstName;
          this.employeeBasicForm.middleName = this.basicinfo.middleName;
          this.employeeBasicForm.lastName = this.basicinfo.lastName;
          this.employeeBasicForm.email = this.basicinfo.email;
          this.employeeBasicForm.gender = this.basicinfo.gender;
          this.employeeBasicForm.birthdate = this.basicinfo.birthdate;
          this.employeeBasicForm.department = this.basicinfo.fkDepartmentId;
          this.employeeBasicForm.empType = this.basicinfo.fkEmployeeType;
          this.employeeBasicForm.designation = this.basicinfo.fkDesignation;
          this.employeeBasicForm.contactNo = this.basicinfo.contactNo;
          this.employeeBasicForm.alterContactNo = this.basicinfo.alterContactNo;
          this.employeeBasicForm.photo = Constants.Image_URL+'images/'+this.basicinfo.photo;
          // console.log(this.employeeBasicForm.photo);
        }


        //this.empType=data;
      },
      error => {
        console.log(error);
      }
    );
  }

}
