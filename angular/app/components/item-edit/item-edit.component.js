class ItemEditController{
    constructor($state, $stateParams, API){
        'ngInject';
        this.$state = $state
        this.formSubmitted = false
        this.alerts = []
        if($stateParams.alerts)
        {
            this.alerts.push($stateParams.alerts)
        }

        let itemId = $stateParams.itemId
        let items = API.service('item',API.all('items'))
        items.one(itemId).get().then((response) => {
            this.itemData = API.copy(response)
        })

    }

    save(isValid){
        if(isValid){
            let $state = this.$state
            this.itemData.put().then(() => {
                let alert = {type: 'success', title: 'Success!', msg: 'Item has been updated.'}
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

export const ItemEditComponent = {
    templateUrl: './views/app/components/item-edit/item-edit.component.html',
    controller: ItemEditController,
    controllerAs: 'vm',
    bindings: {}
}
