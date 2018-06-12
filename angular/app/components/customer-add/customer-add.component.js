class CustomerAddController{
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

    save(isValid)
    {
        if(isValid)
        {
          let customers = this.API.service('customer',this.API.all('customers'))
          let $state = this.$state

          customers.post({
              first_name: this.first_name,
              last_name: this.last_name,
              email: this.email,
              phone: this.phone,
              address: this.address,
              city: this.city,
              state: this.state,
              zip: this.zip,
              company_name: this.company_name
          }).then(()=>{
              let alert = {type: 'success',title:'Success!',msg:'Customer has been added'}
              $state.go($state.current,{alerts: alert})
          }, (response) => {
              let alert = {type: 'error',title:'Error!',msg: response.data.message}
              $state.go($state.current,{alerts: alert})
          })
        }

    }

    $onInit(){
    }
}

export const CustomerAddComponent = {
    templateUrl: './views/app/components/customer-add/customer-add.component.html',
    controller: CustomerAddController,
    controllerAs: 'vm',
    bindings: {}
}
