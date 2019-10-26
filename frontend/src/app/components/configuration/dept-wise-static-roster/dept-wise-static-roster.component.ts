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
  departments: any;
  selectedDropDown = [];
  RosterInfo: any;
  dayName = [];

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router) {

  }

  ngOnInit() {

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

        this.RosterInfo = data;
      //  console.log(data);

      },
      error => {
        console.log(error);
      }
    );


  }

  findEmp(shiftId, dayName) {

   // return dayName;

    const token = this.token.get();
    const form = {
      shiftId: shiftId,
      day: dayName,

    };

    this.http.post(Constants.API_URL + 'rosterLog/getStaticRosterInfo' + '?token=' + token, form).subscribe(data => {


        console.log(data);

      },
      error => {
        console.log(error);
      }
    );


  }

}
