/**
 * HTML preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var mimes = ['text/html', 'application/xhtml+xml'],
		preview = ql.preview,
		fm = ql.fm;

	preview.bind('update', function (e) {
		var file = e.file, jqxhr;

		if ($.inArray(file.mime, mimes) !== -1) {
			e.stopImmediatePropagation();

			// stop loading on change file if not loaded yet
			preview.one('change', function () {
				jqxhr.state() == 'pending' && jqxhr.reject();
			});

			jqxhr = fm.request({
				data: {cmd: 'get', target: file.hash, current: file.phash},
				preventDefault: true
			})
				.done(function (data) {
					ql.hideinfo();
					doc = $('<iframe class="elfinder-quicklook-preview-html"/>').appendTo(preview)[0].contentWindow.document;
					doc.open();
					doc.write(data.content);
					doc.close();
				});
		}
	})
});