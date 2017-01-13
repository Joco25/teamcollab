module.exports = class CardList {
    constructor() {
        this.scope = {
            data: '='
        }

        this.controllerAs = 'ctrl'
    }

    controller($state, $http, $rootScope, CardListFiltersService, CardCacherService) {
        ctrl = this
        ctrl.appliedFilters = bind(this.appliedFilters, this)
        ctrl.authUser = $rootScope.authUser
        ctrl.filters = {
            tag: null,
            assignedTo: null,
            quick: null
        }
        ctrl.stage = $scope.data
        ctrl.searchInput = ''

        $rootScope.$on('filters:update', function(evt, data) {
            ctrl.filters = data
        })

        $rootScope.$on('search:update', function(evt, data) {
            ctrl.searchInput = data
        })

        ctrl.appliedFilters = function(card) {
            CardListFiltersService.check(ctrl.filters, ctrl.authUser, card)
        }
    }
}
