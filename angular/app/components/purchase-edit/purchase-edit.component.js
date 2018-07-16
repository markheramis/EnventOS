class PurchaseEditController{
    constructor($state, $stateParams, API){
        'ngInject';

        this.API = API
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []

        this.purchaseData = null

        if($stateParams.alerts) this.alerts.push($stateParams.alerts)

        let Suppliers = this.API.service('suppliers')
        Suppliers.getList().then((response) => {
            this.suppliers = response.plain()
        })
        let Purchase = API.service('purchase',API.all('purchases'))
        Purchase.one($stateParams.purchaseId).get().then((response) => {
            this.purchaseData      = API.copy(response)
        })
    }
    save(isValid){
        console.log('sumbitting');
        if(isValid){
            let $state = this.$state
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
