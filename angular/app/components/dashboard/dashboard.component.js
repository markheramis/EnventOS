class DashboardController {
  constructor ($scope, $state, API, AclService) {
    'ngInject'

    this.API = API
    this.$state = $state
    this.$scope = $scope
    this.AclService = AclService

    $scope.onClick = function () {}

    $scope.pieLabels = ['Download Sales', 'In-Store Sales', 'Mail-Order Sales']
    $scope.pieData = [300, 500, 100]

    this.recap_labels = []
    this.recap_series = ['Profit', 'Revenue','Cost']
    this.recap_data   = [[],[],[]]

    this.total_profit = 0
    this.total_revenue = 0
    this.total_cost = 0
    this.recap_start = ""
    this.recap_end = ""

    this.order_count = 0
    this.purchase_count = 0
    this.customer_count = 0
    this.item_count = 0
    /*
     * @todo need to change color for the recap graph
     *  profit should be green
     *  revenue should be blue
     *  cost should be red
     * No internet for now, I can't research the manuals #sad
     */

    let Inventory = API.service('recap',API.all('inventory'))
    Inventory.getList().then((response) => {
        response = response.plain()
        this.recap_start = response[0].date.start.date
        this.recap_end = response[6].date.end.date

        response.forEach((period) => {
            this.recap_labels.push(period.date.start.month)
            this.recap_data[0].push(period.total_profit)
            this.recap_data[1].push(period.total_selling)
            this.recap_data[2].push(period.total_cost)

            this.total_profit += period.total_profit
            this.total_revenue += period.total_selling
            this.total_cost += period.total_cost
        })
    })
    Inventory = API.service('inventory')
    Inventory.getList({
        sales: true
    }).then((response) => {
        this.recent_sales = response.plain()
    })


    let Orders = this.API.service('orders')
    Orders.getList().then((response) => {
        this.orders = response.plain()
    })
    let Purchase = this.API.service('purchases')
    Purchase.getList().then((response) => {
        this.purchases = response.plain()
    })

    Orders.one('status-count').get().then((response) => {
        response = response.plain()
        console.log(response.data.complete)
        $scope.pieData[0] = response.data.complete
        $scope.pieData[1] = response.data.delivering
        $scope.pieData[2] = response.data.processing
        $scope.pieData[3] = response.data.cancelled
    })
    Orders.one('count').get().then((response) => {
        this.order_count = response.plain().data
    })
    API.service('customers').one('count').get().then((response) => {
        this.customer_count = response.plain().data
    })
    API.service('items').one('count').get().then((response) => {
        this.item_count = response.plain().data
    })
    API.service('purchases').one('count').get().then((response) => {
        this.purchase_count = response.plain().data
    })
  }
}

export const DashboardComponent = {
  templateUrl: './views/app/components/dashboard/dashboard.component.html',
  controller: DashboardController,
  controllerAs: 'vm',
  bindings: {}
}
