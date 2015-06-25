{!! Form::model($emailEvent, [
	'route' => [$action, $emailEvent],
	'class' => 'form-horizontal panel'
]) !!}
<div class="panel-heading">
	<span class="panel-title">@lang('email::core.tab.general')</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">@lang('email::core.field.events.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', NULL, [
				'class' => 'form-control', 'id' => 'name'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="code">@lang('email::core.field.events.code')</label>
		<div class="col-md-9">
			@if ($emailEvent->exists)
				{!! Form::text('code', NULL, [
					'class' => 'form-control', 'id' => 'code', 'readonly', 'disabled'
				]) !!}
			@else
				{!! Form::text('code', NULL, [
					'class' => 'form-control slug', 'id' => 'code', 'data-separator' => '_'
				]) !!}
			@endif
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title">@lang('email::core.tab.fields')</span>
</div>
<div class="panel-body">
	@include('cms::helpers.rows', [
		'field' => 'fields',
		'data'  => $emailEvent->fields,
	])
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.email.event.list'])
</div>
{!! Form::close() !!}
@include('email::email.event.templates', ['emailEvent' => $emailEvent])