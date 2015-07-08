/**
 * PDF preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var fm = ql.fm,
		mime = 'application/pdf',
		preview = ql.preview,
		active = false;

	if ((fm.UA.Safari && fm.OS == 'mac') || fm.UA.IE) {
		active = true;
	} else {
		$.each(navigator.plugins, function (i, plugins) {
			$.each(plugins, function (i, plugin) {
				if (plugin.type == mime) {
					return !(active = true);
				}
			});
		});
	}

	active && preview.bind('update', function (e) {
		var file = e.file, node;

		if (file.mime == mime) {
			e.stopImmediatePropagation();
			preview.one('change', function () {
				node.unbind('load').remove();
			});

			node = $('<iframe class="elfinder-quicklook-preview-pdf"/>')
				.hide()
				.appendTo(preview)
				.load(function () {
					ql.hideinfo();
					node.show();
				})
				.attr('src', fm.url(file.hash));
		}
	})
});