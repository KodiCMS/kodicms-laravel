CMS.controllers.add(['email.template.get.create', 'email.template.get.edit'], function()
{
	$('#email_event_id').on('change', function () {
		show_options($(this).val());
	});

	$(':radio[name="message_type"]').on('change', function () {
		message_type = $(this).val();
		change_message_redator(message_type)
	});

	function change_message_redator(type) {
		if (type == 'html')
			CMS.filters.switchOn('message', DEFAULT_HTML_EDITOR);
		else
			CMS.filters.switchOn('message', DEFAULT_CODE_EDITOR);
	}

	var activeInput;
	$(':input[type="text"],textarea').on('focus', function () {
		activeInput = $(this);
	});

	$('#field_description').on('click', 'a', function (e) {
		e.preventDefault();

		var curInput = activeInput;

		if (activeInput instanceof jQuery) {
			var cursorPos = curInput.prop('selectionStart');
			var v = curInput.val();
			var textBefore = v.substring(0, cursorPos);
			var textAfter = v.substring(cursorPos, v.length);
			curInput.val(textBefore + $(this).text() + textAfter);
		} else {
			CMS.filters.exec('email_template_message', 'insert', $(this).text());
		}
	});

	show_options($('#email_event_id').val());
	function show_options(id) {
		Api.get('/api.email.events.options', {uid: id}, function (resp) {
			var cont = $('#field_description .col-md-9').empty();
			var ul = $('<ul class="list-unstyled" />').appendTo(cont);
			if (resp.content) {
				for (field in resp.content) {
					$('<li><a href="#" class="field-key">{' + field + '}</a> - ' + resp.content[field] + '</li>').appendTo(ul);
				}
			}
		})
	}
	$(function() {
		var message_type = $(':radio[name="message_type"]:checked').val();
		change_message_redator(message_type);
	});
});