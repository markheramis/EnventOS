class SalesEditController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.API = API
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []

        this.items = []
        this.saleItems = []
        this.payment_amount = 0.00
        this.cost_price = 0.00
        this.selling_price = 0.00
        this.amound_due = 0.00
        if($stateParams.alerts) this.alerts.push($stateParams.alerts)

        let Items = this.API.service('items')
        Items.getList().then((response) => {
            this.items = response.plain()
        })

        let Customers = this.API.service('customers')
        Customers.getList().then((response) => {
            this.customers = response.plain()
        })

        let Sales = this.API.service('sales',API.all('sales'))
        Sales.one($stateParams.salesId).get().then((response) => {
            this.salesData          = API.copy(response)
            response                = response.plain()
            this.payment_type       = response.data.payment_type
            this.customer_id        = response.data.customer_id
            this.payment_amount     = Number.parseFloat(response.data.payment_amount)
            this.comments           = response.data.comments

            let saleItems = response.data.items
            saleItems.forEach((saleItem) => {
                let itemIndex = this.searchItem(saleItem.item_id)
                let item = this.items[itemIndex]
                this.addTosaleItems(item)
                item = this.saleItems[this.searchsaleItems(item.id)]
                item.quantity = saleItem.quantity
                item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
                item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            })


            this.updateTotal()
        })
    }

    searchItem(id) {
        let result = -1
        this.items.findIndex((current,index) => {
            if(current.id == id) result = index
        })
        return result
    }

    addTosaleItems(item) {
        let index = this.searchsaleItems(item.id)
        if(index == -1) {
            item.quantity = 1
            item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
            item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            this.saleItems.push(item)
        } else {
            let current = this.saleItems[index]
            current.quantity++
            current.total_cost_price = (current.quantity * Number.parseFloat(current.cost_price)).toFixed(2)
            current.total_selling_price = (current.quantity * Number.parseFloat(current.selling_price)).toFixed(2)
        }
        this.updateTotal()
    }

    searchsaleItems(searchId) {
        let result = -1
        this.saleItems.findIndex((current,index) => {
            if(current.id == searchId) result = index
        })
        return result
    }

    deleteFromsaleItems(id) {
        let index = this.searchsaleItems(id)
        this.saleItems.splice(index,1)
        this.updateTotal()
    }

    updatesaleItemsQuantity(id,amount) {
        let index = this.searchsaleItems(id)
        this.saleItems[index].quantity = this.saleItems[index].quantity + amount
        if(this.saleItems[index].quantity <= 0){
            this.deleteFromsaleItems(this.saleItems[index].id)
        } else {
            this.saleItems[index].total_cost_price = (this.saleItems[index].quantity * Number.parseFloat(this.saleItems[index].cost_price)).toFixed(2)
            this.saleItems[index].total_selling_price = (this.saleItems[index].quantity * Number.parseFloat(this.saleItems[index].selling_price)).toFixed(2)
        }
        this.updateTotal()
    }

    canAddQuantity(id) {
        let index = this.searchsaleItems(id)
        if(index == -1)
            return true
        else{
            let item = this.saleItems[index]
            if(item.on_hand > item.quantity) return true
        }
    }

    updateTotal() {
        let cost_price = 0
        let selling_price = 0
        this.saleItems.forEach((item) => {
            cost_price = cost_price + Number.parseFloat(item.total_cost_price)
            selling_price = selling_price + Number.parseFloat(item.total_selling_price)
        })
        this.cost_price = cost_price.toFixed(2)
        this.selling_price = selling_price.toFixed(2)
        this.updateAmountDue()
    }

    updateAmountDue() {
        if(this.payment_amount == null) this.payment = 0.00
        let amount = this.payment_amount - this.selling_price
        // this.amount_due = amount.toFixed(2) // problematic, throws error if number is negative, cannot format to x.xx or -x.xx
        this.amount_due = amount // tmp
    }

    save(isValid) {
        if(isValid){
            let $state = this.$state
            this.salesData.data.customer_id = this.customer_id
            this.salesData.data.cost_price = this.cost_price
            this.salesData.data.selling_price = this.selling_price
            this.salesData.data.payment_amount = this.payment_amount
            this.salesData.data.payment_type = this.payment_type
            this.salesData.data.comments = this.comments
            this.salesData.data.items = this.saleItems

            this.salesData.put().then(() => {
                let alert = {type: 'success',title:'Success!',msg:'Sales has been updated.'}
                $state.go($state.current,{alerts:alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current, {alerts: alert})
            })
        }
    }

    $onInit() {
    }
}

export const SalesEditComponent = {
    templateUrl: './views/app/components/sales-edit/sales-edit.component.html',
    controller: SalesEditController,
    controllerAs: 'vm',
    bindings: {}
}
