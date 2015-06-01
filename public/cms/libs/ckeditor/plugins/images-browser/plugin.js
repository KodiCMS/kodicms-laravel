CKEDITOR.plugins.add('images-browser', {
	icons: 'images',
	init: function (editor) {
		editor.ui.addButton('Images', {
			label: 'Browse images',
			command: 'ShowImagesBrowser'
		});
		
		editor.addCommand('ShowImagesBrowser', new CKEDITOR.dialogCommand('imagesBrowserDialog'));
	}
});

CKEDITOR.tools.imagebrowserinsertpicture = function(img) {
	var c;
	a = CKEDITOR.currentInstance, 
	c = CKEDITOR.dialog.getCurrent(), 
	a.config.allowedContent = !0, 
	a.insertHtml(img.trim()), c.hide();
}

CKEDITOR.dialog.add('imagesBrowserDialog', function (editor) {
	return {
		title: 'Abbreviation Properties',
		minWidth: 600,
		minHeight: 400,
		onLoad: function() {
			$.getJSON('/api-media.images', $.proxy(function(data) {
				var folders = {};
				var z = 0;

				// folders
				$.each(data.response, $.proxy(function(key, val)
				{
					if (typeof val.folder !== 'undefined')
					{
						z++;
						folders[val.folder] = z;
					}

				}, this));

				var folderclass = false;
				$.each(data.response, $.proxy(function(key, val)
				{
					// title
					var thumbtitle = '';
					if (typeof val.title !== 'undefined')
					{
						thumbtitle = val.title;
					}

					var folderkey = 0;
					if (!$.isEmptyObject(folders) && typeof val.folder !== 'undefined')
					{
						folderkey = folders[val.folder];
						if (folderclass === false)
						{
							folderclass = '.images-folder-' + folderkey;
						}
					}

					var img = $('<span class="thumbnail"><img src="' + val.thumb + '" class="images-folder images-folder-' + folderkey + '" data-src="' + val.image + '" title="' + thumbtitle + '" data-id="'+val.id+'" /></span>');

					var cont = $('#images_box');
					$('<div class="col-md-3"></div>')
						.append(img)
						.appendTo(cont);


					img.on('click', function(e) {
						var img = $(e.target);
						CKEDITOR.tools.imagebrowserinsertpicture('<img src="' + img.data('src') + '" alt="' + img.prop('title') + '" />');
					});

				}, this));

				// folders
				if (!$.isEmptyObject(folders))
				{
					$('.images-folder').hide();
					$(folderclass).show();

					var onchangeFunc = function(e)
					{
						$('.images-folder').hide();
						$('.images-folder-' + $(e.target).val()).show();
					}

					var select = $('<select id="images_box_select" class="form-control">');
					$.each(folders, function(k,v)
					{
						select.append($('<option value="' + v + '">' + k + '</option>'));
					});
					$('#images_box').before(select);
					select.change(onchangeFunc);
				}

			}, this));
		},
		contents: [
//			{
//				id: 'tab-upload',
//				label: 'Upload',
//				elements: [{
//					type: 'html',
//					html: '<div id="myDiv">Sample <b>text</b>.</div><div id="otherId">Another div.</div>'
//				}]
//			}, 
			{
				id: 'tab-browse',
				label: 'Browse images',
				elements: [{
					type: 'html',
					html: '<form><div id="images_box" class="row"></div></form>'
				}]
			}
		]
	};
});