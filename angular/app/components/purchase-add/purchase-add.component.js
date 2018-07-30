class PurchaseAddController{
    constructor($scope, $state, $stateParams, API){
        'ngInject';
        this.API = API
        this.$state = $state
        this.alerts = []
        if($stateParams.alerts){
            this.alerts.push($stateParams.alerts)
        }
        this.products = []
        this.purchaseItems = []
        this.cost_price = 0.00
        this.selling_price = 0.00

        let Products = this.API.service('products')
        Products.getList({type: 1}).then((response) => {
            this.products = response.plain()
        })


        let Suppliers = this.API.service('suppliers')
        Suppliers.getList().then((response) => {
            this.suppliers = response.plain()
        })
    }

    addToPurchaseProducts(product){
        let index = this.searchPurchaseProducts(product.id)
        if(index == -1){
            product.quantity = 1
            product.total_cost_price = (product.quantity * Number.parseFloat(product.cost_price)).toFixed(2)
            this.purchaseItems.push(product)
        }else{
            let current = this.purchaseItems[index]
            current.quantity++
            current.total_cost_price = (current.quantity * Number.parseFloat(current.cost_price)).toFixed(2)
        }
        this.updateTotal()
    }

    searchPurchaseProducts(searchId){
        let result = -1
        /*
         * @problem: for some reason if the search is found the function still continues the loop
         * @todo: I should probably fix this somewhere in the future, it'd be problemtic if we loop through a large array.
         */
        this.purchaseItems.findIndex((current,index) => {
            if(current.id == searchId) result = index

        })
        return result
    }
    updateOnChange(id){
        let index = this.searchPurchaseProducts(id)
        let product = this.purchaseItems[index]
        if(product.quantity <= 0){
            this.deleteRecievingProducts(product.id)
        }else{
            product.total_cost_price = product.quantity * Number.parseFloat(product.cost_price)
        }
        this.updateTotal()
    }
    deleteRecievingProducts(id){
        let index = this.searchPurchaseProducts(id)
        this.purchaseItems.splice(index,1)
        this.updateTotal()
    }
    updateTotal(){
        let cost_price = 0
        this.purchaseItems.forEach((product) => {
            cost_price += Number.parseFloat(product.total_cost_price)
        })
        this.cost_price = cost_price.toFixed(2)
    }

    save(isValid){
        if(isValid){
            let purchases = this.API.service('purchase',this.API.all('purchases'))
            let $state = this.$state
            purchases.post({
                supplier_id     : this.supplier_id,
                payment_type    : this.payment_type,
                cost_price      : this.cost_price,
                Comments        : this.commments,
                purchaseItems  : this.purchaseItems
            }).then(() => {
                let alert = {
                    type    : 'success',
                    title   : 'Success!',
                    msg     : 'Purchase has been successfully saved'
                }
                $state.go($state.current, {alerts: alert})
            }, (response) => {
                let alert = {
                    type    : 'error',
                    title   : 'Error!',
                    msg     : response.data.message
                }
                $state.go($state.current, {alerts: alert})
            })
        }
    }

    $onInit(){
    }
}

export const PurchaseAddComponent = {
    templateUrl: './views/app/components/purchase-add/purchase-add.component.html',
    controller: PurchaseAddController,
    controllerAs: 'vm',
    bindings: {}
}
