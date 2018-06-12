import { UserProfileComponent } from './app/components/user-profile/user-profile.component'
// Supplier
import {SupplierEditComponent} from './app/components/supplier-edit/supplier-edit.component';
import {SupplierAddComponent} from './app/components/supplier-add/supplier-add.component';
import {SupplierListComponent} from './app/components/supplier-list/supplier-list.component';
// Customer
import {CustomerEditComponent} from './app/components/customer-edit/customer-edit.component';
import {CustomerAddComponent} from './app/components/customer-add/customer-add.component';
import {CustomerListComponent} from './app/components/customer-list/customer-list.component';
import { UserVerificationComponent } from './app/components/user-verification/user-verification.component'
import { UserEditComponent } from './app/components/user-edit/user-edit.component'
import { UserPermissionsEditComponent } from './app/components/user-permissions-edit/user-permissions-edit.component'
import { UserPermissionsAddComponent } from './app/components/user-permissions-add/user-permissions-add.component'
import { UserPermissionsComponent } from './app/components/user-permissions/user-permissions.component'
import { UserRolesEditComponent } from './app/components/user-roles-edit/user-roles-edit.component'
import { UserRolesAddComponent } from './app/components/user-roles-add/user-roles-add.component'
import { UserRolesComponent } from './app/components/user-roles/user-roles.component'
import { UserListsComponent } from './app/components/user-lists/user-lists.component'
import { DashboardComponent } from './app/components/dashboard/dashboard.component'
import { NavSidebarComponent } from './app/components/nav-sidebar/nav-sidebar.component'
import { NavHeaderComponent } from './app/components/nav-header/nav-header.component'
import { LoginLoaderComponent } from './app/components/login-loader/login-loader.component'
import { ResetPasswordComponent } from './app/components/reset-password/reset-password.component'
import { ForgotPasswordComponent } from './app/components/forgot-password/forgot-password.component'
import { LoginFormComponent } from './app/components/login-form/login-form.component'
import { RegisterFormComponent } from './app/components/register-form/register-form.component'

angular.module('app.components')
  .component('userProfile', UserProfileComponent)
  .component('userVerification', UserVerificationComponent)
  .component('userEdit', UserEditComponent)
  .component('userPermissionsEdit', UserPermissionsEditComponent)
  .component('userPermissionsAdd', UserPermissionsAddComponent)
  .component('userPermissions', UserPermissionsComponent)
  .component('userRolesEdit', UserRolesEditComponent)
  .component('userRolesAdd', UserRolesAddComponent)
  .component('userRoles', UserRolesComponent)
  .component('userLists', UserListsComponent)
  .component('dashboard', DashboardComponent)
  .component('navSidebar', NavSidebarComponent)
  .component('navHeader', NavHeaderComponent)
  .component('loginLoader', LoginLoaderComponent)
  .component('resetPassword', ResetPasswordComponent)
  .component('forgotPassword', ForgotPasswordComponent)
  .component('loginForm', LoginFormComponent)
  .component('registerForm', RegisterFormComponent)
// Supplier
.component('supplierEdit', SupplierEditComponent)
.component('supplierAdd', SupplierAddComponent)
.component('supplierList', SupplierListComponent)
// Customer
.component('customerEdit', CustomerEditComponent)
.component('customerAdd', CustomerAddComponent)
.component('customerList', CustomerListComponent)
