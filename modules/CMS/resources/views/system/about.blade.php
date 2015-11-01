<div class="panel tabbable">
	<div class="panel-heading">
		<span class="panel-title">@lang('cms::system.tab.about.general')</span>
	</div>
	<table class="table table-striped">
		<colgroup>
			<col width="200px" />
			<col />
		</colgroup>
		<tbody>
		<tr>
			<th>@lang('cms::system.label.about.cms')</th>
			<td>{{ CMS::NAME }} v{{ CMS::VERSION }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.framework')</th>
			<td>{!! HTML::image('cms/images/laravel-logo.png', null, ['style' => 'height: 17px']) !!} <strong style="color: #E74430">Laravel</strong> v{{ App::version() }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.php_version')</th>
			<td>{{ PHP_VERSION }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.environment')</th>
			<td>{{ env('APP_ENV') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.host')</th>
			<td>{{ array_get($_SERVER, 'HTTP_HOST') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.server')</th>
			<td>{{ array_get($_SERVER, 'SERVER_SOFTWARE') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.cache_driver')</th>
			<td>{{ env('CACHE_DRIVER') }}</td>
		</tr>
		<tr>
			<th>@lang('cms::system.label.about.session_driver')</th>
			<td>{{ env('SESSION_DRIVER') }}</td>
		</tr>
		</tbody>
	</table>

	@if (acl_check('system.phpinfo') and function_exists('phpinfo'))
	<div class="panel-heading">
		<span class="panel-title">@lang('cms::system.tab.about.php_info')</span>
	</div>
	<div class="panel-body no-padding">
		<iframe src="{{ route('backend.phpinfo') }}" width="100%" height="500px" id="phpinfo" style="border: 0"></iframe>
	</div>
	@endif

	@event('view.system.about')
</div>