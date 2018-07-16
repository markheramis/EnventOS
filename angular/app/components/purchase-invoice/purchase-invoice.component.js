class PurchaseInvoiceController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.API = API
        this.$state = $state

        let Purchase = API.service('purchase',API.all('purchases'))

        Purchase.one($stateParams.purchaseId).get().then((response) => {
            this.purchaseData = API.copy(response)
        })
    }

    $onInit(){
    }
}

export const PurchaseInvoiceComponent = {
    templateUrl: './views/app/components/purchase-invoice/purchase-invoice.component.html',
    controller: PurchaseInvoiceController,
    controllerAs: 'vm',
    bindings: {}
}
