class ProductEditController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }

        let productId = $stateParams.productId
        let products = API.service('product',API.all('products'))
        products.one(productId).get().then((response) => {
            this.productData = API.copy(response)
        })

    }

    save(isValid){
        if(isValid){
            let $state = this.$state
            this.productData.put().then(() => {
                let alert = {type: 'success', title: 'Success!', msg: 'Product has been updated.'}
                $state.go($state.current,{alerts:alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current,{alerts: alert})
            })
        }else{
            this.formSubmitted = true
        }
    }

    $onInit(){
    }
}

export const ProductEditComponent = {
    templateUrl: './views/app/components/product-edit/product-edit.component.html',
    controller: ProductEditController,
    controllerAs: 'vm',
    bindings: {}
}
