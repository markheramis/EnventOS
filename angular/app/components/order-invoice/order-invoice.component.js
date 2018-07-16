class OrderInvoiceController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.API = API
        this.$state = $state

        let Order = API.service('order',API.all('orders'))

        Order.one($stateParams.orderId).get().then((response) => {
            this.orderData = API.copy(response)
        })
    }

    $onInit(){
    }
}

export const OrderInvoiceComponent = {
    templateUrl: './views/app/components/order-invoice/order-invoice.component.html',
    controller: OrderInvoiceController,
    controllerAs: 'vm',
    bindings: {}
}
