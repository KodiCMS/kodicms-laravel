/**
 * Images preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var mimes = ['image/jpeg', 'image/png', 'image/gif'],
		preview = ql.preview;

	// what kind of images we can display
	$.each(navigator.mimeTypes, function (i, o) {
		var mime = o.type;

		if (mime.indexOf('image/') === 0 && $.inArray(mime, mimes)) {
			mimes.push(mime);
		}
	});

	preview.bind('update', function (e) {
		var file = e.file,
			img;

		if ($.inArray(file.mime, mimes) !== -1) {
			// this is our file - stop event propagation
			e.stopImmediatePropagation();

			img = $('<img/>')
				.hide()
				.appendTo(preview)
				.load(function () {
					// timeout - because of strange safari bug -
					// sometimes cant get image height 0_o
					setTimeout(function () {
						var prop = (img.width() / img.height()).toFixed(2);
						preview.bind('changesize', function () {
							var pw = parseInt(preview.width()),
								ph = parseInt(preview.height()),
								w, h;

							if (prop < (pw / ph).toFixed(2)) {
								h = ph;
								w = Math.floor(h * prop);
							} else {
								w = pw;
								h = Math.floor(w / prop);
							}
							img.width(w).height(h).css('margin-top', h < ph ? Math.floor((ph - h) / 2) : 0);

						})
							.trigger('changesize');

						// hide info/icon
						ql.hideinfo();
						//show image
						img.fadeIn(100);
					}, 1)
				})
				.attr('src', ql.fm.url(file.hash));
		}

	});
});