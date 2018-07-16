class OrdersListController{
    constructor($scope, $state, $compile, DTOptionsBuilder, DTColumnBuilder, API){
        'ngInject';

        this.API = API
        this.$state = $state

        let Orders = this.API.service('orders')
        Orders.getList().then((response) => {
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
                DTColumnBuilder.newColumn('status').withTitle('Status'),
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
            <a class="btn btn-xs btn-warning" ui-sref="app.orders-edit({ordersId: ${data.id}})">
                <i class="fa fa-edit"></i>
            </a>
            &nbsp
            `
            buttons += `
            <a class="btn btn-xs btn-warning" ui-sref="app.order-invoice({orderId: ${data.id}})">
                <i class="fa fa-search-plus"></i>
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

    delete(id){

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
        },() => {
            API.one('orders').one('order',id).remove().then(() => {
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

export const OrdersListComponent = {
    templateUrl: './views/app/components/orders-list/orders-list.component.html',
    controller: OrdersListController,
    controllerAs: 'vm',
    bindings: {}
}
