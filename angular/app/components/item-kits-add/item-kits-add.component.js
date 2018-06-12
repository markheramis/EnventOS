class ItemKitsAddController{
    constructor($scope, $state, $stateParams, $compile, DTOptionsBuilder, DTColumnBuilder, API)
    {
        'ngInject';
        this.API = API
        this.$state = $state
        this.alerts = []

        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }


        this.items = null
        this.kitItems = []

        this.cost_price = 0
        this.selling_price = 0

        let Items = this.API.service('items')

        Items.getList().then((response) => {
            this.items = response.plain()
        })
    }

    addToCart(item)
    {
        let index = this.searchCart(item.id)
        if(index == -1){
            item.quantity = 1
            item.total_cost_price = (item.quantity * Number.parseFloat(item.cost_price)).toFixed(2)
            item.total_selling_price = (item.quantity * Number.parseFloat(item.selling_price)).toFixed(2)
            this.kitItems.push(item)
        }else{
            this.kitItems[index].quantity++
            this.kitItems[index].total_cost_price = (this.kitItems[index].quantity * Number.parseFloat(this.kitItems[index].cost_price)).toFixed(2)
            this.kitItems[index].total_selling_price = (this.kitItems[index].quantity * Number.parseFloat(this.kitItems[index].selling_price)).toFixed(2)
        }
        this.updateTotal()
    }

    searchCart(searchId)
    {
        let result = -1
        this.kitItems.findIndex((current, index) => {
            if(current.id == searchId){
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
        this.kitItems.splice(index,1)
        this.updateTotal()
    }

    updateCartquantity(id, amount)
    {
        let index = this.searchCart(id)
        this.kitItems[index].quantity = this.kitItems[index].quantity + amount

        if(this.kitItems[index].quantity <= 0)
        {
            this.deleteFromCart(this.kitItems[index].id);
        }
        else
        {
            this.kitItems[index].total_cost_price = (this.kitItems[index].quantity * Number.parseFloat(this.kitItems[index].cost_price)).toFixed(2)
            this.kitItems[index].total_selling_price = (this.kitItems[index].quantity * Number.parseFoat(this.kitItems[index].selling_price)).toFixed(2)
            this.updateTotal()
        }

    }

    updateTotal()
    {
        let cost_price = 0
        let selling_price = 0
        this.kitItems.forEach((item) => {
            cost_price = cost_price + Number.parseFloat(item.total_cost_price)
            selling_price = selling_price + Number.parseFloat(item.total_selling_price)
        })
        this.cost_price = cost_price.toFixed(2)
        this.selling_price = selling_price.toFixed(2)
    }

    save(isValid)
    {
        if(isValid)
        {
            let kit = this.API.service('kit',this.API.all('items'))
            let $state = this.$state

            kit.post({
                item_code: this.item_code,
                item_name: this.item_name,
                description: this.description,
                cost_price: this.cost_price,
                selling_price: this.selling_price,
                items: this.kitItems
            }).then(() => {
                let alert = {type: 'success', title: 'Success', msg: 'Item kit has been added.'}
                $state.go($state.current,{alerts: alert})
            },(response) => {
                let alert = {type: 'error', title: 'Error!', msg: response.data.message}
                $state.go($state.current,{alerts: alert})
            })
        }
    }

    $onInit(){
    }
}

export const ItemKitsAddComponent = {
    templateUrl: './views/app/components/item-kits-add/item-kits-add.component.html',
    controller: ItemKitsAddController,
    controllerAs: 'vm',
    bindings: {}
}
