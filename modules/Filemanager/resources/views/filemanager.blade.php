<script>
	$(function() {
		var elfinder = $('#elfinder').elfinder({
			lang: 'ru',
			url : '/api.filemanager',
			resizable: false,
			height: CMS.content_height,
			uiOptions: {
				toolbar : [
					[@if(acl_check('filemanager.mkdir')) 'mkdir' @endif, @if (acl_check('filemanager.upload')) 'upload' @endif],
					['open', 'download'],
					['info'],
					['quicklook'],
						@if (acl_check('filemanager.edit'))['copy', 'cut', 'paste'],@endif
						@if (acl_check('filemanager.delete'))['rm'],<?php endif; ?>
						@if (acl_check('filemanager.edit'))['duplicate', 'rename', 'edit', 'resize'],@endif
						@if (acl_check('filemanager.edit'))['extract', 'archive'],@endif
					['search'],
					['view']
				]
			}
			@if (!acl_check('filemanager.edit')),contextmenu: false @endif
			@if (!acl_check('filemanager.upload')),dragUploadAllow: false @endif
			@if (!acl_check('filemanager.edit')),allowShortcuts : false @endif
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