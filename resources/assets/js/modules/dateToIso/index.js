angular
    .module('simple.dateToIso', [])
    .filter('dateToIso', function() {
        return function(input) {
            return new Date(input).toISOString();
        };
    })
