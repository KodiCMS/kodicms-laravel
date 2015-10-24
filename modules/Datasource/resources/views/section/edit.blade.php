{!! Form::model($section, [
	'route' => ['backend.datasource.edit.post', $section],
	'class' => 'form-horizontal panel'
]) !!}

@include('datasource::section.partials.information', compact('section'))

<div class="panel-body">
	@include('datasource::section.partials.fields', compact('section', 'fields'))
</div>

{!! $section->getHeadline()->renderOrderSettings() !!}

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => ['backend.datasource.list', $section->getId()]])
</div>

{!! Form::close() !!}