'use strict'
module.exports = function($state, $stateParams, $modal) {
  var cardId, init
  cardId = $stateParams.cardId
  init = (function(_this) {
    return function() {
      return _this.openModal()
    }
  })(this)
  this.openModal = function(size) {
    var modalInstance
    modalInstance = $modal.open({
      template: require('../layouts/card.modal.html'),
      controller: require('./card.modal.ctrl.js'),
      controllerAs: 'ctrl',
      size: 'lg',
      resolve: {
        cardId: function() {
          return cardId
        }
      }
    })
    return modalInstance.result.then((function(_this) {
      return function(selectedItem) {
        return _this.closeEditCard()
      }
    })(this), (function(_this) {
      return function() {
        return _this.closeEditCard()
      }
    })(this))
  }
  this.closeEditCard = function() {
    if ($state.current.name.indexOf('projects') > -1) {
      return $state.go('projects')
    }
    return $state.go('tasklist')
  }
  init()
}
