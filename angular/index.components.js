//  Purchase
import {PurchaseEditComponent} from './app/components/purchase-edit/purchase-edit.component';
import {PurchaseAddComponent} from './app/components/purchase-add/purchase-add.component';
import {PurchaseListComponent} from './app/components/purchase-list/purchase-list.component';
import {PurchaseInvoiceComponent} from './app/components/purchase-invoice/purchase-invoice.component';
// Orders
import {OrdersCreateComponent} from './app/components/orders-create/orders-create.component';
import {OrdersEditComponent} from './app/components/orders-edit/orders-edit.component';
import {OrdersListComponent} from './app/components/orders-list/orders-list.component';
import {OrderInvoiceComponent} from './app/components/order-invoice/order-invoice.component';
// Products
import {ProductEditComponent} from './app/components/product-edit/product-edit.component';
import {ProductAddComponent} from './app/components/product-add/product-add.component';
import {ProductListComponent} from './app/components/product-list/product-list.component';
// Supplier
import {SupplierEditComponent} from './app/components/supplier-edit/supplier-edit.component';
import {SupplierAddComponent} from './app/components/supplier-add/supplier-add.component';
import {SupplierListComponent} from './app/components/supplier-list/supplier-list.component';
// Customer
import {CustomerEditComponent} from './app/components/customer-edit/customer-edit.component';
import {CustomerAddComponent} from './app/components/customer-add/customer-add.component';
import {CustomerListComponent} from './app/components/customer-list/customer-list.component';
// Core
import { LoginLoaderComponent } from './app/components/login-loader/login-loader.component'
import { LoginFormComponent } from './app/components/login-form/login-form.component'
import { RegisterFormComponent } from './app/components/register-form/register-form.component'
import { ForgotPasswordComponent } from './app/components/forgot-password/forgot-password.component'
import { ResetPasswordComponent } from './app/components/reset-password/reset-password.component'
import { UserVerificationComponent } from './app/components/user-verification/user-verification.component'
// Users
import { UserAddComponent } from './app/components/user-add/user-add.component';
import { UserEditComponent } from './app/components/user-edit/user-edit.component'
import { UserListsComponent } from './app/components/user-lists/user-lists.component'
import { UserProfileComponent } from './app/components/user-profile/user-profile.component'
// Roles
import { UserRolesAddComponent } from './app/components/user-roles-add/user-roles-add.component'
import { UserRolesEditComponent } from './app/components/user-roles-edit/user-roles-edit.component'
import { UserRolesComponent } from './app/components/user-roles/user-roles.component'
// Permissions
import { UserPermissionsAddComponent } from './app/components/user-permissions-add/user-permissions-add.component'
import { UserPermissionsEditComponent } from './app/components/user-permissions-edit/user-permissions-edit.component'
import { UserPermissionsComponent } from './app/components/user-permissions/user-permissions.component'
// Globals
import { NavSidebarComponent } from './app/components/nav-sidebar/nav-sidebar.component'
import { NavHeaderComponent } from './app/components/nav-header/nav-header.component'
import { DashboardComponent } from './app/components/dashboard/dashboard.component'

angular.module('app.components')
// Purchase
.component('purchaseEdit', PurchaseEditComponent)
.component('purchaseAdd', PurchaseAddComponent)
.component('purchaseList', PurchaseListComponent)
.component('purchaseInvoice', PurchaseInvoiceComponent)
// Orders
.component('ordersCreate', OrdersCreateComponent)
.component('ordersEdit', OrdersEditComponent)
.component('ordersList', OrdersListComponent)
.component('orderInvoice', OrderInvoiceComponent)
// Products
.component('productEdit', ProductEditComponent)
.component('productAdd', ProductAddComponent)
.component('productList', ProductListComponent)
// Supplier
.component('supplierEdit', SupplierEditComponent)
.component('supplierAdd', SupplierAddComponent)
.component('supplierList', SupplierListComponent)
// Customer
.component('customerEdit', CustomerEditComponent)
.component('customerAdd', CustomerAddComponent)
.component('customerList', CustomerListComponent)
// Core
.component('loginLoader', LoginLoaderComponent)
.component('loginForm', LoginFormComponent)
.component('registerForm', RegisterFormComponent)
.component('forgotPassword', ForgotPasswordComponent)
.component('resetPassword', ResetPasswordComponent)
.component('userVerification', UserVerificationComponent)
// Users
.component('userAdd', UserAddComponent)
.component('userEdit', UserEditComponent)
.component('userLists', UserListsComponent)
.component('userProfile', UserProfileComponent)
// Roles
.component('userRolesAdd', UserRolesAddComponent)
.component('userRolesEdit', UserRolesEditComponent)
.component('userRoles', UserRolesComponent)
// Permissions
.component('userPermissionsAdd', UserPermissionsAddComponent)
.component('userPermissionsEdit', UserPermissionsEditComponent)
.component('userPermissions', UserPermissionsComponent)
// Globals
.component('navSidebar', NavSidebarComponent)
.component('navHeader', NavHeaderComponent)
.component('dashboard', DashboardComponent)
