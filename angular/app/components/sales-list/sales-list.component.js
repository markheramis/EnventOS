class SalesListController{
    constructor($scope, $state, $compile, DTOptionsBuilder, DTColumnBuilder, API){
        'ngInject';

        this.API = API
        this.$state = $state

        let Sales = this.API.service('sales')
        Sales.getList().then((response) => {
            let dataSet = response.plain()

            this.dtOptions = DTOptionsBuilder
            .newOptions()
            .withOption('data',dataSet)
            .withOption('createdRow',createdRow)
            .withOption('responsive',true)
            .withBootstrap()

            this.dtColumns = [
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn(null).withTitle('User').renderWith(userHtml),
                DTColumnBuilder.newColumn(null).withTitle('Customer').renderWith(customerHtml),
                DTColumnBuilder.newColumn('payment_type').withTitle('Payment Type'),
                DTColumnBuilder.newColumn('items_count').withTitle('Items'),
                DTColumnBuilder.newColumn('selling_price').withTitle('Selling Price'),
                DTColumnBuilder.newColumn('created_at').withTitle('Date'),
                DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable().renderWith(actionsHtml)
            ]

            this.displayTable = true
        })

        let createdRow = (row) => {
            $compile(angular.element(row).contents())($scope)
        }

        let customerHtml = (data) => {
            let content = ``
            // if user can view customers
                // render a view customer link
            // else
                content = data.customer.first_name + ' ' + data.customer.last_name
            // end if
            return content
        }

        let userHtml = (data) => {
            let content = ``
            // if user can view users
            // render a view user link
            // else
            content = data.user.name
            // end if
            return content
        }

        let actionsHtml = (data) => {
            var buttons = ``
            buttons += `
            <a class="btn btn-xs btn-warning" ui-sref="app.sales-edit({salesId: ${data.id}})">
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

    $onInit(){
    }
}

export const SalesListComponent = {
    templateUrl: './views/app/components/sales-list/sales-list.component.html',
    controller: SalesListController,
    controllerAs: 'vm',
    bindings: {}
}
