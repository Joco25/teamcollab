angular
    .module('simple.team.mediaComment', [])

	.factory('Parser', [function() {
		var self = {},
			youtubeCache = {},
			youtubeIdRegex = /(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/i,
			youtubeLinkRegex = /(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/ig,
			bbYoutubeRegex = /\[youtube\](.*?)\[\/youtube\]/g,
			linkRegex = /(http|https|ftp|ftps|mailto)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?/ig,
			bbLinkRegex = /\[link\](.*?)\[\/link\]/g,
			imageRegex = /(https?:\/\/.*\.(?:png|jpg|jpeg|gif))/ig,
			bbImageRegex = /\[img\](.*?)\[\/img\]/g

		self.linksToBB = function(value) {
			return value.replace(linkRegex, function(link) {
				if (! link.match(imageRegex) && link.indexOf('youtube') === -1) {
					return '[link]' + link + '[/link]'
				}

				return link
			})
		}

		self.imagesToBB = function(value) {
			return value.replace(linkRegex, function(link) {
				if (link.match(imageRegex)) {
					return '[img]' + link + '[/img]'
				}

				return link
			})
		}

		self.youtubesToBB = function(value) {
			return String(value).replace(youtubeLinkRegex, function(link) {
				return '[youtube]' + link + '[/youtube]'
			})
		}

		self.toBB = function(value) {
			value = self.youtubesToBB(value)
			value = self.linksToBB(value)
			value = self.imagesToBB(value)

			return value
		}

		self.linksToDisplay = function(value) {
			return value.replace(bbLinkRegex, '<a class="media-link" target="_blank" href="$1">$1</a>')
		}

		self.imagesToDisplay = function(value) {
			return value.replace(bbImageRegex, function(img) {
				img = img.replace('[img]', '')
				img = img.replace('[/img]', '')

				return '<a class="media-image" target="_blank" href="' + img + '"><img style="max-width: 400px; max-height: 400px" src="' + img + '"></a>'
			})
		}

		self.youtubesToDisplay = function(value) {
			return value.replace(bbYoutubeRegex, function(link) {
				link = link.replace('[youtube]', '')
				link = link.replace('[/youtube]', '')

				var videoId = link.match(youtubeIdRegex)

				if (videoId[1] === undefined) {
					return link
				}

                var videoId = videoId[1]

                var text = `<p style="margin: 0 0 6px"><a href="https://youtube.com/watch?v=${videoId}" target="_blank">${link}</a></p><iframe width="420" height="235" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`

				return text
			})
		}

		self.createYoutubePreview = function(data, videoId) {
            `
            <a href="https://youtube.com/watch?v=${videoId}" target="_blank"></a>
			<iframe width="420" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
            `
		}

        self.nl2br = function (value) {
			if (!value) {
				return value
			}

			value = value + ''

			return value.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2')
        }

		self.toDisplay = function(value) {
			value = self.youtubesToDisplay(value)
			value = self.linksToDisplay(value)
			value = self.imagesToDisplay(value)

			return value
		}

		self.render = function(value) {
			value = self.toBB(value)
			value = self.toDisplay(value)

			return self.nl2br(value)
		}

		return self
	}])

	.directive('mediaComment', ['Parser', function(Parser){
		return {
			scope: {
				ngModel: '='
			},
			link: function (scope, element) {
				var value = scope.ngModel
				value = Parser.render(value)
				element.html(value)
			}
		}
	}])
