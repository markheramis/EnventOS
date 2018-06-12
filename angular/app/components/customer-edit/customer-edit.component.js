class CustomerEditController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }
        let customerId = $stateParams.customerId
        let customers = API.service('customer',API.all('customers'))
        customers.one(customerId).get().then((response) => {
            this.customerData = API.copy(response)
        })
    }

    save(isValid){
        if(isValid){
            let $state = this.$state
            this.customerData.put().then(()=>{
                let alert = {type:'success',title:'Success!',msg: 'Customer has been updated.'}
                $state.go($state.current, {alerts: alert})
            }, (response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current, {alerts: alert})
            })
        }else{
            this.formSubmitted = true
        }
    }

    $onInit(){
    }
}

export const CustomerEditComponent = {
    templateUrl: './views/app/components/customer-edit/customer-edit.component.html',
    controller: CustomerEditController,
    controllerAs: 'vm',
    bindings: {}
}
