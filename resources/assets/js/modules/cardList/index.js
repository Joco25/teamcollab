'use strict'

angular.module('simple.team.cardList', [])

register('simple.team.cardList')
    .directive('cardList', require('./directives/cardList.js'))
    .directive('cardListItem', require('./directives/cardListItem.js'))
    .service('CardListFiltersService', require('./services/cardList.filters.js'))
