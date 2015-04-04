<script>
	$(function() {
		var elfinder = $('#elfinder').elfinder({
			lang: 'ru',
			url : Api.build_url('elfinder'),
			resizable: false,
			height: cms.content_height,
			uiOptions: {
				toolbar : [
					[@if(ACL::check('filemanager.mkdir')) 'mkdir' @endif, @if (ACL::check('filemanager.upload')) 'upload' @endif],
					['open', 'download'],
					['info'],
					['quicklook'],
						@if (ACL::check('filemanager.edit'))['copy', 'cut', 'paste'],@endif
						@if (ACL::check('filemanager.delete'))['rm'],<?php endif; ?>
						@if (ACL::check('filemanager.edit'))['duplicate', 'rename', 'edit', 'resize'],@endif
						@if (ACL::check('filemanager.edit'))['extract', 'archive'],@endif
					['search'],
					['view']
				]
			}
			@if (!ACL::check('filemanager.edit')),contextmenu: false @endif
			@if (!ACL::check('filemanager.upload')),dragUploadAllow: false @endif
			@if (!ACL::check('filemanager.edit')),allowShortcuts : false @endif
		}).elfinder('instance');

		$(window).resize(function() {
			var node = elfinder.getUI('node');
			var h = cms.content_height - 40;
			node.height(h);
			node.find('.elfinder-navbar')
					.add(node.find('.elfinder-cwd'))
					.add(node.find('.elfinder-cwd-wrapper'))
					.height(h - node.find('.elfinder-toolbar').height() - node.find('.elfinder-statusbar').height() )
		});
	});
</script>

<div id="elfinder"></div>