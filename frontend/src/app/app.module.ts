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
