@section('scripts')
	@parent

<script type="text/javascript">
var show_button = true;
var current_driver = '';
$(function() {
	init_test_email_button_vars();

	$('.panel')
		.on('change', '#emailDriver', function() {
			change_email_driver($(this).val());
			test_email_button_visible();
		})
		.on('change', '#settingEncryption', function() {
			var $encryption = $(this).val();
			change_email_port($encryption);
		});

	change_email_driver($('#emailDriver').val());
	change_email_port($('#settingEncryption').val());

	$('body').on('click', '#send-test-email', function() {
		Api.post('/api.email.send', {
			subject: '@lang('email::core.settings.test.subject')',
			to: '{{ config('mail.default') }}',
			message: '@lang('email::core.settings.test.message')'
		}, function(response) {
			console.log(response.content);
			var message;
			if (response.content.send)
			{
				message = '@lang('email::core.settings.test.result_positive')';
			} else
			{
				message = '@lang('email::core.settings.test.result_negative')'
			}
			CMS.messages.show(message, response.content.send ? 'success' : 'error');
		});
		return false;
	});

	$('body').on('post:backend:api-settings.save', function() {
		init_test_email_button_vars();
		test_email_button_visible();
	});
});
function init_test_email_button_vars() {
	show_button = true;
	current_driver = $('#emailDriver').val();
	test_email_button_visible();
}
function change_email_port($encryption) {
	var $port = $('#settingPort');
	switch($encryption){
		case 'ssl':
		case 'tls':
			$port.val(465);
			break;
		default:
			$port.val(25);
			break;
	}
}
function change_email_driver(driver) {
	if(current_driver != driver)
		show_button = false;
	else
		show_button = true;
    $('fieldset').attr('disabled', 'disabled').hide();

	var $fieldset = $('fieldset#' + driver + '-driver-settings');
    $fieldset.removeAttr('disabled').show();
	CMS.clear_error($fieldset, false);
}
function test_email_button_visible() {
	var $button = $('#send-test-email');
	var $tips = $('.test-email-message');
	if(show_button) {
		$button.show();
		$tips.hide();
	} else {
		$button.hide();
		$tips.show();
	}
}
</script>
@stop

<div class="panel-heading" data-icon="envelope">
	<span class="panel-title">@lang('email::core.settings.title')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="settingDefault">@lang('email::core.settings.default_email')</label>
		<div class="col-md-9">
			{!! Form::text('config[mail][default]', config('mail.default'), [
				'id' => 'settingDefault', 'class' => 'form-control'
			]) !!}
		</div>
	</div>
	<div>
		<div class="panel-heading">
        	<span class="panel-title">@lang('email::core.settings.title')</span>
        </div>
        <div class="panel-body">
			<div class="form-group">
				<label class="control-label col-md-3" for="emailDriver">@lang('email::core.settings.email_driver')</label>
				<div class="col-md-6">
					{!! Form::select('config[mail][driver]', $drivers, config('mail.driver'), ['id' => 'emailDriver', 'class' => 'form-control']) !!}

					<p class="help-block test-email-message">@lang('email::core.settings.test.label')</p>
				</div>
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-labeled" id="send-test-email" data-icon="envelope">
						@lang('email::core.settings.test.btn')
					</a>
				</div>
			</div>

			<fieldset id="sendmail-driver-settings">
				<hr class="panel-wide"/>
				<div class="form-group">
					<label class="control-label col-md-3" for="settingPath">@lang('email::core.settings.sendmail.path')</label>
					<div class="col-md-9">
						{!! Form::text('config[mail][sendmail]', config('mail.sendmail'), [
							'id' => 'settingPath', 'class' => 'form-control',
							'placeholder' => trans('email::core.settings.sendmail.placeholder')
						]) !!}

						<p class="help-block">@lang('email::core.settings.sendmail.help', [
							'path1' => '/usr/sbin/sendmail',
							'path2' => '/usr/lib/sendmail',
							'link' => link_to('http://www.php.net/manual/en/mail.configuration.php', 'www.php.net', array('target' => 'blank'))
						])</p>
					</div>
				</div>
			</fieldset>

			<fieldset id="smtp-driver-settings">
				<hr class="panel-wide"/>
				<div class="form-group">
					<label class="control-label col-md-3" for="settingHost">@lang('email::core.settings.smtp.host')</label>
					<div class="col-md-9">
						{!! Form::text('config[mail][host]', config('mail.host'), [
							'id' => 'settingHost', 'class' => 'form-control'
						]) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="settingPort">@lang('email::core.settings.smtp.port')</label>
					<div class="col-md-2">
						{!! Form::text('config[mail][port]', config('mail.port'), [
							'id' => 'settingPort', 'class' => 'form-control'
						]) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="settingUsername">@lang('email::core.settings.smtp.username')</label>
					<div class="col-md-3">
						{!! Form::text('config[mail][username]', config('mail.username'), [
							'id' => 'settingUsername', 'class' => 'form-control'
						]) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="settingPassword">@lang('email::core.settings.smtp.password')</label>
					<div class="col-md-3">
						{!! Form::text('config[mail][password]', config('mail.password'), [
							'id' => 'settingPassword', 'class' => 'form-control'
						]) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="settingEncryption">@lang('email::core.settings.smtp.encryption')</label>
					<div class="col-md-2">
						{!! Form::select('config[mail][encryption]', [
							NULL => 'Disable',
							'ssl' => 'SSL',
							'tls' => 'TLS'
						], config('mail.encryption'), [
							'id' => 'settingEncryption',
							'class' => 'form-control'
						]) !!}
					</div>
				</div>
			</fieldset>

			<fieldset id="mailgun-driver-settings">
				<hr class="panel-wide"/>
				<div class="form-group">
					<label class="control-label col-md-3" for="mailgunDomain">@lang('email::core.settings.mailgun.domain')</label>
					<div class="col-md-9">
						{!! Form::text('config[services][mailgun][domain]', config('services.mailgun.domain'), [
							'id' => 'mailgunDomain', 'class' => 'form-control'
						]) !!}
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="mailgunSecret">@lang('email::core.settings.mailgun.secret')</label>
					<div class="col-md-9">
						{!! Form::text('config[services][mailgun][secret]', config('services.mailgun.secret'), [
							'id' => 'mailgunSecret', 'class' => 'form-control'
						]) !!}
					</div>
				</div>
			</fieldset>

			<fieldset id="mandrill-driver-settings">
				<hr class="panel-wide"/>
				<div class="form-group">
					<label class="control-label col-md-3" for="mandrillSecret">@lang('email::core.settings.mandrill.secret')</label>
					<div class="col-md-9">
						{!! Form::text('config[services][mandrill][secret]', config('services.mandrill.secret'), [
							'id' => 'mandrillSecret', 'class' => 'form-control'
						]) !!}
					</div>
				</div>
			</fieldset>
		</div>
		<div class="panel-heading">
        	<span class="panel-title">@lang('email::core.settings.queue.title')</span>
        </div>
        <div class="panel-body">
        	<div class="form-group">
				<label class="control-label col-md-3" for="batchSize">@lang('email::core.settings.queue.batch_size')</label>
				<div class="col-md-3">
					{!! Form::text('config[email_queue][batch_size]', config('email_queue.batch_size'), [
						'id' => 'batchSize', 'class' => 'form-control'
					]) !!}
				</div>
				<div class="col-md-offset-3 col-md-9">
					<p class="help-block">@lang('email::core.settings.queue.batch_help')</p>
				</div>
			</div>
        	<div class="form-group">
				<label class="control-label col-md-3" for="batchInterval">@lang('email::core.settings.queue.interval')</label>
				<div class="col-md-3">
					{!! Form::text('config[email_queue][interval]', config('email_queue.interval'), [
						'id' => 'batchInterval', 'class' => 'form-control'
					]) !!}
				</div>
			</div>
        	<div class="form-group">
				<label class="control-label col-md-3" for="batchMaxAttempts">@lang('email::core.settings.queue.max_attempts')</label>
				<div class="col-md-3">
					{!! Form::text('config[email_queue][max_attempts]', config('email_queue.max_attempts'), [
						'id' => 'batchMaxAttempts', 'class' => 'form-control'
					]) !!}
				</div>
				<div class="col-md-offset-3 col-md-9">
					<p class="help-block">@lang('email::core.settings.queue.max_attempts_help')</p>
				</div>
			</div>
        </div>

	</div>
</div>