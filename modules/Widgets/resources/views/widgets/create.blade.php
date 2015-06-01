{!! Form::open(['class' => 'form-horizontal panel']) !!}
<div class="panel-heading">
	<span class="panel-title" data-icon="info-circle">@lang('widgets::core.title.general')</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3">@lang('widgets::core.field.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', NULL, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">@lang('widgets::core.field.description')</label>
		<div class="col-md-9">
			{!! Form::textarea('description', NULL, ['class' => 'form-control', 'rows' => 4]) !!}
		</div>
	</div>
</div>
<hr />
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('widgets::core.field.type')</label>
		<div class="col-md-6">
			{!! Form::select('type', $types, 'html', ['class' => 'form-control', 'size' => 10]) !!}
		</div>
	</div>
</div>
<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.widget.list'])
</div>
{!! Form::close() !!}