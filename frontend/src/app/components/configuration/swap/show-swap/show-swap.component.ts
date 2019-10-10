import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
declare var $ :any;

@Component({
  selector: 'app-show-swap',
  templateUrl: './show-swap.component.html',
  styleUrls: ['./show-swap.component.css']
})
export class ShowSwapComponent implements AfterViewInit,OnDestroy,OnInit {


  @ViewChild(DataTableDirective)


  dtElement: DataTableDirective;
  dtOptions:DataTables.Settings={};
  dtTrigger:Subject<any>=new Subject();
  dtInstance:DataTables.Api;




  constructor(private modalService: NgbModal,private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router) { }

  ngOnInit() {
    this.getSwapData();

  }
  getSwapData(){

    const token=this.token.get();
    this.dtOptions = {
      stateSave:true,

      "drawCallback": function () {
        let api = this.api();


      },
      ajax: {
        url: Constants.API_URL+'swap/getAllSwapReq'+'?token='+token,
        type: 'POST',
        data:function (d){



        },
      },
      columns: [

        { data: 'empFullnameBy' ,name:'empFullnameBy'},
        { data: 'swap_by_date' ,name:'swap_by_date'},
        { data: 'shift_byName' ,name:'shift_byName'},
        { data: 'empFullnameFor' ,name:'empFullnameFor'},
        { data: 'swap_for_date' ,name:'swap_for_date'},
        { data: 'shift_forName' ,name:'shift_forName'},


        {

          "data": function (data: any, type: any, full: any) {


            return '<div class="dropdown">\n' +
              '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
              '  </button>\n' +
              '  <div class="dropdown-menu">\n' +
              '    <button class="dropdown-item" data-edit-id="'+data.id+'" >Edit</button>\n' +
              '    <button ngxPermissionsOnly="['+"admin"+','+"Manager"+']" class="dropdown-item" data-Accept-id="'+data.id+'" >Accept</button>\n' +
              '  </div>\n' +
              '</div>';
          },
          "orderable": false, "searchable":false, "name":"selected_rows"
        }


      ],
      processing: true,
      serverSide: true,
      pagingType: 'full_numbers',
      pageLength: 10
    };

  }

  ngAfterViewInit(): void {
    this.dtTrigger.next();

    this.renderer.listenGlobal('document', 'click', (event) => {

      if (event.target.hasAttribute("data-edit-id")) {

        let id=event.target.getAttribute("data-edit-id");

       // this.editRequestSwap(id);


      }else if (event.target.hasAttribute("data-Accept-id")){

        let id=event.target.getAttribute("data-Accept-id");



        this.acceptSwapReq(id);

      }


    });


  }
  rerender(){
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();
      this.dtTrigger.next();

    });
  }
  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }

  acceptSwapReq(id)
  {

    const token=this.token.get();

    this.http.post(Constants.API_URL+'swap/acceptSwapReq'+'?token='+token,{'id':id}).subscribe(data1 => {

        console.log(data1);

        $.alert({
          title: 'Success',
          content: 'Update Successfull',
        });



        this.rerender();





      },
      error => {
        console.log(error);
      }
    );



  }







}
