module.exports = class CardListItem {
    constructor() {
        this.scope = {
          data: '='
        }
        this.controllerAs = 'ctrl'
        this.template = require('./views/cardListItem.html')
    }

    controller($scope, $rootScope) {
        this.card = $scope.data
        this.s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
    }
}
