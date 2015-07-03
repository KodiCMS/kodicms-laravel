/**
 * @class  elFinder toolbar search button widget.
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindersearchbutton = function (cmd) {
	return this.each(function () {
		var result = false,
			button = $(this)
				.hide()
				.addClass('pane-heading-controls hidden-sm hidden-xs form-inline col-sm-auto ' + cmd.fm.res('class', 'searchbtn') + ''),
			form = $('<div class="input-group input-group-sm" />').appendTo(button),
			search = function () {
				var val = $.trim(input.val());
				if (val) {
					cmd.exec(val).done(function () {
						result = true;
						input.focus();
					});

				} else {
					cmd.fm.trigger('searchend')
				}
			},
			abort = function () {
				input.val('');
				if (result) {
					result = false;
					cmd.fm.trigger('searchend');
				}
			},
			input = $('<input type="text" class="form-control no-margin-b" placeholder="' + cmd.fm.i18n('cmdsearch') + '" />')
				.appendTo(form)
				// to avoid fm shortcuts on arrows
				.keypress(function (e) {
					e.stopPropagation();
				})
				.keydown(function (e) {
					e.stopPropagation();

					e.keyCode == 13 && search();

					if (e.keyCode == 27) {
						e.preventDefault();
						abort();
					}
				});

		$('<span class="input-group-btn"><button class="btn btn-default" title="' + cmd.title + '"><i class="fa fa-search" /></button></span>')
			.appendTo(form)
			.click(search);

		// wait when button will be added to DOM
		setTimeout(function () {
			button.parent().detach();
			cmd.fm.getUI('toolbar').prepend(button.show());
		}, 200);

		cmd.fm
			.error(function () {
				input.unbind('keydown');
			})
			.select(function () {
				input.blur();
			})
			.bind('searchend', function () {
				input.val('');
			})
			.viewchange(abort)
			.shortcut({
				pattern: 'ctrl+f f3',
				description: cmd.title,
				callback: function () {
					input.select().focus();
				}
			});
	});
}