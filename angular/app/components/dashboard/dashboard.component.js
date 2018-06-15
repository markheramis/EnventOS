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
    /*
     * @todo need to change color for the recap graph
     *  profit should be green
     *  revenue should be blue
     *  cost should be red
     * No internet for now, I can't research the manuals #sad
     */

    this.getMonthlyRecap()
  }

  getMonthlyRecap(){
    let $scope = this.$scope
    let API = this.API
    let Inventory = API.service('recap',API.all('inventory'))
    Inventory.getList().then((response) => {
        response = response.plain()
        console.log(response)

        response.forEach((period) => {
            this.recap_labels.push(period.date.start.month)
            this.recap_data[0].push(period.total_profit)
            this.recap_data[1].push(period.total_selling)
            this.recap_data[2].push(period.total_cost)
        })
    })
  }
}

export const DashboardComponent = {
  templateUrl: './views/app/components/dashboard/dashboard.component.html',
  controller: DashboardController,
  controllerAs: 'vm',
  bindings: {}
}
