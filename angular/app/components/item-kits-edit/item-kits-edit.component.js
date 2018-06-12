class ItemKitsEditController{
    constructor($state, $stateParams, $compile, DTOptionsBuilder, DTColumnBuilder, API){
        'ngInject';
        this.API = API
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }

        this.items = null
        this.cart = []

        this.cost_price = 0
        this.selling_price = 0

        let Items = this.API.service('items')

        Items.getList().then((response) => {
            this.items = response.plain()
        })

        let itemId = $stateParams.itemId
        let Item = API.service('kit',API.all('items'))
        Item.one(itemId).get().then((response) => {
            this.itemData = this.API.copy(response)
        })

    }

    addToCart(item)
    {
        let index = this.searchCart(item.id)
        if(index == -1)
        {
            item.quantity = 1
            item.total_cost_price = item.quantity * item.cost_price
            item.total_selling_price = item.quantity * item.selling_price
            this.itemData.data.kitItems.push(item)
        }
        else
        {

            this.itemData.data.kitItems[index].quantity++
            this.itemData.data.kitItems[index].total_cost_price = this.itemData.data.kitItems[index].quantity * this.itemData.data.kitItems[index].cost_price
            this.itemData.data.kitItems[index].total_selling_price = this.itemData.data.kitItems[index].quantity * this.itemData.data.kitItems[index].selling_price
        }
        this.updateTotal()
    }

    searchCart(searchId)
    {
        let result = -1
        this.itemData.data.kitItems.findIndex((current,index) => {
            if(current.id == searchId)
            {
                // @note: dirty-walkaround
                // @challenge: for some reason the function/loop doesn't stop even when the searchId is already found
                result = index
            }
        })
        return result
    }

    deleteFromCart(id)
    {
        let index = this.searchCart(id)
        this.itemData.data.kitItems.splice(index,1)
        this.updateTotal()
    }

    updateCartquantity(id, amount)
    {
        let index = this.searchCart(id)
        this.itemData.data.kitItems[index].quantity = this.itemData.data.kitItems[index].quantity + amount
        if(this.itemData.data.kitItems[index].quantity <= 0)
        {
            this.deleteFromCart(this.itemData.data.kitItems[index].id)
        }
        else
        {
            let total_cost_price = this.itemData.data.kitItems[index].quantity * Number.parseFloat(this.itemData.data.kitItems[index].cost_price)
            let total_selling_price = this.itemData.data.kitItems[index].quantity * Number.parseFloat(this.itemData.data.kitItems[index].selling_price)
            this.itemData.data.kitItems[index].total_cost_price = total_cost_price.toFixed(2)
            this.itemData.data.kitItems[index].total_selling_price = total_selling_price.toFixed(2)
            this.updateTotal()
        }
    }

    updateTotal()
    {
        let cost_price = 0
        let selling_price = 0
        this.itemData.data.kitItems.forEach((item) => {
            cost_price = cost_price + Number.parseFloat(item.total_cost_price)
            selling_price = selling_price + Number.parseFloat(item.total_selling_price)
        })

        this.itemData.data.cost_price = cost_price.toFixed(2)
        this.itemData.data.selling_price = selling_price.toFixed(2)


    }

    save(isValid)
    {
        if(isValid)
        {
            let $state = this.$state
            this.itemData.put().then(() => {
                let alert = {type: 'success', title: 'Success!', msg: 'Item has been updated.'}
                $state.go($state.current,{alerts:alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current,{alerts: alert})
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

export const ItemKitsEditComponent = {
    templateUrl: './views/app/components/item-kits-edit/item-kits-edit.component.html',
    controller: ItemKitsEditController,
    controllerAs: 'vm',
    bindings: {}
}
