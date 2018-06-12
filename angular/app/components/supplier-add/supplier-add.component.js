class SupplierAddController{
    constructor(API, $state, $stateParams){
        'ngInject';
        this.$state = $state
        this.formSubmitted = false
        this.API = API
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }
    }

    save(isValid){
        if(isValid){
            let suppliers = this.API.service('supplier',this.API.all('suppliers'))
            let $state = this.$state

            suppliers.post({
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                phone: this.phone,
                address: this.address,
                city: this.city,
                state: this.state,
                zip: this.zip,
                company_name: this.company_name
            }).then(() => {
                let alert = {type: 'success',title:'Success!',msg:'Supplier has been added'}
                $state.go($state.current,{alerts: alert})
            }, (response) => {
                let alert = {type: 'error',title: 'Error!',msg: response.data.message}
                $state.go($state.current,{alerts: alert})
            })
        }
    }

    $onInit(){
    }
}

export const SupplierAddComponent = {
    templateUrl: './views/app/components/supplier-add/supplier-add.component.html',
    controller: SupplierAddController,
    controllerAs: 'vm',
    bindings: {}
}
