class ReceivingEditController{
    constructor($state, $stateParams, API){
        'ngInject';

        this.API = API
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []

        this.items = []
        this.receivingItems = []
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

        let Receiving = API.service('receiving',API.all('receivings'))
        Receiving.one($stateParams.receivingId).get().then((response) => {
            this.receivingData      = API.copy(response)

            response                = response.plain()
            this.payment_type       = response.data.payment_type
            this.supplier_id        = response.data.supplier_id
            this.amount_tendered    = Number.parseFloat(response.data.amount_tendered)
            this.comments           = response.data.comments

            response.data.items.forEach((receivingItem) => {
                let itemIndex = this.searchItem(receivingItem.item_id)
                let item = this.items[itemIndex]
                this.addToReceivingItems(item)
                item = this.receivingItems[this.searchReceivingItems(item.id)]
                item.quantity = receivingItem.quantity
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

    searchReceivingItems(searchId){
        let result = -1
        this.receivingItems.findIndex((current,index) => {
            if(current.id == searchId) result = index
        })
        return result
    }

    deleteFromReceivingItems(id){
        let index = this.searchReceivingItems(id)
        this.receivingItems.splice(index,1)
        this.updateTotal()
    }

    updateReceivingItemsQuantity(id,amount){
        let index = this.searchReceivingItems(id)
        let item = this.receivingItems[index]
        item.quantity = item.quantity + amount
        if(item.quantity <= 0){
            this.deleteRecievingItems(item.id)
        }else{
            item.total_cost_price = item.quantity * Number.parseFloat(item.cost_price)
            item.total_selling_price = item.quantity * Number.parseFloat(item.selling_price)
        }
        this.updateTotal()
    }

    addToReceivingItems(item){
        let index = this.searchReceivingItems(item.id)
        if (index == -1){
            item.quantity = 1
            item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
            item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            this.receivingItems.push(item)
        }else{
            let current = this.receivingItems[index]
            current.quantity++
            current.total_cost_price = (current.quantity * Number.parseFloat(current.cost_price)).toFixed(2)
            current.total_selling_price = (current.quantity * Number.parseFloat(current.selling_price)).toFixed(2)
        }
    }

    updateTotal(){
        let cost_price = 0
        let selling_price = 0
        this.receivingItems.forEach((item) => {
            cost_price += Number.parseFloat(item.total_cost_price)
            selling_price += Number.parseFloat(item.total_selling_price)
        })
        this.cost_price = cost_price.toFixed(2)
        this.selling_price = selling_price.toFixed(2)
    }

    save(isValid){
        if(isValid){
            let $state = this.$state
            this.receivingData.data.supplier_id = this.supplier_id
            this.receivingData.data.cost_price = this.cost_price
            this.receivingData.data.selling_price = this.selling_price
            this.receivingData.data.amount_tendered = this.amount_tendered
            this.receivingData.data.comments = this.comments
            this.receivingData.data.items = this.receivingItems

            this.receivingData.put().then(() => {
                let alert = {type: 'success', title: 'Success!', msg: 'Receiving has been updated successfully'}
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

export const ReceivingEditComponent = {
    templateUrl: './views/app/components/receiving-edit/receiving-edit.component.html',
    controller: ReceivingEditController,
    controllerAs: 'vm',
    bindings: {}
}
