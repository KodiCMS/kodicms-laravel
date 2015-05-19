@section('scripts')
<script type="text/javascript">
	function elfinderInit(params) {
		var params = $.extend({
			lang: '{{ Lang::getLocale() }}',
			url : '/api.filemanager',
			height: CMS.content_height - 20,
			resizable: false,
			uiOptions: {
				toolbar : [
					[@if(acl_check('filemanager.mkdir'))'mkdir'@endif, @if(acl_check('filemanager.upload'))'upload'@endif],
					['open', 'download'],
					['info'],
					['quicklook'],
					@if(acl_check('filemanager.edit'))['copy', 'cut', 'paste'],@endif
					@if(acl_check('filemanager.delete'))['rm'],@endif
					@if(acl_check('filemanager.edit'))['duplicate', 'rename', 'edit', 'resize'],@endif
					@if(acl_check('filemanager.edit'))['extract', 'archive'],@endif
					['search'],
					['view']
				]
			}
			@if (!acl_check('filemanager.edit')),contextmenu: false @endif
			@if (!acl_check('filemanager.upload')),dragUploadAllow: false @endif
			@if (!acl_check('filemanager.edit')),allowShortcuts : false @endif
		}, params);

		var elfinder = $('body').elfinder(params).elfinder('instance');

		$(window).resize(function() {
			var node = elfinder.getUI('node');
			var h = CMS.content_height - 20;
			node.height(h);
			node.find('.elfinder-navbar')
				.add(node.find('.elfinder-workzone'))
				.add(node.find('.elfinder-cwd'))
				.add(node.find('.elfinder-cwd-wrapper'))
				.height(h - node.find('.elfinder-toolbar').height() - node.find('.elfinder-statusbar').height() )
		});

		return elfinder;
	};

	$(function() {
		if($.query.get('CKEditor').length > 0) {
			var elfinder = elfinderInit({
				getFileCallback: function(file) {
					var funcNum = $.query.get('CKEditorFuncNum');
					window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
					window.close();
				}
			});
		}
	});
</script>
@stop