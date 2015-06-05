CMS.loader = {
	counter: 0,
		init: function (container) {
		container = $('body');

		return $('<div class="_loader_container"><span>' + __('Loading') + '</span></div>')
			.appendTo(container)
			.css({
				width: container.outerWidth(true),
				height: container.outerHeight(true),
				top: container.offset().top,
				left: container.offset().left
			})
			.prop('id', 'loader' + ++this.counter);
	},
	show: function (container, speed) {
		if(!speed) {
			speed = 500;
		}

		var loader = this.init(container).fadeTo(speed, 0.4);
		return this.counter;
	},
	hide: function (id) {
		if(!id)
			cont = $('._loader_container');
		else
			cont = $('#loader'+id);

		cont.stop().fadeOut(400, function() {
			$(this).remove();
		});
	}
}