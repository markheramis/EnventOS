class SupplierEditController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }
        let supplierId = $stateParams.supplierId
        let suppliers = API.service('supplier',API.all('suppliers'))
        suppliers.one(supplierId).get().then((response) => {
            this.supplierData = API.copy(response)
        })
    }

    save(isValid){
        if(isValid){
            let $state = this.$state
            this.supplierData.put().then(() => {
                let alert = {type: 'success',title:'Success!',msg:'Supplier has been updated.'}
                $state.go($state.current,{alerts:alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current, {alerts: alert})
            })
        }else{
            this.formsSubmitted = true
        }
    }

    $onInit(){
    }
}

export const SupplierEditComponent = {
    templateUrl: './views/app/components/supplier-edit/supplier-edit.component.html',
    controller: SupplierEditController,
    controllerAs: 'vm',
    bindings: {}
}
