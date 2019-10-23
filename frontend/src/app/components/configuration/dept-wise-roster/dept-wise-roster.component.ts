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

  constructor() { }

  ngOnInit() {
  }

}
