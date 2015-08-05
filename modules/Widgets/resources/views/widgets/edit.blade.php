{!! Form::open(['class' => 'panel form-horizontal', 'route' => ['backend.widget.edit.post', $widget]]) !!}

<div class="panel-heading panel-toggler" data-target-spoiler=".general-spoiler" data-hash="description">
	<span class="panel-title" data-icon="info-circle">@lang('widgets::core.title.general')</span>
</div>
<div class="panel-body panel-spoiler general-spoiler">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3">@lang('widgets::core.field.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', $widget->name, [
				'class' => 'form-control'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">@lang('widgets::core.field.description')</label>
		<div class="col-md-9">
			{!! Form::textarea('description', $widget->description, [
				'class' => 'form-control', 'rows' => 4
			]) !!}
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3">@lang('widgets::core.field.type')</label>
		<div class="col-md-9">
			{!! Form::text(NULL, $widget->type, [
				'class' => 'form-control', 'disabled', 'size' => 50
			]) !!}
		</div>
	</div>
</div>

@event('view.widget.edit', [$widget])

<div class="panel-heading">
	<span class="panel-title" data-icon="cogs">@lang('widgets::core.title.settings')</span>
</div>

{!! $settingsView !!}

@event('view.widget.edit.settings', [$widget])

<hr class="no-margin-vr" />

<div class="panel-body">
	@if (acl_check('widgets.location') and !$widget->isHandler())
		{!! link_to_route('backend.widget.location', trans('widgets::core.button.location'), [$widget], [
			'data-icon' => 'sitemap', 'class' => 'btn btn-sm btn-primary btn-labeled'
		]) !!}
	@endif

	@event('view.widget.edit.footer', [$widget])
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.widget.list'])
</div>
{!! Form::close() !!}