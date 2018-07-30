class ProductAddController{
    constructor(API, $state, $stateParams){
        'ngInject';
        this.$state = $state
        this.formSubmitted = false
        this.API = API
        this.alerts = []
        if($stateParams.alerts){
            this.alerts.push($stateParams.alerts)
        }
    }

    save(isValid){
        if(isValid){
            let products = this.API.service('product',this.API.all('products'))
            let $state = this.$state
            products.post({
                product_code: this.product_code,
                product_name: this.product_name,
                size: this.size,
                description: this.description,
                cost_price:  this.cost_price,
                selling_price: this.selling_price,
                quantity: this.quantity
            }).then(()=> {
                let alert = {type: 'success',title: 'Success!',msg: 'Product has been added.'}
                $state.go($state.current, {alerts: alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.currrent, {alerts: alert})
            })
        }
    }

    $onInit(){
    }
}

export const ProductAddComponent = {
    templateUrl: './views/app/components/product-add/product-add.component.html',
    controller: ProductAddController,
    controllerAs: 'vm',
    bindings: {}
}
