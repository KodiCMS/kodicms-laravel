/**
 * HTML5 audio preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var preview = ql.preview,
		autoplay = !!ql.options['autoplay'],
		mimes = {
			'audio/mpeg': 'mp3',
			'audio/mpeg3': 'mp3',
			'audio/mp3': 'mp3',
			'audio/x-mpeg3': 'mp3',
			'audio/x-mp3': 'mp3',
			'audio/x-wav': 'wav',
			'audio/wav': 'wav',
			'audio/x-m4a': 'm4a',
			'audio/aac': 'm4a',
			'audio/mp4': 'm4a',
			'audio/x-mp4': 'm4a',
			'audio/ogg': 'ogg'
		},
		node;

	preview.bind('update', function (e) {
		var file = e.file,
			type = mimes[file.mime];

		if (ql.support.audio[type]) {
			e.stopImmediatePropagation();

			node = $('<audio class="elfinder-quicklook-preview-audio" controls preload="auto" autobuffer><source src="' + ql.fm.url(file.hash) + '" /></audio>')
				.appendTo(preview);
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