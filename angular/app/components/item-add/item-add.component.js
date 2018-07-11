class ItemAddController{
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
            let items = this.API.service('item',this.API.all('items'))
            let $state = this.$state
            items.post({
                item_code: this.item_code,
                item_name: this.item_name,
                size: this.size,
                description: this.description,
                cost_price:  this.cost_price,
                selling_price: this.selling_price,
                quantity: this.quantity
            }).then(()=> {
                let alert = {type: 'success',title: 'Success!',msg: 'Item has been added.'}
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

export const ItemAddComponent = {
    templateUrl: './views/app/components/item-add/item-add.component.html',
    controller: ItemAddController,
    controllerAs: 'vm',
    bindings: {}
}
