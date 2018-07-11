class SupplierListController{
    constructor($scope, $state, $compile, DTOptionsBuilder, DTColumnBuilder, API){
        'ngInject';

        this.API = API
        this.$state = $state

        let Suppliers = this.API.service('suppliers')
        Suppliers.getList().then((response) => {
            let dataSet = response.plain()

            this.dtOptions = DTOptionsBuilder
            .newOptions()
            .withOption('data',dataSet)
            .withOption('createdRow',createdRow)
            .withOption('responsive',true)
            .withBootstrap()

            this.dtColumns = [
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn('first_name').withTitle('First Name'),
                DTColumnBuilder.newColumn('last_name').withTitle('Last Name'),
                DTColumnBuilder.newColumn('email').withTitle('Email'),
                DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable().renderWith(actionsHtml)
            ]

            this.displayTable = true
        })

        let createdRow = (row) => {
            $compile(angular.element(row).contents())($scope)
        }

        let actionsHtml = (data) => {
            var buttons = ``
            buttons += `
            <a class="btn btn-xs btn-warning" ui-sref="app.supplier-edit({supplierId: ${data.id}})">
                <i class="fa fa-edit"></i>
            </a>
            &nbsp
            `
            buttons += `
            <a class="btn btn-xs btn-danger" ng-click="vm.delete(${data.id})">
                <i class="fa fa-trash-o"></i>
            </a>
            `
            return buttons
        }
    }
    delete(customerId){
        let API = this.API
        let $state = this.$state
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this data!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            html: false
        }, function () {
            API.one('suppliers').one('supplier', customerId).remove().then(() => {
                swal({
                    title: 'Deleted!',
                    text: 'User Permission has been deleted.',
                    type: 'success',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                }, function () {
                    $state.reload()
                })
            })
        })
    }
    $onInit(){
    }
}

export const SupplierListComponent = {
    templateUrl: './views/app/components/supplier-list/supplier-list.component.html',
    controller: SupplierListController,
    controllerAs: 'vm',
    bindings: {}
}
