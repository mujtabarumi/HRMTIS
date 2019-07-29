import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
// import { RouterModule, Routes } from '@angular/router';
import { ActivatedRouteSnapshot, RouterModule, RouterStateSnapshot, Routes } from '@angular/router';

import {HomeComponent} from "./components/home/home.component";
import {LoginComponent} from "./components/login/login.component";
import {GuestService} from "./services/guest.service";
import {AuthService} from "./services/auth.service";
import { NgxPermissionsGuard } from 'ngx-permissions';




export function testPermissions(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    // console.log(route.params);
    // if (route.params['id'] === 42) {
    //     return ['MANAGER', "UTILS"]
    // } else {
    //     return 'ADMIN'
    // }
}
const routes: Routes = [
    {path: '', component: LoginComponent, canActivate: [GuestService] },
    { path: 'login', component: LoginComponent, canActivate: [GuestService] },
    { path: 'home', component: HomeComponent,canActivate: [AuthService] },
];



@NgModule({
    imports: [
        RouterModule.forRoot(routes)
    ],
    exports: [
        RouterModule
    ],
    providers: [
        // CanDeactivateGuard
    ]
})


export class AppRoutingModule {


}