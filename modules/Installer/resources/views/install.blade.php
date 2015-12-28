<div class="container-fluid margin-sm-vr">
	{!! Form::open([
		'class' => 'form-horizontal'
	]) !!}
	<div id="wizard" class="wizard panel">
		<h1>@lang('installer::core.title.language')</h1>
		<div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.current_language')</label>
					<div class="col-md-3">
						{!! Form::select('locale', $locales,$selectedLocale, [
							'id' => 'current-lang', 'class' => 'form-control'
						]) !!}
					</div>
				</div>
			</div>
		</div>
		<h1>@lang('installer::core.title.environment')</h1>
		<div>
			{!! $environment !!}
		</div>
		<h1>@lang('installer::core.title.database')</h1>
		<div>
			<div class="note note-info">@lang('installer::core.messages.database_connection_information')</div>
			<div class="panel-body">
				<div class="connection-settings">
					<div class="form-group">
						<label class="control-label col-md-3" for="db_driver">@lang('installer::core.field.db_driver')</label>
						<div class="col-md-3">
							{!! Form::select('database[driver]', array_combine($dbDrivers, $dbDrivers),array_get($database, 'driver'), [
                                'id' => 'db_driver', 'class' => 'form-control col-sm-auto'
                            ]) !!}
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_host">@lang('installer::core.field.db_server')</label>
						<div class="col-md-9">
							{!! Form::text('database[host]', array_get($database, 'host'), [
								'class' => 'form-control col-sm-auto', 'id' => 'db_host'
							]) !!}
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_username">@lang('installer::core.field.db_username')</label>
						<div class="col-md-9 form-inline">
							{!! Form::text('database[username]', array_get($database, 'username'), [
								'class' => 'form-control col-sm-auto', 'id' => 'db_username',
							]) !!}
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_password">@lang('installer::core.field.db_password')</label>
						<div class="col-md-9">
							{!! Form::password('database[password]', [
								'class' => 'form-control col-sm-auto', 'id' => 'db_password'
							]) !!}

							<p class="help-block">@lang('installer::core.messages.database_no_password')</p>
						</div>
					</div>
				</div>
				<div class="form-group well well-sm">
					<label class="control-label col-md-3" for="db_database">@lang('installer::core.field.db_database')</label>
					<div class="col-md-9 form-inline">
						{!! Form::text('database[database]', array_get($database, 'database'), [
							'class' => 'form-control col-sm-auto', 'id' => 'db_database', 'required'
						]) !!}

						<p class="help-block">@lang('installer::core.messages.database_name_inforamtion')</p>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="db_preffix">@lang('installer::core.field.db_preffix')</label>
					<div class="col-md-9 form-inline">
						{!! Form::text('database[prefix]', array_get($data, 'prefix'), [
							'class' => 'form-control', 'id' => 'db_preffix'
						]) !!}
					</div>
				</div>
			</div>
		</div>

		<h1>@lang('installer::core.title.site_information')</h1>
		<div>
			<div class="panel-heading" data-icon="user">
				<span class="panel-title">@lang('installer::core.title.user_settings')</span>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3" for="username">@lang('installer::core.field.username')</label>
					<div class="col-md-9 form-inline">
						{!! Form::text('install[username]', array_get($data, 'username'), [
							'class' => 'form-control', 'id' => 'username', 'required'
						]) !!}
					</div>
				</div>
				<div class="well well-small">
					<div id="password-form">
						<div class="form-group">
							<label class="control-label col-md-3" for="password">@lang('installer::core.field.password')</label>
							<div class="col-md-9 form-inline">
								{!! Form::password('install[password]', [
									'class' => 'form-control', 'id' => 'password', 'required'
								]) !!}

								{!! Form::password('install[password_confirmation]', [
									'class' => 'form-control', 'id' => 'password_confirmation', 'placeholder' => trans('installer::core.field.password_conform'), 'required'
								]) !!}
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="email">@lang('installer::core.field.email')</label>
					<div class="col-md-9 form-inline">
						{!! Form::text('install[email]', array_get($data, 'email'), [
							'class' => 'form-control', 'id' => 'email', 'required'
						]) !!}
					</div>
				</div>
			</div>
			<div class="panel-heading" data-icon="exclamation-circle">
				<span class="panel-title">@lang('installer::core.title.site_settings')</span>
			</div>
			<div class="panel-body">
				<div class="form-group form-group-lg">
					<label class="control-label col-md-3" for="site_title">@lang('installer::core.field.site_title')</label>
					<div class="col-md-9">
						{!! Form::text('install[site_title]', array_get($data, 'site_title'), [
							'class' => 'form-control', 'id' => 'site_title', 'required'
						]) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="admin_dir_name">@lang('installer::core.field.admin_dir_name')</label>
					<div class="col-md-9 form-inline">
						<div class="input-group">
							<div class="input-group-addon">{{ url()->current() }}/</div>
							{!! Form::text('install[admin_dir_name]', array_get($data, 'admin_dir_name'), [
							'class' => 'form-control', 'id' => 'admin_dir_name', 'size' => 20, 'maxlength' => 20, 'required'
							]) !!}
						</div>
					</div>
				</div>
			</div>
			<div class="panel-heading" data-icon="globe">
				<span class="panel-title">@lang('installer::core.title.regional_settings')</span>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.interface_locale')</label>
					<div class="col-md-3">
						{!! Form::select('install[locale]', $locales, array_get($data, 'locale'), ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.timezone')</label>
					<div class="col-md-3">
						{!! Form::select('install[timezone]', array_combine($timezones, $timezones), array_get($data, 'timezone'), ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.date_format')</label>
					<div class="col-md-3">
						{!! Form::select('install[date_format]', array_combine($dateFormats, $dateFormats), array_get($data, 'date_format'), ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>

			@event('view.installer.step.information', [$data])
		</div>

		<h1>@lang('installer::core.title.other')</h1>
		<div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.cache_type')</label>
					<div class="col-md-3">
						{!! Form::select('install[cache_driver]', array_combine($cacheDrivers, $cacheDrivers), array_get($data, 'cache_driver')) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.session_type')</label>
					<div class="col-md-3">
						{!! Form::select('install[session_driver]', array_combine($sessionDrivers, $sessionDrivers), array_get($data, 'session_driver')) !!}
					</div>
				</div>
			</div>

			@event('view.installer.step.other', [$data])
		</div>

		@event('view.installer.step.new', [$data])
	</div>
	{!! Form::close() !!}
</div>
