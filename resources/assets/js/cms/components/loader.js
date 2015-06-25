CMS.loader = {
	counter: 0,
	getLastId: function() {
		return this.counter;
	},
	init: function (container, message) {
		if (container !== undefined && !(container instanceof jQuery)) {
			container = $(container);
		}
		else if (container === undefined) {
			container = $('body');
		}

		++this.counter;

		var $loader = $('<div class="_loader_container"><span class="_loader_preloader" /></div>');

		if(message !== undefined) {
			if(message instanceof jQuery)
				$loader.append(message);
			else
				$loader.append('<span class="_loader_message">' + message + '</span>');
		}

		return $loader
			.appendTo(container)
			.css({
				width: container.outerWidth(true),
				height: container.outerHeight(true),
				top: container.offset().top - $(window).scrollTop(),
				left: container.offset().left - $(window).scrollLeft()
			})
			.prop('id', 'loader' + this.getLastId());
	},
	show: function (container, message, speed) {
		var speed = speed || 500;

		this.init(container, message).fadeTo(speed, 0.7);
		return this.counter;
	},
	hide: function (id) {
		if (!id)
			cont = $('._loader_container');
		else
			cont = $('#loader' + id);

		cont.stop().fadeOut(400, function() {
			$(this).remove();
		});
	}
}