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

        let Items = this.API.service('items')
        Items.getList({type: 1}).then((response) => {
            this.items = response.plain()
        })

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
            this.amount_tendered    = Number.parseFloat(response.data.amount_tendered)
            this.comments           = response.data.comments

            response.data.items.forEach((purchaseItem) => {
                let itemIndex = this.searchItem(purchaseItem.item_id)
                let item = this.items[itemIndex]
                this.addToPurchaseItems(item)
                item = this.purchaseItems[this.searchPurchaseItems(item.id)]
                item.quantity = purchaseItem.quantity
                item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
                item.total_selling_price = (item.quantity   * Number.parseFloat(item.selling_price)).toFixed(2)
            })

            this.updateTotal()
        })
    }

    searchItem(id){
        let result = -1
        this.items.findIndex((current,index) => {
            if(current.id == id) result = index
        })
        return result
    }

    searchPurchaseItems(searchId){
        let result = -1
        this.purchaseItems.findIndex((current,index) => {
            if(current.id == searchId) result = index
        })
        return result
    }

    deleteFromPurchaseItems(id){
        let index = this.searchPurchaseItems(id)
        this.purchaseItems.splice(index,1)
        this.updateTotal()
    }

    updatePurchaseItemsQuantity(id,amount){
        let index = this.searchPurchaseItems(id)
        let item = this.purchaseItems[index]
        item.quantity = item.quantity + amount
        if(item.quantity <= 0){
            this.deleteRecievingItems(item.id)
        }else{
            item.total_cost_price = item.quantity * Number.parseFloat(item.cost_price)
            item.total_selling_price = item.quantity * Number.parseFloat(item.selling_price)
        }
        this.updateTotal()
    }

    addToPurchaseItems(item){
        let index = this.searchPurchaseItems(item.id)
        if (index == -1){
            item.quantity = 1
            item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
            item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            this.purchaseItems.push(item)
        }else{
            let current = this.purchaseItems[index]
            current.quantity++
            current.total_cost_price = (current.quantity * Number.parseFloat(current.cost_price)).toFixed(2)
            current.total_selling_price = (current.quantity * Number.parseFloat(current.selling_price)).toFixed(2)
        }
    }

    updateTotal(){
        let cost_price = 0
        let selling_price = 0
        this.purchaseItems.forEach((item) => {
            cost_price += Number.parseFloat(item.total_cost_price)
            selling_price += Number.parseFloat(item.total_selling_price)
        })
        this.cost_price = cost_price.toFixed(2)
        this.selling_price = selling_price.toFixed(2)
    }

    save(isValid){
        if(isValid){
            let $state = this.$state
            this.purchaseData.data.supplier_id = this.supplier_id
            this.purchaseData.data.cost_price = this.cost_price
            this.purchaseData.data.selling_price = this.selling_price
            this.purchaseData.data.amount_tendered = this.amount_tendered
            this.purchaseData.data.comments = this.comments
            this.purchaseData.data.items = this.purchaseItems

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
