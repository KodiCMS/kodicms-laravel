/**
 * Texts preview plugin
 *
 * @param elFinder.commands.quicklook
 **/
elFinder.prototype.commands.quicklook.plugins.push(function (ql) {
	var fm = ql.fm,
		mimes = fm.res('mimes', 'text'),
		preview = ql.preview;

	preview.bind('update', function (e) {
		var file = e.file,
			mime = file.mime,
			jqxhr;

		if (mime.indexOf('text/') === 0 || $.inArray(mime, mimes) !== -1) {
			e.stopImmediatePropagation();

			// stop loading on change file if not loadin yet
			preview.one('change', function () {
				jqxhr.state() == 'pending' && jqxhr.reject();
			});

			jqxhr = fm.request({
				data: {cmd: 'get', target: file.hash},
				preventDefault: true
			})
				.done(function (data) {
					ql.hideinfo();
					$('<div class="elfinder-quicklook-preview-text-wrapper">' +
						'<textarea id="elfinder-quicklook-preview-text" data-readonly="on" data-height="200">' + fm.escape(data.content) + '</textarea>' +
					'</div>')
						.appendTo(preview);

					$('#elfinder-quicklook-preview-text').on('filter:switch:on', function (e, editor) {
						$('.elfinder-quicklook-preview-text-wrapper').setHeightFor('#elfinder-quicklook-preview-textDiv', {
							updateOnResize: true,
							offset: 0,
							minHeight: 200,
							onCalculate: function (a, h) {
								CMS.filters.exec('elfinder-quicklook-preview-text', 'changeHeight', h);
							},
							onResize: function (a, h) {
								CMS.filters.exec('elfinder-quicklook-preview-text', 'changeHeight', h);
							}
						});
					});

					CMS.filters.switchOn('elfinder-quicklook-preview-text', 'ace', {mime: mime});
				});
		}

		ql.preview.on('changesize', function () {
			$('.elfinder-quicklook-preview-text-wrapper').trigger('resize');
		});
	});
});