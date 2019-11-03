import { Component, OnInit } from '@angular/core';
import {Constants} from '../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../services/token.service';
import {User} from '../../model/user.model';
import {NgxPermissionsService} from 'ngx-permissions';
import { NavbarService } from '../../services/navbar.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  data: any;

  userModel = {} as User;
    user: any = {
        contactNo: '',
        email: '',
        fkActivationStatus: '',
        fkCompany: '',
        fkUserType: '',
        id:  '',
        picture: '',
        registrationdate:  '',
        rememberToken:  '',
        userName:  ''

    };
    tokenUser: any = {};
  permission: string;

  constructor(private permissionsService: NgxPermissionsService, public http: HttpClient, private token: TokenService,
              public nav: NavbarService) {

  }

  ngOnInit() {

    const token = this.token.get();


    //console.log(this.user);



      // this.token.getUser().subscribe(data => {
      //         this.userModel=data as User;
      //         // this.tokenUser=this.userModel;
      //
      //         let perm = [];
      //         perm.push(this.userModel.fkUserType);
      //         console.log(perm);
      //         this.permissionsService.loadPermissions(perm);
      //     },
      //     error => {
      //         console.log(error);
      //
      //
      //     });


          this.http.post(Constants.API_URL + 'me?token=' + token, null).subscribe(data1 => {



              if (data1['fkUserType'] == 'emp') {

                const token = this.token.get();
                this.http.post(Constants.API_URL + 'getEmpDesignation?token=' + token, {'id': data1['id']}).subscribe(data => {


                    if (data['designationTitle'] == Constants.manager) {

                      this.permissionsService.removePermission('emp');
                     // console.log(this.permissionsService.getPermissions());


                      const perm = [];
                      perm.push(data['designationTitle']);
                      this.permissionsService.loadPermissions(perm);

                      // console.log(this.permissionsService.getPermissions());


                    }if (data['designationTitle'] == Constants.HR) {

                      const perm = [];
                      perm.push(data['designationTitle']);
                      this.permissionsService.loadPermissions(perm);


                    } else {

                     // this.permission=[data['fkUserType']]
                    }





                  },
                  error => {
                    console.log(error);


                  }
                );

              }


            },
            error => {
              console.log(error);
              // this.handleError(error);

            }
          );

  }


    isAdmin() {
      if (this.user.fkUserType == 'admin') {
          return true;
      }
      //   console.log(this.user.fkUserType);
      return false;
    }

  whoAmI(e: MouseEvent) {
      e.preventDefault();


      const token = this.token.get();
      this.http.post(Constants.API_URL + 'me?token=' + token, null).subscribe(data => {
              console.log(data);

          },
          error => {
              console.log(error);
              this.handleError(error);

          }
      );
  }

  logout(e: MouseEvent) {
    e.preventDefault();
    const token = this.token.get();
    // console.log(token);
    //
    this.http.post(Constants.API_URL + 'logout?token=' + token, null).subscribe(data => {
          // console.log(data);
          this.data = data;
          if (this.data.flag === 'true') {
            this.token.remove();
          }

        },
        error => {

          if (error.status == 401 && error.error.message === 'Unauthenticated.') {
            this.token.remove();
          }

        }
    );


  }

  handleError(error) {
      if (error.status == 401 && error.error.message === 'Unauthenticated.') {
          this.token.remove();
      }

  }

}
