angular.module('simple.team.redactor', [])

	// https://github.com/whatever-company/angular-redactor
	.directive("redactor", ['$timeout', function ($timeout) {
		return {
			restrict: 'A',
			require: 'ngModel',
			link: function ($scope, element, attrs, controller) {
				var instance,
					initialised = false

				// redactor
				instance = element.redactor({
					imageGetJson: '/api/internal/redactor/images',
					imageUpload: '/api/internal/redactor/image?resource=true',
					fileUpload: '/api/internal/redactor/file',
					plugins: ['fontcolor'],
					syncAfterCallback: function(html) {
						// view -> model
						if (initialised && controller.$viewValue !== html) {
							$timeout(function () {
								controller.$setViewValue(html)
							})
						}
					}
				}).redactor('getObject')

				// model -> view
				controller.$render = function () {
					instance.set(controller.$viewValue || "")
					initialised = true
				}

				// destroy
				$scope.$on('$destroy', function () {
					instance.destroy()
					instance = null
				})
			}
		}
	}])
