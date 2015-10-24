/**
 * Flash preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var fm = ql.fm,
		mime = 'application/x-shockwave-flash',
		preview = ql.preview,
		active = false;

	$.each(navigator.plugins, function (i, plugins) {
		$.each(plugins, function (i, plugin) {
			if (plugin.type == mime) {
				return !(active = true);
			}
		});
	});

	active && preview.bind('update', function (e) {
		var file = e.file,
			node;

		if (file.mime == mime) {
			e.stopImmediatePropagation();
			ql.hideinfo();
			preview.append((node = $('<embed class="elfinder-quicklook-preview-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" src="' + fm.url(file.hash) + '" quality="high" type="application/x-shockwave-flash" />')));
		}
	});
});