CMS.ui.add('ace', function() {
	var $ace = {};

	function parseMimeMode(mime) {
		switch (mime)
		{
			case 'application/json':
				return 'json';
			case 'text/plain':
				return 'text';
			default:
				if(mime.indexOf('text/x-') === 0)
					return mime.substring(7);
				else if(mime.indexOf('text/') === 0)
					return mime.substring(5);
				else
					return 'text';
		}
	}

	$ace.switchOn_handler = function (textarea_id, params) {
		var editor_id = getSlug(textarea_id) + 'Div',
			$textarea = $('#' + textarea_id).hide(),
			mode;

		params = $.extend({
			height: 300
		}, $textarea.data(), params);


		mode = params.mode || 'php';

		if(_.has(params, 'mime'))
			mode = parseMimeMode(params.mime);

		var editArea = $('<div id="' + editor_id + '" />')
			.insertAfter($textarea)
			.css({
				height: params.height,
				fontSize: 14
			});

		var editor = ace.edit(editor_id);

		editor.$blockScrolling = Infinity;
		editor.setValue($textarea.val());

		editor.clearSelection();
		editor.getSession().setMode("ace/mode/" + mode);
		editor.getSession().setTabSize(4);
		editor.getSession().setUseSoftTabs(false);
		editor.getSession().setUseWrapMode(true);
		editor.getSession().on('change', function () {
			$textarea.val(editor.getSession().getValue());
		});

		editor.setTheme("ace/theme/" + ACE_THEME);

		if (_.propertyOf(params)('readonly')) {
			editor.setReadOnly(true);
		} else {
			editor.commands.addCommand({
				bindKey: {win: 'Ctrl-S', mac: 'Command-S'},
				exec: function (editor) {
					$('button[name="continue"]').click();
				}
			});
		}

		editor.commands.addCommand({
			name: 'Full-screen',
			bindKey: {win: 'Ctrl-Shift-F', mac: 'Command-Shift-F'},
			exec: function (editor) {
				FullScreen.toggle(editArea[0]);
			}
		});

		return editor;
	};

	$ace.switchOff_handler = function (editor, textarea_id){
		$('#' + getSlug(textarea_id) + 'Div').remove();
	}

	$ace.exec_handler = function (editor, command, textarea_id, data){
		var textarea_id = getSlug(textarea_id);

		switch (command) {
			case 'insert':
				editor.insert(data);
				break;
			case 'changeHeight':
				$('#' + textarea_id + 'Div')
					.css({
						height: data
					});

				editor.resize();
		}

		return true;
	}

	CMS.filters.add('ace', $ace.switchOn_handler, $ace.switchOff_handler, $ace.exec_handler);
});