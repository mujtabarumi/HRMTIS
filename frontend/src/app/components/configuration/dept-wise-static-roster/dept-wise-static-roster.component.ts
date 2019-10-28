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
  selector: 'app-dept-wise-static-roster',
  templateUrl: './dept-wise-static-roster.component.html',
  styleUrls: ['./dept-wise-static-roster.component.css']
})
export class DeptWiseStaticRosterComponent implements OnInit {

  dropdownSettings2 = {};
  dropdownSettingsEmp = {};
  departments: any;
  selectedDropDown = [];
  selectedDropDownEmp = [];
  RosterInfo: any;
  AllRosterInfo: any;
  dayName = [];
  modalRef: any;
  employees: any;
  showbtn: boolean;

  newEmpRoster: any = {

    rosterLogId: '',
    shiftId: '',
    dayName: '',
    dutyempIds: [],
    offdutyempIds: [],

  };

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router) {

  }

  adddiv() {
    if (this.showbtn == true) {
      this.showbtn = false;
    }
    if (this.showbtn == false) {
      this.showbtn = true;
    }
  }

  ngOnInit() {

    this.showbtn = true;

    this.dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

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
      singleSelection: false,
      idField: 'empid',
      textField: 'empFullname',
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


    };

    console.log(form);

    this.http.post(Constants.API_URL + 'department/getRosterInfo' + '?token=' + token, form).subscribe(data => {

        this.AllRosterInfo = data;

        const that = this;

        this.http.post(Constants.API_URL + 'department/getStaticRosterAndEmpInfo' + '?token=' + token, form).subscribe(data => {

            that.RosterInfo = data;
            console.log(data);

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

    const deptId = [];

    for (let i = 0; i < this.selectedDropDown.length; i++) {

      deptId.push(this.selectedDropDown[0]['id']);
    }

    const form1 = {
      departments: deptId,

    };

    this.http.post(Constants.API_URL + 'employee/getAllEmpForDepartment' + '?token=' + token, form1).subscribe(data => {

        this.employees = data;

        console.log(data);



      },
      error => {
        console.log(error);
      }
    );



  }

  findEmp(shiftId, dayName) {



    const token = this.token.get();
    const form = {
      shiftId: shiftId,
      day: dayName,

    };



    this.http.post(Constants.API_URL + 'rosterLog/getStaticRosterInfo' + '?token=' + token, form).subscribe(data => {


        return data['EmpRosterNames'];

      },
      error => {
        console.log(error);
      }
    );


  }
  ChangeRosterLog(shiftId, dayName, rosterLogId, content) {



    const token = this.token.get();
    const form = {
      shiftId: shiftId,
      day: dayName,

    };

   // console.log(form);



    this.http.post(Constants.API_URL + 'rosterLog/getStaticRosterInfo' + '?token=' + token, form).subscribe(data => {


        for (let i = 0; i < data.length; i++) {
          const d = {
            'empid': data[i]['EmployeeId'],
            'empFullname': data[i]['empFullname']
          };
          const e = {
            'empid': data[i]['EmployeeId'],

          };
          this.selectedDropDownEmp.push(d);
          this.newEmpRoster.dutyempIds.push(e);
        }



        console.log(this.selectedDropDownEmp);


      },
      error => {
        console.log(error);
      }
    );
    this.newEmpRoster.shiftId = shiftId;
    this.newEmpRoster.dayName = dayName;
    this.newEmpRoster.rosterLogId = rosterLogId;

    this.modalRef = this.modalService.open(content, {size: 'lg', backdrop: 'static'});


  }
  test(event) {
    console.log(this.selectedDropDownEmp);
  }
  private modalClose() {

    this.modalRef.close();

  }

  updateRoster() {

    const token = this.token.get();
    console.log(this.newEmpRoster);

    this.http.post(Constants.API_URL + 'rosterLog/setStaticRosterInfo' + '?token=' + token, this.newEmpRoster).subscribe(data => {


      console.log(data);

      },
      error => {
        console.log(error);
      }
    );

  }

}
