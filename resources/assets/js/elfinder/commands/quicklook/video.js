/**
 * HTML5 video preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var preview = ql.preview,
		autoplay = !!ql.options['autoplay'],
		mimes = {
			'video/mp4': 'mp4',
			'video/x-m4v': 'mp4',
			'application/mp4': 'mp4',
			'video/ogg': 'ogg',
			'application/ogg': 'ogg',
			'video/webm': 'webm'
		},
		node;

	preview.bind('update', function (e) {
		var file = e.file,
			type = mimes[file.mime];

		if (ql.support.video[type]) {
			e.stopImmediatePropagation();

			ql.hideinfo();
			node = $('<video class="elfinder-quicklook-preview-video" controls preload="auto" autobuffer><source src="' + ql.fm.url(file.hash) + '" /></video>').appendTo(preview);
			autoplay && node[0].play();

		}
	}).bind('change', function () {
		if (node && node.parent().length) {
			node[0].pause();
			node.remove();
			node = null;
		}
	});
});