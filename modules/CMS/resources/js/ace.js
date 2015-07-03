CMS.ui.add('ace', function() {
	var $ace = {};

	$ace.switchOn_handler = function (textarea_id, params) {
		var editor_id = getSlug(textarea_id) + 'Div';
		var textarea = $('#' + textarea_id).hide();

		params = $.extend({
			height: 300
		}, textarea.data(), params);

		var mode = params.mode || params.mime ? parseMimeMode(params.mime) : 'php';

		var editArea = $('<div id="' + editor_id + '" />')
			.insertAfter(textarea)
			.css({
				height: params.height,
				fontSize: 14
			});

		var editor = ace.edit(editor_id);

		editor.$blockScrolling = Infinity;
		editor.setValue(textarea.val());

		editor.clearSelection();
		editor.getSession().setMode("ace/mode/" + mode);
		editor.getSession().setTabSize(4);
		editor.getSession().setUseSoftTabs(false);
		editor.getSession().setUseWrapMode(true);
		editor.getSession().on('change', function () {
			textarea.val(editor.getSession().getValue());
		});

		editor.setTheme("ace/theme/" + ACE_THEME);

		function parseMimeMode(mime) {
			var mode;
			switch (mime)
			{
				case 'application/json':
					mode = 'json';
					break;
				case 'text/plain':
					mode = 'text';
					break;
				case 'text/x-sql':
					mode = 'sql';
					break;
				default:
					mode = mime.indexOf('text/') === 0 ? mime.substring(5) : 'text';
			}

			return mode;
		}

		if (textarea.data('readonly') == 'on') {
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