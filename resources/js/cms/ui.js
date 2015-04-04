CMS.ui
.add('ajaxSetup', function() {
	$.ajaxSetup({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
	});
})
.add('i18nSetup', function() {
	i18n.init({
		lng: 'ru',
		fallbackLng: [],
		resGetPath: '/js/lang/__lng__.json',
	});
})
.add('select', function() {
	$("select").select2();
})
.add('notySetup', function() {
	$.noty.defaults = $.extend($.noty.defaults, {
		layout: 'topRight',
		theme: 'relax',
		timeout: 3000
	});
})
.add('validator.default', function() {
	$.validator.setDefaults({
		highlight: function(element) {
			var elem = $(element);
			if (elem.hasClass("select2-offscreen")) {
                $("#s2id_" + elem.attr("id") + " ul")
					.closest('.form-group')
					.addClass('has-error');
            } else {
				elem
					.closest('.form-group')
					.addClass('has-error');
            }
		},
		unhighlight: function(element) {
			var elem = $(element);
			
			if (elem.hasClass("select2-offscreen")) {
				$("#s2id_" + elem.attr("id") + " ul")
					.closest('.form-group')
					.removeClass('has-error')
					.find('help-block-hidden')
					.removeClass('help-block-hidden')
					.addClass('help-block')
					.show();
			} else {
				elem
					.closest('.form-group')
					.removeClass('has-error')
					.find('help-block-hidden')
					.removeClass('help-block-hidden')
					.addClass('help-block')
					.show();
			}
		},
		errorElement: 'p',
		errorClass: 'jquery-validate-error help-block',
		errorPlacement: function(error, element) {
			var $p, has_e, is_c;
			is_c = element.is('input[type="checkbox"]') || element.is('input[type="radio"]');
			has_e = element.closest('.form-group').find('.jquery-validate-error').length;
			if (!is_c || !has_e) {
				if (!has_e) {
					element.closest('.form-group').find('.help-block').removeClass('help-block').addClass('help-block-hidden').hide();
				}
				error.addClass('help-block');
				if (is_c) {
					return element.closest('[class*="col-"]').append(error);
				} else {
					$p = element.parent();
					if ($p.is('.input-group')) {
						return $p.parent().append(error);
					} else {
						return $p.append(error);
					}
				}
			}
		}
	});
})
.add('tags', function() {
	$('textarea.input-tags').select2({
		tags: [],
		minimumInputLength: 0,
		tokenSeparators: [',', ' ', ';'],
		createSearchChoice: function(term, data) {
			if ($(data).filter(function() {
					return this.text.localeCompare(term)===0;
				}).length===0) {
				return {
					id:term,
					text:term
				};
			}
		},
		multiple: true,
		ajax: {
			url: '/api.tags',
			dataType: "json",
			data: function(term, page) {
				return {tag: term};
			},
			results: function(data, page) {
				if(!data.content) return {results: []};
				return {results: data.content};
			}
		},
		initSelection: function(element, callback) {
			var data = [];

			var tags = element.val().split(",");
			for(i in tags) {
				data.push({
					id: tags[i],
					text: tags[i]
				});
			};
			callback(data);
		}
	});
})
.add('datepicker', function() {
	var options = {
		format: 'Y-m-d H:i:00',
		lang: LOCALE,
		dayOfWeekStart: 1
	};

	$('.input-datetime').each(function() {
		var local_options = $.extend({}, options);
		var $self = $(this);

		if($self.data('range-max-input')) {
			local_options['onShow'] = function(ct) {
				var $input = $($self.data('range-max-input'));
				this.setOptions({
					maxDate: $input.val() ? $input.val() : false
				});
			}
		}

		if($self.data('range-min-input')) {
			local_options['onShow'] = function(ct) {
				var $input = $($self.data('range-min-input'));
				this.setOptions({
					minDate: $input.val() ? $input.val() : false
				});
			}
		}

		$self.datetimepicker(local_options);
	});

	$('.input-date').each(function() {
		var local_options = $.extend(options, {
			timepicker: false,
			format: 'Y-m-d'
		});

		var $self = $(this);

		if($self.data('range-max-input')) {
			local_options['onShow'] = function( ct ){
				var $input = $($self.data('range-max-input'));
				this.setOptions({
					maxDate: $input.val() ? $input.val() : false
				});
			}
		} else if($self.data('range-min-input')) {
			local_options['onShow'] = function( ct ){
				var $input = $($self.data('range-min-input'));
				this.setOptions({
					minDate: $input.val() ? $input.val() : false
				});
			}
		}

		$self.datetimepicker(local_options);
	});
});