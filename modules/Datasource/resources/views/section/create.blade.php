{!! Form::model($section, [
	'route' => ['backend.datasource.create.post', $typeObject->getType()],
	'class' => 'form-horizontal panel'
]) !!}

@include('datasource::section.partials.information', ['section' => $section])

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.datasource.list'])
</div>

{!! Form::close() !!}