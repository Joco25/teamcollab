angular
	.module('simple.team.stopScroll', [])
	.directive('stopScroll', stopScroll)

function stopScroll() {
	return {
		link: function($scope, $element) {
			var scrollArea = $element.find('.stop-scroll-container')
			scrollArea.on('mousewheel', function(e) {
				scrollArea.scrollTop(scrollArea.scrollTop() - e.originalEvent.wheelDeltaY)
				return false
			})
		}
	}
}
