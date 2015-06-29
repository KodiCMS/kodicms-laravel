@if ($failed)
<div class="alert alert-danger alert-dark no-margin-b padding-sm-vr">
	{!! UI::icon('exclamation-triangle fa-lg') !!} {{ __('Kohana may not work correctly with your environment.') }}
</div>
@else
<div class="alert alert-success alert-dark no-margin-b padding-sm-vr">
	{!! UI::icon('check fa-lg') !!} {{ __('Your environment passed all requirements.') }}
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
	<span class="panel-title">{{ __( 'Optional Tests' ) }}</span>
</div>

<p class="alert alert-info alert-dark no-margin-b padding-sm-vr">
	{!! UI::icon('lightbulb-o fa-lg') !!} {{  __('The following extensions are not required to run the Kohana core, but if enabled can provide access to additional classes.') }}
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
		</tbdoy>
	</table>
</div>
@endif