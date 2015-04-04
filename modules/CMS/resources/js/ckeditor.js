$(function() {
	$('body').on('before_cms_init', function (){
		var $ckeditor = {};

		CKEDITOR.disableAutoInline = true;
		CKEDITOR.config.extraPlugins = 'images-browser';
		CKEDITOR.config.simpleImageBrowserURL = '/api-media.images';

		$ckeditor.switchOn_handler = function (textarea_id, params) {
			params = $.extend({
				skin: 'bootstrapck',
				filebrowserBrowseUrl: '/backend/elfinder/',
				height: 200
			}, params);
			var editor = CKEDITOR.replace(textarea_id, params);
			return editor;
		};

		$ckeditor.switchOff_handler = function (editor, textarea_id){
			editor.destroy()
		}

		$ckeditor.exec_handler = function (editor, command, textarea_id, data){
			switch (command) {
				case 'insert':
					editor.insertText(data);
					break;
				case 'changeHeight':
					editor.resize('100%', data);
			}
		}

		cms.filters.add('ckeditor', $ckeditor.switchOn_handler, $ckeditor.switchOff_handler, $ckeditor.exec_handler);
	});
});