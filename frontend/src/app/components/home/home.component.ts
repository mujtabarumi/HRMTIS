import { Component, OnInit } from '@angular/core';
import { CheckService } from '../../services/check.service';
import {TokenService} from "../../services/token.service";
import {HttpClient} from "@angular/common/http";
import {ActivatedRoute, Router} from "@angular/router";
import {Constants} from "../../constants";
declare var $ :any;
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {



  constructor(private check:CheckService,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router) {

  }


  ngOnInit() {

  }




}
