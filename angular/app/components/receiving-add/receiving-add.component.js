class ReceivingAddController{
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
        this.receivingItems = []
        this.amount_tendered = 0.00
        this.cost_price = 0.00
        this.selling_price = 0.00

        let Items = this.API.service('items')
        Items.getList({type: 1}).then((response) => {
            this.items = response.plain()
        })


        let Suppliers = this.API.service('suppliers')
        Suppliers.getList().then((response) => {
            this.suppliers = response.plain()
        })
    }

    addToReceivingItems(item)
    {
        let index = this.searchReceivingItems(item.id)
        if(index == -1)
        {
            item.quantity = 1
            item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
            item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            this.receivingItems.push(item)
        }
        else
        {
            let current = this.receivingItems[index]
            current.quantity++
            current.total_cost_price = (current.quantity * Number.parseFloat(current.cost_price)).toFixed(2)
            current.total_selling_price = (current.quantity * Number.parseFloat(current.selling_price)).toFixed(2)
        }

        this.updateTotal()
    }

    searchReceivingItems(searchId)
    {
        let result = -1
        /*
         * @problem: for some reason if the search is found the function still continues the loop
         * @todo: I should probably fix this somewhere in the future, it'd be problemtic if we loop through a large array.
         */
        this.receivingItems.findIndex((current,index) => {
            if(current.id == searchId) result = index

        })
        return result
    }

    updateReceivingItemsQuantity(id,amount)
    {
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
    deleteRecievingItems(id)
    {
        let index = this.searchReceivingItems(id)
        this.receivingItems.splice(index,1)
        this.updateTotal()
    }
    updateTotal()
    {
        let cost_price = 0
        let selling_price = 0
        this.receivingItems.forEach((item) => {
            cost_price += Number.parseFloat(item.total_cost_price)
            selling_price += Number.parseFloat(item.total_selling_price)
        })
        this.cost_price = cost_price.toFixed(2)
        this.selling_price = selling_price.toFixed(2)
    }

    save(isValid)
    {
        if(isValid)
        {
            let receivings = this.API.service('receiving',this.API.all('receivings'))
            let $state = this.$state
            receivings.post({
                supplier_id     : this.supplier_id,
                payment_type    : this.payment_type,
                cost_price      : this.cost_price,
                selling_price   : this.selling_price,
                amount_tendered : this.amount_tendered,
                Comments        : this.commments,
                receivingItems  : this.receivingItems
            }).then(() => {
                let alert = {
                    type    : 'success',
                    title   : 'Success!',
                    msg     : 'Receiving has been successfully saved'
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

export const ReceivingAddComponent = {
    templateUrl: './views/app/components/receiving-add/receiving-add.component.html',
    controller: ReceivingAddController,
    controllerAs: 'vm',
    bindings: {}
}
