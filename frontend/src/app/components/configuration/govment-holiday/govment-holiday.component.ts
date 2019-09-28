import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
declare var $ :any;

@Component({
  selector: 'app-govment-holiday',
  templateUrl: './govment-holiday.component.html',
  styleUrls: ['./govment-holiday.component.css']
})
export class GovmentHolidayComponent implements  AfterViewInit,OnDestroy,OnInit {

  @ViewChild('editGovtHoliday') editModal: any;
  @ViewChild(DataTableDirective)


  dtElement: DataTableDirective;
  dtOptions:DataTables.Settings={};
  dtTrigger:Subject<any>=new Subject();
  dtInstance:DataTables.Api;

  modalRef:any;
  rejectModel:any={};
  govtHolidayObj:any={
    holidayName:"",
    startDate:"",
    endDate:'',
    purpose:'',
    noOfDays:'',
    createdBy:'',
    status:'',
    modified_date:'',
    modified_by:''

  };



  constructor(private modalService: NgbModal,private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router) { }

  ngOnInit() {

      this.getAllGovtHoliday();


  }

   getAllGovtHoliday()
   {

    const token=this.token.get();
    this.dtOptions = {
      stateSave:true,

      "drawCallback": function () {
        let api = this.api();


      },
      ajax: {
        url: Constants.API_URL+'govtHoliday/getAllGovtHoliday'+'?token='+token,
        type: 'POST',
        data:function (d){

          if ($('#startDate').val()!='')
          {
            d['startDate']=$('#startDate').val();

          }
          if ($('#endDate').val()!='')
          {
            d['endDate']=$('#endDate').val();

          }
          if ($('#HolidayStatus').val()!='')
          {
            d['HolidayStatus']=$('#HolidayStatus').val();

          }

        },
      },
      columns: [

        { data: 'holidayName' ,name:'holidayName'},
        { data: 'startDate' ,name:'startDate'},
        { data: 'endDate' ,name:'endDate'},
        { data: 'noOfDays' ,name:'noOfDays'},
        { data: 'purpose' ,name:'purpose'},
        { data: 'status' ,name:'status'},

        { data: 'empFullname' ,name:'empFullname'},

        {

          "data": function (data: any, type: any, full: any) {
            return '<div class="dropdown">\n' +
              '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
              '  </button>\n' +
              '  <div class="dropdown-menu">\n' +
              '    <button class="dropdown-item" data-edit-id="'+data.id+'" >Edit</button>\n' +
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

        this.editGovtHoliday(id);


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
  addModal(addGovtHoliday){

    this.govtHolidayObj={};
    this.modalRef =  this.modalService.open(addGovtHoliday, { size: 'lg',backdrop:'static'});
  }
  insertGovtHoliday()
  {
    const token=this.token.get();

    this.http.post(Constants.API_URL+'govtHoliday/insertNewGovtHoliday'+'?token='+token,this.govtHolidayObj).subscribe(data => {

        $.alert({
          title: data,
          content: 'Update Successfull',
        });
        this.rerender();
        this.govtHolidayObj={};

      },
      error => {
        console.log(error);
      }
    );

    this.modalRef.close();



  }

  editGovtHoliday(id){

    const token=this.token.get();

    this.http.post(Constants.API_URL+'govtHoliday/getGovtHolidayInfo'+'?token='+token,{id:id}).subscribe(data => {

        this.govtHolidayObj.holidayName=data['holidayName'];
        this.govtHolidayObj.startDate=data['startDate'];
        this.govtHolidayObj.endDate=data['endDate'];
        this.govtHolidayObj.purpose=data['purpose'];
        this.govtHolidayObj.noOfDays=data['noOfDays'];
        this.govtHolidayObj.status=data['status'];
        this.govtHolidayObj.id=data['id'];

        console.log(this.govtHolidayObj);

        this.modalRef = this.modalService.open(this.editModal, {size: 'lg',backdrop:'static'});

      },
      error => {
        console.log(error);
      }
    );


  }
  updateGovtHoliday(){

    const token=this.token.get();

    this.http.post(Constants.API_URL+'govtHoliday/updateGovtHoliday'+'?token='+token,this.govtHolidayObj).subscribe(data => {

        $.alert({
          title: data,
          content: 'Update Successfull',
        });
        this.rerender();
        this.govtHolidayObj={};

      },
      error => {
        console.log(error);
      }
    );

    this.modalRef.close();

  }

}
