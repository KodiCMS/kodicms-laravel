<div class="container-fluid margin-sm-vr">
	<h1 class="pull-left no-margin-t">{{ $title }}</h1>
	{!! Form::open([
		'class' => 'form-horizontal'
	]) !!}
	<div id="wizard" class="wizard">
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
						<label class="control-label col-md-3" for="db_host">@lang('installer::core.field.db_server')</label>
						<div class="col-md-9">
							{!! Form::text('install[db_host]', array_get($data, 'db_host'), [
								'class' => 'form-control col-sm-auto', 'id' => 'db_host', 'required'
							]) !!}
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_username">@lang('installer::core.field.db_username')</label>
						<div class="col-md-9 form-inline">
							{!! Form::text('install[db_username]', array_get($data, 'db_username'), [
								'class' => 'form-control col-sm-auto', 'id' => 'db_user', 'required'
							]) !!}
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_password">@lang('installer::core.field.db_password')</label>
						<div class="col-md-9">
							{!! Form::password('install[db_password]', [
								'class' => 'form-control col-sm-auto', 'id' => 'db_password'
							]) !!}

							<p class="help-block">@lang('installer::core.messages.database_no_password')</p>
						</div>
					</div>
				</div>
				<div class="form-group well well-sm">
					<label class="control-label col-md-3" for="db_database">@lang('installer::core.field.db_database')</label>
					<div class="col-md-9 form-inline">
						{!! Form::text('install[db_database]', array_get($data, 'db_database'), [
							'class' => 'form-control col-sm-auto', 'id' => 'db_database', 'required'
						]) !!}

						<p class="help-block">@lang('installer::core.messages.database_name_inforamtion')</p>
					</div>

					<div class="col-md-offset-3 col-md-9">
						<hr />
						<label class="checkbox btn btn-danger btn-checkbox">
							{!! Form::checkbox('install[empty_database]', 1, (bool) array_get($data, 'empty_database'), ['class' => 'px']) !!}
							<span class="lbl">@lang('installer::core.button.empty_database')</span>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="db_preffix">@lang('installer::core.field.db_preffix')</label>
					<div class="col-md-9 form-inline">
						{!! Form::text('install[db_prefix]', array_get($data, 'db_prefix'), [
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
					<label class="control-label col-md-3" for="site_name">@lang('installer::core.field.site_title')</label>
					<div class="col-md-9">
						{!! Form::text('install[site_name]', array_get($data, 'site_name'), [
							'class' => 'form-control', 'id' => 'site_name', 'required'
						]) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="admin_dir_name">@lang('installer::core.field.admin_dir_name')</label>
					<div class="col-md-9 form-inline">
						<div class="input-group">
							<div class="input-group-addon">{{ url() }}/</div>
							{!! Form::text('install[admin_dir_name]', array_get($data, 'admin_dir_name'), [
							'class' => 'form-control', 'id' => 'admin_dir_name', 'size' => 20, 'maxlength' => 20, 'required'
							]) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="url_suffix">@lang('installer::core.field.url_suffix')</label>
					<div class="col-md-9 form-inline">
						<div class="input-group">
							<div class="input-group-addon">{{ url() }}/news</div>
							{!! Form::text('install[url_suffix]', array_get($data, 'url_suffix'), [
							'class' => 'form-control', 'id' => 'url_suffix', 'size' => 6, 'maxlength' => 6
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
						{!! Form::select('install[timezone]', $timezones, array_get($data, 'timezone'), ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3">@lang('installer::core.field.date_format')</label>
					<div class="col-md-3">
						{!! Form::select('install[date_format]', $dateFormats, array_get($data, 'date_format'), ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
			<?php /* Observer::notify('installer_step_site_imformation', $data); */ ?>
		</div>

		<?php /*
		<h1>{{ __('Other') }}</h1>
		<div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3">{{ __('Cache type') }}</label>
					<div class="col-md-3">
						<?php echo Form::select('install[cache_type]', $cacheTypes, array_get($data, 'cache_type')); ?>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Session storage'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[session_type]', $session_types, array_get($data, 'session_type')); ?>
					</div>
				</div>
			</div>

			<?php Observer::notify('installer_step_other', $data); ?>
		</div>

		<?php Observer::notify('installer_step_new', $data); ?>

		*/ ?>
	</div>
	{!! Form::close() !!}
</div>