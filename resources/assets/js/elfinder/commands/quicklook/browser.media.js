/**
 * Audio/video preview plugin using browser plugins
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var preview = ql.preview,
		mimes = [],
		node;

	$.each(navigator.plugins, function (i, plugins) {
		$.each(plugins, function (i, plugin) {
			(plugin.type.indexOf('audio/') === 0 || plugin.type.indexOf('video/') === 0) && mimes.push(plugin.type);
		});
	});

	preview.bind('update', function (e) {
		var file = e.file,
			mime = file.mime,
			video;

		if ($.inArray(file.mime, mimes) !== -1) {
			e.stopImmediatePropagation();
			(video = mime.indexOf('video/') === 0) && ql.hideinfo();
			node = $('<embed src="' + ql.fm.url(file.hash) + '" type="' + mime + '" class="elfinder-quicklook-preview-' + (video ? 'video' : 'audio') + '"/>')
				.appendTo(preview);
		}
	}).bind('change', function () {
		if (node && node.parent().length) {
			node.remove();
			node = null;
		}
	});
});