class OrdersEditController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.API = API
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []

        if($stateParams.alerts) this.alerts.push($stateParams.alerts)

        let Items = this.API.service('items')
        Items.getList().then((response) => {
            this.items = response.plain()
        })

        let Customers = this.API.service('customers')
        Customers.getList().then((response) => {
            this.customers = response.plain()
        })

        let Orders = this.API.service('order',API.all('orders'))
        Orders.one($stateParams.ordersId).get().then((response) => {
            this.orderData = API.copy(response)
        })
    }

    save(isValid) {
        if(isValid){
            let $state = this.$state
            this.orderData.put().then(() => {
                let alert = {type: 'success',title:'Success!',msg:'Orders has been updated.'}
                $state.go($state.current,{alerts:alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current, {alerts: alert})
            })
        }
    }

    $onInit() {
    }
}

export const OrdersEditComponent = {
    templateUrl: './views/app/components/orders-edit/orders-edit.component.html',
    controller: OrdersEditController,
    controllerAs: 'vm',
    bindings: {}
}
