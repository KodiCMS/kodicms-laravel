<script type="text/javascript">
	function elfinderInit(params) {
		var params = $.extend({
			lang: '{{ Lang::getLocale() }}',
			url : '/api.filemanager',
			height: 590,
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

		return $('body').elfinder(params).elfinder('instance');
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

			$(window).resize(function() {
				var node = elfinder.getUI('node');
				var h = cms.content_height + 100;
				node.height(h);
				node.find('.elfinder-navbar')
						.add(node.find('.elfinder-cwd'))
						.add(node.find('.elfinder-cwd-wrapper'))
						.height(h - node.find('.elfinder-toolbar').height() - node.find('.elfinder-statusbar').height() )
			});
		}
	});
</script>