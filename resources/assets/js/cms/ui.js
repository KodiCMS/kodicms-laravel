CMS.ui.add('flags', function () {
	$('body').on('click', '.flags .label', function (e) {
		var $src = $(this).parent().data('target');
		if (!$src) $src = $(this).parent().prevAll(':input');
		else $src = $($src);

		var $container = $(this).parent();
		var $append = $container.data('append') == true;
		var $array = $container.data('array') == true;
		var $value = $(this).data('value');

		if ($array) $value = $value.split(',');

		if ($append) {
			if ($src.is(':input')) {
				var $values = $src.val().split(', ');
				$values.push($value);
				$values = _.uniq(_.compact($values));
				$src.val($values.join(', '));
			}
			else {
				var $values = $src.text().split(', ');
				$values.push($value);
				$values = _.uniq(_.compact($values));
				$src.text($values.join(', '));
			}

			$('.label', $container).removeClass('label-success');
			for (i in $values) {
				$('.label[data-value="' + $values[i] + '"]').addClass('label-success');
			}

		} else {
			$('.label', $container).removeClass('label-success');
			$(this).addClass('label-success');

			if ($src.hasClass('select2-offscreen')) {
				$src.select2("val", $value);
			}
			else if ($src.is(':input')) {
				$src.val($value);
			}
			else {
				$src.text($value);
			}
		}

		$src.change();

		e.preventDefault();
	});
}).add('btn-confirm', function () {
	$('body').on('click', '.btn-confirm', function (e) {
		var $btn = $(this);

		var message = i18n.t('cms.core.messages.are_you_sure');
		if ($btn.data('message')) {
			message = $btn.data('message');
		}

		return confirm(message);
	});
}).add('calculate_height', function () {
	CMS.calculateContentHeight();

	$(window).resize(function () {
		CMS.content_height = null;
		CMS.calculateContentHeight();
	});
}).add('panel-toggler', function () {
	var icon_open = 'fa-chevron-up',
		icon_close = 'fa-chevron-down',
		text = i18n.t('cms.core.label.toggler_close');

	$('.panel-toggler')
		.click(function () {
			var $self = $(this),
				hash = $self.data('hash');

			if ($self.data('target')) {
				$_cont = $($self.data('target'));
			} else {
				var $_cont = $self.next('.panel-spoiler');
			}

			$_cont.slideToggle('fast', function () {
				var $icon = $self.find('.panel-toggler-icon');
				var $text = $self.find('.panel-toggler-text');

				if ($(this).is(':hidden')) {
					$icon.removeClass(icon_open).addClass(icon_close).addClass('fa');
					$text.text(i18n.t('cms.core.label.toggler_close'));
					if(hash) CMS.hashString.removeParam('toggled', hash);
				} else {
					$icon.addClass(icon_open).removeClass(icon_close).addClass('fa');
					$text.text(i18n.t('cms.core.label.toggler_open'));
					if(hash) CMS.hashString.setParam('toggled[]', hash);
				}
			});

			return false;
		}).each(function () {
			var $self = $(this),
				hash = $self.data('hash');

			if (CMS.hashString.findInParam('toggled', hash)) {
				$self.click();
			}
		})
		.append('' +
			'<div class="panel-heading-controls">' +
				'<span class="text-sm">' +
					'<i class="panel-toggler-icon fa ' + icon_close + '" />' +
					'&nbsp;&nbsp;&nbsp;<span class="panel-toggler-text">' + text + '</span>' +
				'</span>' +
			'</div>');

}).add('datepicker', function () {

	var options = {
		format: 'Y-m-d H:i:00',
		lang: LOCALE,
		dayOfWeekStart: 1
	};

	$('.datetimepicker').each(function () {
		var local_options = $.extend({}, options);
		var $self = $(this);

		if ($self.data('range-max-input')) {
			local_options['onShow'] = function (ct) {
				var $input = $($self.data('range-max-input'));
				this.setOptions({
					maxDate: $input.val() ? $input.val() : false
				});
			}
		}

		if ($self.data('range-min-input')) {
			local_options['onShow'] = function (ct) {
				var $input = $($self.data('range-min-input'));
				this.setOptions({
					minDate: $input.val() ? $input.val() : false
				});
			}
		}

		$self.datetimepicker(local_options);
	});

	$('.datepicker').each(function () {
		var local_options = $.extend(options, {
			timepicker: false,
			format: 'Y-m-d'
		});

		var $self = $(this);

		if ($self.data('range-max-input')) {
			local_options['onShow'] = function (ct) {
				var $input = $($self.data('range-max-input'));
				this.setOptions({
					maxDate: $input.val() ? $input.val() : false
				});
			}
		} else if ($self.data('range-min-input')) {
			local_options['onShow'] = function (ct) {
				var $input = $($self.data('range-min-input'));
				this.setOptions({
					minDate: $input.val() ? $input.val() : false
				});
			}
		}

		$self.datetimepicker(local_options);
	});

	$('.timepicker').each(function () {
		var local_options = $.extend(options, {
			timepicker: true,
			datepicker: false,
			format: 'H:i:s'
		});

		var $self = $(this);

		$self.datetimepicker(local_options);
	});
}).add('slug', function () {

	var slugs = {};
	$('body').on('keyup', '.slug-generator', function () {
		var $slug_cont = $('.slugify');

		if ($(this).data('slug')) {
			$slug_cont = $($(this).data('slug'));
		}

		$separator = '-';
		if ($(this).data('separator')) {
			$separator = $(this).data('separator');
		}

		if ($slug_cont.is(':input') && $slug_cont.val() == '')
			slugs[$slug_cont] = true;

		$slug_cont.on('keyup', function () {
			slugs[$slug_cont] = false;
		});

		if (slugs[$slug_cont]) {
			var slug = getSlug($(this).val(), {
				separator: $separator
			});

			if ($slug_cont.is(':input'))
				$slug_cont.val(slug);
			else
				$slug_cont.text(slug);
		}
	});

	$('body').on('keyup', '.slugify', function () {
		var c = String.fromCharCode(event.keyCode);
		var isWordcharacter = c.match(/\w/);

		if (!isWordcharacter && event.keyCode != '32') return;

		$separator = '-';
		if ($(this).data('separator')) {
			$separator = $(this).data('separator');
		}

		var slug = getSlug($(this).val(), {
			separator: $separator
		});

		$(this).val(slug);
		slugs[$(this)] = false;

		if ($(this).val() == '')
			slugs[$(this)] = true;
	});

}).add('dropzone', function () {
	// Disable auto discover for all elements:
	Dropzone.autoDiscover = false;

	if (!$('.dropzone').length) {
		return;
	}

	CMS.uploader = new Dropzone('.dropzone', {
		success: function (file, r) {
			var response = $.parseJSON(r);
			var self = this;
			if (response.code != 200) {
				CMS.messages.error(response.message);

			} else if (response.message) {
				CMS.messages.show(response.message);
			}

			$(file.previewElement).fadeOut(500, function () {
				self.removeFile(file);
			});
		},
		error: function (file, message) {
			CMS.messages.error(message);
			this.removeFile(file);
		},
		dictDefaultMessage: __("Drop files here to upload"),
		dictFallbackMessage: __("Your browser does not support drag'n'drop file uploads."),
		dictFallbackText: __("Please use the fallback form below to upload your files like in the olden days."),
		dictFileTooBig: __("File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB."),
		dictInvalidFileType: __("You can't upload files of this type."),
		dictResponseError: __("Server responded with {{statusCode}} code."),
		dictCancelUpload: __("Cancel upload"),
		dictCancelUploadConfirmation: __("Are you sure you want to cancel this upload?"),
		dictRemoveFile: __("Remove file"),
		dictMaxFilesExceeded: __("You can only upload {{maxFiles}} files."),
	});
}).add('select2', function () {
	$.extend($.fn.select2.defaults.defaults, {
		theme: "bootstrap",
		width: 'style'
	})

	$('select').not('.no-script').select2({
		minimumResultsForSearch: Infinity
	});

	$('.tags').select2({
		tags: true,
		tokenSeparators: [',', ' ', ';']
	});
}).add('filemanager', function () {
	var $input = $(':input[data-filemanager]');

	$input.each(function () {
		var $self = $(this);
		var $btn = $('<button class="btn btn-info btn-labeled" type="button"><i class="fa fa-folder-open"></i></button>');
		if ($self.next().hasClass('input-group-btn')) {
			$btn.prependTo($self.next());
		} else {
			$btn.insertAfter($self);
		}

		$btn.on('click', function () {
			CMS.filemanager.open($self);
		});

		$self.removeAttr('data-filemanager');
	})

	$('body').on('click', '.btn-filemanager', function () {
		var el = $(this).data('el');
		var type = $(this).data('type');

		if (!el) return false;

		CMS.filemanager.open(el, type);
		return false;
	});
}).add('hotkeys', function () {
	$('*[data-hotkeys]').each(function () {
		var $self = $(this),
			$hotkeys = $self.data('hotkeys'),
			$callback = function (e) {
				e.preventDefault();
			};

		if ($self.is(':submit') || $self.hasClass('popup')) {
			$callback = function (e) {
				$self.trigger('click');
				e.preventDefault();
			}
		} else if ($self.attr('href')) {

			$callback = function (e) {
				if ($self.hasClass('btn-confirm')) {
					if (!confirm(__('Are you sure?')))
						return false;
				}
				window.location = $self.attr('href');
				e.preventDefault();
			}
		} else if ($self.hasClass('panel-toggler')) {
			$callback = function (e) {
				$self.trigger('click');
				$('body').scrollTo($self);
				e.preventDefault();
			}
		} else if ($self.hasClass('nav-tabs')) {
			$callback = function (e) {
				var $current_li = $self.find('li.active'),
					$next_li = $current_li.next();

				if ($next_li.hasClass('nav-section')) {
					$next_li = $next_li.next();
				}
				if ($current_li.is(':last-child')) {
					$next_li = $self.parent().find('li:first-child');
				}

				$next_li.find('a').trigger('click');
				e.preventDefault();
			}
		} else if ($self.is(':checkbox')) {
			$callback = function (e) {
				if ($self.prop("checked"))
					$self.uncheck().trigger('change');
				else
					$self.check().trigger('change');
				e.preventDefault();
			}
		}

		$(document).on('keydown', null, $hotkeys, $callback);
	});

	// GLOBAL HOTKEYS
	$(document).on('keydown', null, 'shift+f1', function (e) {
		Api.delete('/api.cache.clear');
		e.preventDefault();
	});

	$(document).on('keydown', null, 'shift+f2', function (e) {
		Api.get('/api.layout.rebuild');
		e.preventDefault();
	});

	$(document).on('keydown', null, 'ctrl+shift+l', function (e) {
		window.location = '/backend/auth/logout';
		e.preventDefault();
	});
}).add('select_all_checkbox', function () {
	$(document).on('change', 'input[name="check_all"]', function (e) {
		var $self = $(this),
			$target = $self.data('target');

		if (!$target) return false;

		$($target).prop("checked", this.checked).trigger('change');
		e.preventDefault();
	});
}).add('icon', function () {
	$('*[data-icon]').add('*[data-icon-prepend]').each(function () {
		var cls = $(this).data('icon');
		if ($(this).hasClass('btn-labeled')) cls += ' btn-label icon';

		$(this).html('<i class="fa fa-' + cls + '"></i> ' + $(this).html());
		$(this).removeAttr('data-icon-prepend').removeAttr('data-icon');
	});

	$('*[data-icon-append]').each(function () {
		$(this).html($(this).html() + '&nbsp&nbsp<i class="fa fa-' + $(this).data('icon-append') + '"></i>');
		$(this).removeAttr('data-icon-append');
	});
}).add('tabbable', function () {
	$('.tabbable').each(function (i) {
		var $self = $(this);

		if ($('> .panel-heading', $self).size() > 0) {
			var $tabs_content = $('<div class="tab-content no-padding-t no-padding-b" />').prependTo($self);
			var $tabs_ul = $('<ul class="nav nav-tabs tabs-generated" />');
			$('> .panel-heading', $self).each(function (j) {
				var $li = $('<li></li>').appendTo($tabs_ul);
				var $content = $(this).nextUntil('.panel-heading').not('.panel-footer').removeClass('panel-spoiler');

				$(this).find('.panel-title').removeClass('panel-title');
				$(this).find('.panel-heading-controls').remove();
				$('<div class="tab-pane" id="panel-tab-' + i + '' + '' + j + '" />').append($content).appendTo($tabs_content);
				$('<a href="#panel-tab-' + i + '' + '' + j + '" data-toggle="tab"></a>').html($(this).html()).appendTo($li);

				$(this).remove();
			});

			$tabs_ul.prependTo($self);
		}
	});

	$('.tabs-generated li:first-child').add('.tabbable .tab-pane:first-child').addClass('active');
	$('.tabs-generated').tabdrop();

}).add('noty', function () {
	$.noty.themes.KodiCMSTheme = $.extend($.noty.themes.bootstrapTheme, {
		name: 'KodiCMSTheme',
		style: function () {

			var containerSelector = this.options.layout.container.selector;
			$(containerSelector).addClass('list-group');

			this.$closeButton.append('<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>');
			this.$closeButton.addClass('close');

			this.$bar.addClass('alert alert-dark').css('padding', '10px');
			switch (this.options.type) {
				case 'alert':
				case 'notification':
				case 'information':
					this.$bar.addClass("alert-info");
					break;
				case 'warning':
					this.$bar.addClass("alert-warning");
					break;
				case 'error':
					this.$bar.addClass("alert-danger");
					break;
				case 'success':
					this.$bar.addClass("alert-success");
					break;
			}

			this.$message.css({

				position: 'relative'
			});
		}
	});

	$.noty.defaults = $.extend($.noty.defaults, {
		layout: 'topRight',
		theme: 'KodiCMSTheme',
		timeout: 3000
	});
})
.add('momentJs', function () {
	moment.locale(LOCALE);
})
.add('switcher', function () {
	$(".form-switcher").bootstrapToggle();
})
.add('bootbox', function () {
	bootbox.setLocale(LOCALE);
})
.add('bootstrap', function () {
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
});