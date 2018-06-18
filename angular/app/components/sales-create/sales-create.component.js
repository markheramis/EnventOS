class SalesCreateController{
    constructor($scope, $state, $stateParams, API){
        'ngInject';
        this.API = API
        this.$state = $state
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }

        this.items = []
        this.saleItems = []
        this.payment_amount = 0.00
        this.cost_price = 0.00
        this.selling_price = 0.00
        this.amount_due = 0.00


        let Items = this.API.service('items')
        Items.getList().then((response) => {
            this.items = response.plain()
        })

        let Customers = this.API.service('customers')
        Customers.getList().then((response) => {
            this.customers = response.plain()
        })
    }

    addTosaleItems(item)
    {
        let index = this.searchsaleItems(item.id)
        if(index == -1)
        {
            item.quantity = 1
            item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
            item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            this.saleItems.push(item)
        }
        else
        {
            this.saleItems[index].quantity++
            this.saleItems[index].total_cost_price = (this.saleItems[index].quantity * Number.parseFloat(this.saleItems[index].cost_price)).toFixed(2)
            this.saleItems[index].total_selling_price = (this.saleItems[index].quantity * Number.parseFloat(this.saleItems[index].selling_price)).toFixed(2)
        }

        this.updateTotal()
    }
    searchsaleItems(searchId)
    {
        let result = -1
        /*
         * @problem: for some reason if the search is found the function still continues the loop
         * @todo: I should probably fix this somewhere in the future, it'd be problemtic if we loop through a large array.
         */
        this.saleItems.findIndex((current,index) => {
            if(current.id == searchId)
            {
                result = index
            }
        })
        return result
    }

    deleteFromsaleItems(id)
    {
        let index = this.searchsaleItems(id)
        this.saleItems.splice(index,1)
        this.updateTotal()
    }

    updatesaleItemsQuantity(id,amount)
    {
        let index = this.searchsaleItems(id)
        this.saleItems[index].quantity = this.saleItems[index].quantity + amount
        if(this.saleItems[index].quantity <= 0)
        {
            this.deleteFromsaleItems(this.saleItems[index].id)
        }
        else
        {
            this.saleItems[index].total_cost_price = (this.saleItems[index].quantity * Number.parseFloat(this.saleItems[index].cost_price)).toFixed(2)
            this.saleItems[index].total_selling_price = (this.saleItems[index].quantity * Number.parseFloat(this.saleItems[index].selling_price)).toFixed(2)
        }
        this.updateTotal()
    }
    canAddQuantity(id)
    {
        let index = this.searchsaleItems(id)
        if(index == -1)
        {
            return true
        }
        else
        {
            let item = this.saleItems[index]
            if(item.on_hand > item.quantity)
            {
                return true
            }
        }
    }
    updateTotal()
    {
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
    updateAmountDue()
    {
        if(this.payment_amount == null) this.payment = 0.00
        let amount = this.payment_amount - this.selling_price
        // this.amount_due = amount.toFixed(2) // problematic, throws error if number is negative, cannot format to x.xx or -x.xx
        this.amount_due = amount // tmp
    }
    save(isValid)
    {
        if(isValid)
        {
            let sales = this.API.service('sales',this.API.all('sales'))
            let $state = this.$state
            sales.post({
                customer_id     : this.customer_id,
                payment_type    : this.payment_type,
                cost_price      : this.cost_price,
                selling_price   : this.selling_price,
                payment_amount  : this.payment_amount,
                status          : this.status,
                comments        : this.comments,
                saleItems       : this.saleItems
            }).then(() => {
                let alert = {
                    type:'success',
                    title:'Success!',
                    msg:'Sales have been saved successfully'
                }
                $state.go($state.current,{alerts:alert})
            },(response) => {
                let alert = {
                    type:'error',
                    title: 'Error!',
                    msg: response.data.message
                }
                $state.go($state.current, {alerts:alert})
            })
        }
        else
        {
            this.formSubmitted = true
        }
    }

    $onInit(){
    }
}

export const SalesCreateComponent = {
    templateUrl: './views/app/components/sales-create/sales-create.component.html',
    controller: SalesCreateController,
    controllerAs: 'vm',
    bindings: {}
}
