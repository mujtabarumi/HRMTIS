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

  dropdownSettingsEmpduty = {};
  dropdownSettingsEmpOffduty = {};
  dropdownSettings2 = {};
  departments: any;
  Date: string;
  RosterInfo: any;
  getRosterInfo: any;
  staticResult: any;
  ChangeRosterInfo: any;
  selectedDropDown = [];
  selectedDropDownEmpduty = [];
  selectedDropDownEmpOffduty = [];
  modalRef: any;
  dutyemployees: any;
  OffDutyemployees: any;
  employees: any;
  showTable: boolean;
  showExistingData: boolean;

  existingRosterData: any;



  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router) {

  }

  ngOnInit() {
    this.showTable = false;
    this.showExistingData = false;

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

  onItemSelectDepartment(value) {

    this.showTable = false;
    this.selectedDropDownEmpduty = [];
    this.selectedDropDownEmpOffduty = [];

    if (this.selectedDropDown.length > 0) {



      const deptId = [];

      for (let i = 0; i < this.selectedDropDown.length; i++) {

        deptId.push(this.selectedDropDown[i]['id']);
      }

      const form = {
        departments: deptId,

      };

      const token = this.token.get();


      this.http.post(Constants.API_URL + 'department/getRosterInfo' + '?token=' + token, form).subscribe(data => {

          this.RosterInfo = data;



        },
        error => {
          console.log(error);
        }
      );




    }



  }
  onItemDeSelectDepartment(value) {

    this.showTable = false;
    this.selectedDropDownEmpduty = [];
    this.selectedDropDownEmpOffduty = [];

    if (this.selectedDropDown.length > 0) {

      const deptId = [];

      for (let i = 0; i < this.selectedDropDown.length; i++) {

        deptId.push(this.selectedDropDown[i]['id']);
      }

      const form = {
        departments: deptId,

      };

      const token = this.token.get();


      this.http.post(Constants.API_URL + 'department/getRosterInfo' + '?token=' + token, form).subscribe(data => {

          this.RosterInfo = data;



        },
        error => {
          console.log(error);
        }
      );




    } else {
      this.RosterInfo = [];

    }



  }



  searchRoster() {

    this.showTable = true;

    this.dropdownSettingsEmpduty = {
      singleSelection: false,
      idField: 'empId',
      textField: 'empFullname',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };
    this.dropdownSettingsEmpOffduty = {
      singleSelection: false,
      idField: 'empId',
      textField: 'empFullname',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };

    const token = this.token.get();

    const deptId = [];

    for (let i = 0; i < this.selectedDropDown.length; i++) {

      deptId.push(this.selectedDropDown[i]['id']);
    }

    const form1 = {
      departments: deptId,

    };


    this.http.post(Constants.API_URL + 'employee/getAllEmpForDepartment' + '?token=' + token, form1).subscribe(data => {


        this.employees = data;

        const form = {
          departments: this.selectedDropDown[0]['id'],
          date: $('#Date').val(),
          shiftId: $('#RosterInfo').val(),

        };



        this.http.post(Constants.API_URL + 'rosterLog/getDataFromStaticRoster' + '?token=' + token, form).subscribe(data1 => {

            this.staticResult = data1;
            console.log(data1);


            for (let i = 0; i < this.staticResult.duty.length; i++) {

              if (this.staticResult.duty[i]['weekend'] == null || this.staticResult.duty[i]['weekend'] == '') {

                const d = {
                  'empId': this.staticResult.duty[i]['EmployeeId'],
                  'empFullname': this.staticResult.duty[i]['empFullname']
                };

                this.selectedDropDownEmpduty.push(d);
              }


            }
            for (let i = 0; i < this.staticResult.Offduty.length; i++) {

              if (this.staticResult.Offduty[i]['weekend'] != null || this.staticResult.Offduty[i]['weekend'] != '') {

                const o = {
                  'empId': this.staticResult.Offduty[i]['EmployeeId'],
                  'empFullname': this.staticResult.Offduty[i]['empFullname']
                };

                this.selectedDropDownEmpOffduty.push(o);
              }

            }
            //
              console.log(this.selectedDropDownEmpduty);
              console.log(this.selectedDropDownEmpOffduty);

          },
          error => {
            console.log(error);
          }
        );


      },
      error => {
        console.log(error);
      }
    );





  }
  rosterChange() {
    this.showExistingData = false;
    this.showTable = false;
  }

  onItemSelect(value) {

    console.log(value);
    console.log(this.selectedDropDownEmpduty);

  }
  onItemDeSelect(value) {

    console.log(this.selectedDropDownEmpduty);

  }
  onItemSelectEmpOffduty(value) {

    console.log(value);
    console.log(this.selectedDropDownEmpOffduty);

  }
  onItemDeSelectEmpOffduty(value) {

    console.log(this.selectedDropDownEmpOffduty);

  }
  submitRoster() {

    const token = this.token.get();

    const form = {
      departments: this.selectedDropDown[0]['id'],
      date: $('#Date').val(),
      shiftId: $('#RosterInfo').val(),

      dutyEmp: this.selectedDropDownEmpduty,
      offdutyEmp: this.selectedDropDownEmpOffduty,

    };

    this.http.post(Constants.API_URL + 'roster/setDepartmentWiseRosterByShift' + '?token=' + token, form).subscribe(data => {


        $.alert({
          title: data,
          content: 'Roster set Successfully',
        });
        this.modalClose();


      },
      error => {
        console.log(error);
      }
    );



  }

  findSetRoster() {

    const token = this.token.get();

    const form = {
      departments: this.selectedDropDown[0]['id'],
      date: $('#Date').val(),
      shiftId: $('#RosterInfo').val(),


    };

    this.http.post(Constants.API_URL + 'roster/findDepartmentWiseRosterByShift' + '?token=' + token, form).subscribe(data => {

     //  console.log(data);
        this.showExistingData = true;
        this.showTable = false;
        this.existingRosterData = data;


      },
      error => {
        console.log(error);
      }
    );

  }
  ChangeRoster() {

    this.searchRoster();

  }
  private modalClose() {

    this.modalRef.close();

  }

}
