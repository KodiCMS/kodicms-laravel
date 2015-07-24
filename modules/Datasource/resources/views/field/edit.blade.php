{!! Form::model($field, [
	'route' => ['backend.datasource.field.edit.post', $field],
	'class' => 'form-horizontal panel'
]) !!}

<div class="panel-heading" data-icon="exclamation-circle">
	<span class="panel-title">Field description</span>
</div>

<div class="panel-body" id="filed-type">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">Name</label>
		<div class="col-md-9">
			{!! Form::text('name', null, [
				'class' => 'slug-generator form-control',
				'id' => 'name', 'data-separator' => '_'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="key">Key</label>
		<div class="col-md-3">
			{!! Form::text(null, $field->getKey(), [
				'class' => 'form-control',
				'id' => 'key',
				'disabled'
			]) !!}
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title" data-icon="cog">Settings</span>
</div>

@if(!is_null($typeObject = $field->getType()))
<div class="panel-body">
	@if(!is_null($editTemplate = $typeObject->getEditTemplate()))
		@include($editTemplate, compact('field', 'section', 'sections'))
		<hr class="panel-wide" />
	@endif

	@include('datasource::field.partials.required', compact('field', 'section', 'sections'))
	@include('datasource::field.partials.hint', compact('field'))
	@include('datasource::field.partials.position', compact('field'))
</div>
@endif

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => ['backend.datasource.edit', $section->getId()]])
</div>

{!! Form::close() !!}