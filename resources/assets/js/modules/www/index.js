module.exports = angular.module('simple.team.www', [])

.factory('$www', ['$http', function($http) {
	function encodeObject(obj) {
		var str = ''
		for(var p in obj) {
			if (obj[p] !== undefined) {
				switch(typeof obj[p]) {
					case 'boolean' :
						str += p + '=' + (obj[p] ? 'true' : 'false') + '&'
						break

					case 'number' :
					case 'string' :
						str += p + '=' + obj[p] + '&'
						break

					case 'object' :
						for (var i in obj[p]) {
							if (!obj[p][i]) {
								obj[p][i] = false
							}

							str += p + '[' + encodeURIComponent(i) + ']=' + encodeURIComponent(obj[p][i]) + '&'
						}
						break
				}
			}
		}

		return str
	}

	var self = {}

	self.get = function(url, data) {
		return $http.get(url + '?' + encodeObject(data))
	}

	self.post = function(url, data){
		return $http.post(url, data)
	}

	self.put = function(url, data){
		return $http.put(url, data)
	}

	self.delete = function(url, data){
		return $http.delete(url + '?' + encodeObject(data))
	}

	return self
}])
