@section('scripts')
<script>
$(function() {
	var elfinder = $('#elfinder').elfinder({
		lang: '{{ Lang::getLocale() }}',
		url : '/api.filemanager',
		resizable: false,
		height: CMS.content_height - 55,
		uiOptions: {
			toolbar : [
				[
					@if (acl_check('filemanager.mkdir')) 'mkdir' @endif,
					@if (acl_check('filemanager.mkfile')) 'mkfile' @endif,
					@if (acl_check('filemanager.upload')) 'upload' @endif
				],
				['open', 'download'],
				['info'],
				['quicklook'],
				@if (acl_check('filemanager.edit'))['copy', 'cut', 'paste'],@endif
				@if (acl_check('filemanager.delete'))['rm'],<?php endif; ?>
				@if (acl_check('filemanager.edit'))['duplicate', 'rename', 'edit', 'resize'],@endif
				@if (acl_check('filemanager.edit'))['extract', 'archive'],@endif
				['search'],
				['view', 'sort']
			]
		}
		@if (!acl_check('filemanager.edit')),contextmenu: false @endif
		@if (!acl_check('filemanager.upload')),dragUploadAllow: false @endif
		@if (!acl_check('filemanager.edit')),allowShortcuts : false @endif
	}).elfinder('instance');

	$(window).resize(function() {
		var node = elfinder.getUI('node');
		var h = CMS.content_height - 55;

		node.find('.elfinder-navbar')
			.add(node.find('.elfinder-workzone'))
			.add(node.find('.elfinder-cwd'))
			.add(node.find('.elfinder-cwd-wrapper'))
			.height(h - node.find('.elfinder-toolbar').height() - node.find('.elfinder-statusbar').height())
	});
});
</script>
@stop

<div id="elfinder"></div>