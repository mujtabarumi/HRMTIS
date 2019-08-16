import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
// import { RouterModule, Routes } from '@angular/router';
import { ActivatedRouteSnapshot, RouterModule, RouterStateSnapshot, Routes } from '@angular/router';

import {HomeComponent} from "./components/home/home.component";
import {LoginComponent} from "./components/login/login.component";
import {GuestService} from "./services/guest.service";
import {AuthService} from "./services/auth.service";
import { NgxPermissionsGuard } from 'ngx-permissions';
import {EmployeeComponent} from "./components/user/employee/employee.component";
import {AddDepartmentComponent} from "./components/configuration/department/add-department/add-department.component";
import {ShiftComponent} from "./components/configuration/shift/shift.component";
import {ShiftAssignComponent} from "./components/configuration/shift-assign/shift-assign.component";
import {EditAssignedShiftComponent} from "./components/configuration/edit-assigned-shift/edit-assigned-shift.component";
import {AttendanceComponent} from "./components/report/attendance/attendance.component";
import {AddEmployeeComponent} from "./components/user/add-employee/add-employee.component";






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
    { path: 'employee', component: EmployeeComponent,canActivate: [AuthService] },
    { path: 'employee/edit/:id', component: AddEmployeeComponent,canActivate: [AuthService] },
    { path: 'configuration/department/add', component: AddDepartmentComponent,canActivate: [AuthService]  },
    { path: 'configuration/shift', component: ShiftComponent,canActivate: [AuthService] },
    { path: 'configuration/shift/assign', component: ShiftAssignComponent,canActivate: [AuthService] },
    { path: 'configuration/shift/edit-assign', component: EditAssignedShiftComponent,canActivate: [AuthService] },
    { path: 'report/attendance', component: AttendanceComponent,canActivate: [AuthService]  },




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
