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

    Inventory.getList({
        receiving: true
    }).then((response) => {
        this.recent_receivings = response.plain()
    })

    let Sales = this.API.service('sales')
    Sales.getList({
        user: true,
        customer: true
    }).then((response) => {
        this.sales = response.plain()
    })
  }
}

export const DashboardComponent = {
  templateUrl: './views/app/components/dashboard/dashboard.component.html',
  controller: DashboardController,
  controllerAs: 'vm',
  bindings: {}
}
