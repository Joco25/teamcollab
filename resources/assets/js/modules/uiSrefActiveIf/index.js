module.exports = angular
    .module('simple.team.uiSrefActiveIf', [])
    .directive('uiSrefActiveIf', function($state) {
        return {
            restrict: "A",
            controller: function ($scope, $element, $attrs) {
                var state = $attrs.uiSrefActiveIf;

                function update() {
                    if ( $state.includes(state) || $state.is(state) ) {
                        $element.addClass("active");
                    } else {
                        $element.removeClass("active");
                    }
                }

                $scope.$on('$stateChangeSuccess', update);
                update();
            }
        };
    })
