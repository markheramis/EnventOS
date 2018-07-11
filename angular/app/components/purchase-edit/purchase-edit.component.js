class PurchaseEditController{
    constructor($state, $stateParams, API){
        'ngInject';

        this.API = API
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []

        this.items = []
        this.purchaseItems = []
        this.amount_tendered = 0.00
        this.cost_price = 0.00
        this.selling_price = 0.00

        if($stateParams.alerts) this.alerts.push($stateParams.alerts)

        let Suppliers = this.API.service('suppliers')
        Suppliers.getList().then((response) => {
            this.suppliers = response.plain()
        })

        let Purchase = API.service('purchase',API.all('purchases'))
        Purchase.one($stateParams.purchaseId).get().then((response) => {
            this.purchaseData      = API.copy(response)

            response                = response.plain()
            this.payment_type       = response.data.payment_type
            this.supplier_id        = response.data.supplier_id
            this.comments           = response.data.comments
            this.items              = response.data.items

        })
    }
    save(isValid){
        if(isValid){
            let $state = this.$state
            this.purchaseData.data.supplier_id = this.supplier_id
            this.purchaseData.data.cost_price = this.cost_price
            this.purchaseData.data.comments = this.comments

            this.purchaseData.put().then(() => {
                let alert = {type: 'success', title: 'Success!', msg: 'Purchase has been updated successfully'}
                $state.go($state.current, {alerts: alert})
            }, (response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current, {alerts: alert})
            })
        }
    }

    $onInit(){
    }
}

export const PurchaseEditComponent = {
    templateUrl: './views/app/components/purchase-edit/purchase-edit.component.html',
    controller: PurchaseEditController,
    controllerAs: 'vm',
    bindings: {}
}
