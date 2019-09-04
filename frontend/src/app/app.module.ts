import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule,ReactiveFormsModule }   from '@angular/forms';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/home/home.component';
import { AppRoutingModule } from './app-routing.module';
import { HttpClientModule } from '@angular/common/http';
import { NavbarComponent } from './components/navbar/navbar.component';
import { DataTablesModule } from 'angular-datatables';
import { LoginComponent } from './components/login/login.component';
import { NgxSpinnerModule } from 'ngx-spinner';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';

import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { NgMultiSelectDropDownModule } from 'ng-multiselect-dropdown';
import { NgxPermissionsModule } from 'ngx-permissions';
import { EmployeeComponent } from './components/user/employee/employee.component';
import { AddDepartmentComponent } from './components/configuration/department/add-department/add-department.component';
import { ShiftComponent } from './components/configuration/shift/shift.component';
import { ShiftAssignComponent } from './components/configuration/shift-assign/shift-assign.component';
import { EditAssignedShiftComponent } from './components/configuration/edit-assigned-shift/edit-assigned-shift.component';
import { AttendanceComponent } from './components/report/attendance/attendance.component';
import { AddEmployeeComponent } from './components/user/add-employee/add-employee.component';
import { BasicInfoComponent } from './components/user/basic-info/basic-info.component';
import { JoiningInfoComponent } from './components/user/joining-info/joining-info.component';
import { AddLeaveComponent } from './components/leave/add-leave/add-leave.component';
import { LeaveSummeryComponent } from './components/leave/leave-summery/leave-summery.component';
import { LeaveComponent } from './components/configuration/leave/leave.component';
import { NotShiftAssignListComponent } from './components/configuration/not-shift-assign-list/not-shift-assign-list.component';
import { LeaveSummeryShowComponent } from './components/leave/leave-summery-show/leave-summery-show.component';
import { ShowLeaveComponent } from './components/configuration/show-leave/show-leave.component';
import { AddDesignationComponent } from './components/configuration/designation/add-designation/add-designation.component';








@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    NavbarComponent,
    LoginComponent,
    EmployeeComponent,
    AddDepartmentComponent,
    ShiftComponent,
    ShiftAssignComponent,
    EditAssignedShiftComponent,
    AttendanceComponent,
    AddEmployeeComponent,
    BasicInfoComponent,
    JoiningInfoComponent,
    AddLeaveComponent,
    LeaveSummeryComponent,
    LeaveComponent,
    NotShiftAssignListComponent,
    LeaveSummeryShowComponent,
    ShowLeaveComponent,
    AddDesignationComponent,








  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    DataTablesModule,
    HttpClientModule,
    NgxSpinnerModule,
    FormsModule,
    ReactiveFormsModule,
    BsDatepickerModule.forRoot(),
    NgbModule.forRoot(),
    NgMultiSelectDropDownModule.forRoot(),
    NgxPermissionsModule.forRoot(),
    NgbModule.forRoot(),
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
