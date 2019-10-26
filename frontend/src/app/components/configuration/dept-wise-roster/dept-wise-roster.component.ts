import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';

declare var $: any;

@Component({
  selector: 'app-dept-wise-roster',
  templateUrl: './dept-wise-roster.component.html',
  styleUrls: ['./dept-wise-roster.component.css']
})
export class DeptWiseRosterComponent implements OnInit {

  dropdownSettingsEmp = {};
  dropdownSettings2 = {};
  departments: any;
  Date: string;
  RosterInfo: any;
  ChangeRosterInfo: any;
  selectedDropDown = [];
  selectedDropDownEmp = [];
  modalRef: any;
  employees: any;

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router) {

  }

  ngOnInit() {
    this.dropdownSettings2 = {
      singleSelection: true,
      idField: 'id',
      textField: 'departmentName',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };
    this.dropdownSettingsEmp = {
      singleSelection: true,
      idField: 'EmployeeId',
      textField: 'EmpFullNames',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };


    this.getAllDepartment();

  }
  getAllDepartment() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'department/get' + '?token=' + token).subscribe(data => {

        this.departments = data;

      },
      error => {
        console.log(error);
      }
    );

  }

  searchRoster() {

    const token = this.token.get();
    const form = {
      departments: this.selectedDropDown[0]['id'],
      date: $('#Date').val(),

    };

    console.log(form);

    this.http.post(Constants.API_URL + 'department/getRosterAndEmpInfo' + '?token=' + token, form).subscribe(data => {

        this.RosterInfo = data;
        console.log(data);

      },
      error => {
        console.log(error);
      }
    );


  }
  ChangeRosterLog(shiftId, content) {

    const token = this.token.get();

    const deptId = [];
    deptId.push(this.selectedDropDown[0]['id']);

    const form = {
      departments: deptId,
      date: $('#Date').val(),
      shift: shiftId,

    };


    this.http.post(Constants.API_URL + 'employee/getAllEmpForDepartment' + '?token=' + token, form).subscribe(data => {


      this.employees = data;




      },
      error => {
        console.log(error);
      }
    );
    this.http.post(Constants.API_URL + 'department/shift/getRosterAndEmpInfo' + '?token=' + token, form).subscribe(data => {



          this.ChangeRosterInfo = data;



          // for (let i = 0; i < data.length; i++) {
          //   const d = {
          //     'EmployeeId': data[i]['EmployeeId'],
          //     'EmpFullNames': data[i]['empFullname']
          //   };
          //   this.selectedDropDownEmp.push(d);
          // }


            // console.log(this.selectedDropDownEmp);




      },
      error => {
        console.log(error);
      }
    );

    this.modalRef = this.modalService.open(content, {size: 'lg', backdrop: 'static'});



  }
  private modalClose() {

    this.modalRef.close();

  }

}
