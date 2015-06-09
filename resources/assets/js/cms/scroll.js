var Scroll = {
	add: function(element, params) {
		$(element).slimScroll(params)
	},
	addToWidget: function(widgetContainer, scrollContainer, elements) {
		var $widget = $(widgetContainer);
		var $scrollContainer = $(scrollContainer, $widget);

		function initScroll() {
			if(!elements)
				var elements = ['.panel-heading', '.panel-footer'];

			var height = $widget.innerHeight() - 5;

			for(i in elements)
				height = height - $widget.find(elements[i]).innerHeight();

			$scrollContainer.slimScroll({height: height});
		}

		function updateScroll() {
			$scrollContainer.slimScroll({destroy: true});
			initScroll();
		}

		$widget.on('resize_stop', function(e, gridster, ui) {
			updateScroll();
		});

		initScroll();
	}
}