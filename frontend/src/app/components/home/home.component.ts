import { Component, OnInit } from '@angular/core';
import { CheckService } from '../../services/check.service';
import {TokenService} from '../../services/token.service';
import {HttpClient} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {Constants} from '../../constants';
declare var $: any;

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  showTotalDiv: boolean;
  designation: any;
  activeEmp: any;

  // tslint:disable-next-line:max-line-length
  constructor(private check: CheckService, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router) {

  }


  ngOnInit() {

    if (localStorage.getItem('role') == 'admin') {

      this.showTotalDiv = true;
      this.designation = 'admin';

      this.getTotalActiveEmp();

    } else {

      this.showTotalDiv = false;
      this.designation = localStorage.getItem('role');
    }

  }
  getTotalActiveEmp() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'employee/getTotalActiveEmp' + '?token=' + token).subscribe(data => {

      this.activeEmp = data;


      },
      error => {
        console.log(error);
      }
    );

  }




}
