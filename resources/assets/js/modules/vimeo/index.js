angular.module('simple.team.vimeo', [])

	.filter('vimeoIframe', [function() {
		return function (value) {
			if (!value) {
				return value
			}

			value = value.replace('https', '')
				.replace('http', '')
				.substring(13)

			return '//player.vimeo.com/video/' + value
		}
	}])
