@if ($failed)
<div class="alert alert-danger alert-dark no-margin-b padding-sm-vr">
	{!! UI::icon('exclamation-triangle fa-lg') !!}
	@lang('installer::core.messages.environment_failed')
</div>
@else
<div class="alert alert-success alert-dark no-margin-b padding-sm-vr">
	{!! UI::icon('check fa-lg') !!}
	@lang('installer::core.messages.environment_passed')
</div>
@endif

<div id="env_test">
	<table class="table table-hover">
		<colgroup>
			<col width="300px" />
			<col />
		</colgroup>
		<tbody>
		@foreach ($tests as $test)
		<tr class="{{ $test['passed'] ? '' : 'danger' }}">
			<th>{{ array_get($test, 'title') }}</th>
			<td>
				<div class="{{ $test['passed'] ? 'text-success' : '' }}">{{ $test['message'] }}</div>
				<?php $notice = array_get($test, 'notice'); ?>

				@if (is_array($notice))
				<br />
				<div class="{{ array_get($notice, 'class') }} padding-xs-vr no-margin-b">
					{!! UI::icon('lightbulb-o fa-lg') !!}  {{ array_get($notice, 'message') }}
				</div>
				@endif
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>

@if($optional)
<div class="panel-heading">
	<span class="panel-title">@lang('installer::core.title.environment_optional')</span>
</div>

<p class="alert alert-info alert-dark no-margin-b padding-sm-vr">
	{!! UI::icon('lightbulb-o fa-lg') !!}
	@lang('installer::core.messages.environment_optional')
</p>
<div id="optional_test">
	<table class="table table-striped">
		<colgroup>
			<col width="300px" />
			<col />
		</colgroup>
		<tbody>
		@foreach($optional as $test)
		<tr class="{{ $test['passed'] ? '' : 'info' }}">
			<th>{{ array_get($test, 'title') }}</th>
			<td>
				<div class="{{ $test['passed'] ? 'text-success' : '' }}">{{ $test['message'] }}</div>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
@endif