'use strict'
angular.module('simple.team.userData', []).service('UserDataService', [
  '$http', function($http) {
    this.loadUsers = function() {
      return $http.get('/api/team/users')
    }
  }
])

// ---
// generated by coffee-script 1.9.2
