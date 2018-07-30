    export function RoutesConfig($stateProvider, $urlRouterProvider) {
    'ngInject'

    var getView = (viewName) => {
        return `./views/app/pages/${viewName}/${viewName}.page.html`
    }

    var getLayout = (layout) => {
        return `./views/app/pages/layout/${layout}.page.html`
    }

    $urlRouterProvider.otherwise('/')

    $stateProvider
    .state('app', {
        abstract: true,
        views: {
            'layout': {
                templateUrl: getLayout('layout')
            },
            'header@app': {
                templateUrl: getView('header')
            },
            'footer@app': {
                templateUrl: getView('footer')
            },
            main: {}
        },
        data: {
            bodyClass: 'hold-transition skin-blue sidebar-mini'
        }
    })
    .state('app.landing', {
        url: '/',
        data: {auth: true},
        views: {
            'main@app': {
                templateUrl: getView('landing')
            }
        }
    })
    .state('app.purchase-list',{
        url: '/purchase/list',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<purchase-list></purchase-list>'
            }
        }
    })
    .state('app.purchase-add',{
        url: '/purchase/add',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<purchase-add></purchase-add>'
            }
        },
        params:{
            alerts: null
        }
    })
    .state('app.purchase-edit',{
        url: '/purchase/edit/:purchaseId',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<purchase-edit></purchase-edit>'
            }
        },
        params: {
            alerts: null,
            purchaseId: null
        }
    })
    .state('app.purchase-invoice',{
        url: '/purchase/invoice/:purchaseId',
        data: {auth: true},
        views: {
            'main@app': {
                template:'<purchase-invoice></purchase-invoice>'
            }
        },
        params:{
            purchaseId: null
        }
    })
    .state('app.orders-create',{
        url: '/orders/create',
        data: {auth: true},
        views: {
            'main@app':{
                template:'<orders-create></orders-create>'
            }
        },
        params: {
            alerts: null
        }
    })
    .state('app.orders-list',{
        url: '/orders/list',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<orders-list></orders-list>'
            }
        }
    })
    .state('app.orders-edit',{
        url: '/orders/edit/:ordersId',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<orders-edit></orders-edit>'
            }
        },
        params:{
            alerts: null,
            ordersId: null
        }
    })
    .state('app.order-invoice',{
        url: '/orders/invoice/:orderId',
        data: {auth: true},
        views: {
            'main@app' : {
                template: '<order-invoice></order-invoice>'
            }
        },
        params: {
            alerts: null,
            orderId: null
        }
    })
    .state('app.product-list',{
        url: '/product/list',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<product-list></product-list>'
            }
        }
    })
    .state('app.product-add',{
        url: '/product/add',
        data: {auth:  true},
        views: {
            'main@app': {
                template: '<product-add></product-add>'
            }
        },
        params: {
            alerts: null
        }
    })
    .state('app.product-edit',{
        url: '/product/edit/:productId',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<product-edit></product-edit>'
            }
        },
        params: {
            alerts: null,
            productId: null
        }
    })
    .state('app.supplier-list',{
        url: '/supplier/list',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<supplier-list></supplier-list>'
            }
        }
    })
    .state('app.supplier-add',{
        url: '/supplier/add',
        data: {auth: true},
        views: {
            'main@app':{
                template:'<supplier-add></supplier-add>'
            }
        },
        params:{
            alerts: null
        }
    })
    .state('app.supplier-edit',{
        url: '/supplier/edit/:supplierId',
        data: {auth: true},
        views: {
            'main@app':{
                template:'<supplier-edit></supplier-edit>'
            }
        },
        params:{
            alerts: null,
            supplierId: null
        }
    })
    .state('app.customer-list',{
        url: '/customer/list',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<customer-list></customer-list>'
            }
        }
    })
    .state('app.customer-add',{
        url: '/customer/add',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<customer-add></customer-add>'
            }
        },
        params:{
            alerts: null
        }
    })
    .state('app.customer-edit',{
        url: '/customer/edit/:customerId',
        data: {auth: true},
        views: {
            'main@app':{
                template: '<customer-edit></customer-edit>'
            }
        },
        params:{
            alerts: null,
            customerId: null
        }
    })
    .state('app.userpermissions', {
        url: '/user-permissions',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-permissions></user-permissions>'
            }
        }
    })
    .state('app.userpermissionsedit', {
        url: '/user-permissions/edit/:permissionId',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-permissions-edit></user-permissions-edit>'
            }
        },
        params: {
            alerts: null,
            permissionId: null
        }
    })
    .state('app.userpermissionsadd', {
        url: '/user-permissions/add',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-permissions-add></user-permissions-add>'
            }
        },
        params: {
            alerts: null
        }
    })

    .state('app.userroles', {
        url: '/user-roles',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-roles></user-roles>'
            }
        }
    })
    .state('app.userrolesedit', {
        url: '/user-roles/edit/:roleId',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-roles-edit></user-roles-edit>'
            }
        },
        params: {
            alerts: null,
            roleId: null
        }
    })

    .state('app.userrolesadd', {
        url: '/user-roles/add',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-roles-add></user-roles-add>'
            }
        },
        params: {
            alerts: null
        }
    })

    .state('app.profile', {
        url: '/profile',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-profile></user-profile>'
            }
        },
        params: {
            alerts: null
        }
    })
    .state('app.userlist', {
        url: '/user/lists',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-lists></user-lists>'
            }
        }
    })
    .state('app.useredit', {
        url: '/user/edit/:userId',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-edit></user-edit>'
            }
        },
        params: {
            alerts: null,
            userId: null
        }
    })
    .state('app.useradd',{
        url: '/user/add',
        data: {auth: true},
        views: {
            'main@app': {
                template: '<user-add></user-add>'
            }
        }
    })

    .state('loginloader', {
        url: '/login-loader',
        views: {
            'layout': {
                templateUrl: getView('login-loader')
            },
            'header@app': {},
            'footer@app': {}
        },
        data: {
            bodyClass: 'hold-transition login-page'
        }
    })
    .state('login', {
        url: '/login',
        views: {
            'layout': {
                templateUrl: getView('login')
            },
            'header@app': {},
            'footer@app': {}
        },
        data: {
            bodyClass: 'hold-transition login-page'
        },
        params: {
            registerSuccess: null,
            successMsg: null
        }
    })
    .state('register', {
        url: '/register',
        views: {
            'layout': {
                templateUrl: getView('register')
            },
            'header@app': {},
            'footer@app': {}
        },
        data: {
            bodyClass: 'hold-transition register-page'
        }
    })
    .state('forgot_password', {
        url: '/forgot-password',
        views: {
            'layout': {
                templateUrl: getView('forgot-password')
            },
            'header@app': {},
            'footer@app': {}
        },
        data: {
            bodyClass: 'hold-transition login-page'
        }
    })
    .state('reset_password', {
        url: '/reset-password/:email/:token',
        views: {
            'layout': {
                templateUrl: getView('reset-password')
            },
            'header@app': {},
            'footer@app': {}
        },
        data: {
            bodyClass: 'hold-transition login-page'
        }
    })
    .state('userverification', {
        url: '/userverification/:status',
        views: {
            'layout': {
                templateUrl: getView('user-verification')
            }
        },
        data: {
            bodyClass: 'hold-transition login-page'
        },
        params: {
            status: null
        }
    })
    .state('app.logout', {
        url: '/logout',
        views: {
            'main@app': {
                controller: function ($rootScope, $scope, $auth, $state, AclService) {
                    $auth.logout().then(function () {
                        delete $rootScope.me
                        AclService.flushRoles()
                        AclService.setAbilities({})
                        $state.go('login')
                    })
                }
            }
        }
    })
}
