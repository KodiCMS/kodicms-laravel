$(function() {
	$('#wizard').on('change', '#current-lang', function() {
		window.location = '?lang=' + $(this).val();
	})

	var password_generator_status = function() {
		var checkbox = $('#generate-password-checkbox');

		if(checkbox.is(':checked')){
			$('#password-form').hide();
		} else {
			$('#password-form').show();
		}
	}

	$('#wizard').on('change', '#generate-password-checkbox', password_generator_status);
	password_generator_status();

	function show_error($error) {
		$('#wizard .wizard-alert').remove();

		$('#wizard .wizard-pane.active .widget-content')
			.append('<p class="wizard-alert alert alert-error">'+$error+'</p>');
	}

	$("#wizard").steps({
		labels: {
			current: "",
			pagination: "Pagination",
			finish: __("Finish"),
			next: __("Next"),
			previous: __("Previous"),
			loading: __("Loading ...")
		},
		onInit: function() {
			$(this).find('.steps ul').addClass('nav nav-tabs tabs-generated');
			//$(this).find('.content').addClass('tab-content')
		},
		onStepChanging: function (event, currentIndex, newIndex) {
			$form = $(".form-horizontal");

			if(currentIndex == 1 && newIndex > currentIndex && FAILED ) {
				CMS.messages.parse([__('Before proceeding to the next step you need to fix errors')], 'error');
				return false;
			}

			if(currentIndex == 2 && newIndex > currentIndex) {
				if(validate_step_2($form))
				{
					return check_connect();
				};

				return false;
			}

			console.log(currentIndex, newIndex, currentIndex)
			if(currentIndex == 3 && newIndex > currentIndex) {
				return validate_step_3($form);
			}

			return true;
		},
		onFinishing: function (event, currentIndex) {
			$status = validate_step_3($form);
			return $status && $("form").submit();
		}
	});

	function validate_step_2($form) {
		$form.validate({
			onsubmit: false,
			rules: {
				'install[db_host]': "required",
				'install[db_username]': "required",
				'install[db_database]': "required"
			}
		}, true);

		return $form.valid();
	}

	function validate_step_3($form) {
		console.log($form);
		$form.validate({
			onsubmit: false,
			rules: {
				'install[site_name]': "required",
				'install[username]': "required",
				'install[password]': {
					required: true,
					minlength: 5
				},
				'install[email]': {
					required: true,
					email: true
				},
				'install[admin_dir_name]': "required",
				'install[password_confirmation]': {
					equalTo: "#password"
				},
			}
		}, true);

		return $form.valid();
	}

	function check_connect() {
		CMS.clear_error();
		var $fields = $(':input[name*=db_]').serialize();

		var response = Api.get('api.installer.databaseCheck', $fields, false, false);

		if(response.code == 200 && response.content) return true;

		if(response.message) {
			CMS.clear_error();
			CMS.messages.parse([response.message], 'error');
		}

		return false;
	}


	$('.select2-container').remove();
	CMS.ui.init('select2');
	CMS.ui.init('icon');
});

