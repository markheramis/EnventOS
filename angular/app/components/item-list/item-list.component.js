class ItemListController{
    constructor($scope, $state, $compile, DTOptionsBuilder, DTColumnBuilder, API, AclService){
        'ngInject';
        this.API = API
        this.$state = $state
        this.AclService = AclService


        let Items = this.API.service('items')
        Items.getList({
            type: 1
        }).then((response) => {
            let dataSet = response.plain()

            this.dtOptions = DTOptionsBuilder
            .newOptions()
            .withOption('data',dataSet)
            .withOption('createdRow',createdRow)
            .withOption('responsive',true)
            .withBootstrap()
            this.dtColumns = [
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn('item_code').withTitle('Code'),
                DTColumnBuilder.newColumn('item_name').withTitle('Name'),
                DTColumnBuilder.newColumn('cost_price').withTitle('Cost Price'),
                DTColumnBuilder.newColumn('selling_price').withTitle('Selling Price'),
                DTColumnBuilder.newColumn('on_hand').withTitle('On Hand'),
                DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable().renderWith(actionsHtml)
            ]

            this.displayTable = true
        })

        let createdRow = (row) => {
            $compile(angular.element(row).contents())($scope)
        }

        let actionsHtml = (data) => {
            var buttons = ``
            buttons += `
            <a class="btn btn-xs btn-warning" ui-sref="app.item-edit({itemId: ${data.id}})">
                <i class="fa fa-edit"></i>
            </a>
            &nbsp
            `
            buttons += `
            <a class="btn btn-xs btn-danger" ng-click="vm.delete(${data.id})">
              <i class="fa fa-trash-o"></i>
            </a>
            `
            return buttons
        }
    }

    delete(itemId)
    {
        let API = this.API
        let $state = this.$state
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this data!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            html: false
        }, function () {
            API.one('items').one('item', itemId).remove().then(() => {
                swal({
                    title: 'Deleted!',
                    text: 'User Permission has been deleted.',
                    type: 'success',
                    confirmButtonText: 'OK',
                    closeOnConfirm: true
                }, function () {
                    $state.reload()
                })
            })
        })
    }

    $onInit(){
    }
}

export const ItemListComponent = {
    templateUrl: './views/app/components/item-list/item-list.component.html',
    controller: ItemListController,
    controllerAs: 'vm',
    bindings: {}
}
