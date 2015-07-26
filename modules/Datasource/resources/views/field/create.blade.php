<script type="text/javascript">
$(function() {
	$('#select-field-type').change(function() {
		var id = $(this).val();
		var $fieldset = $('#field-settings fieldset');

		$fieldset
				.attr('disabled', 'disabled')
				.hide()
				.filter('fieldset#field-' + id)
				.show()
				.removeAttr('disabled')
				.end();

		$('select', $fieldset).removeAttr('disabled')

	}).change();
});
</script>

{!! Form::model($field, [
	'route' => ['backend.datasource.field.create.post', $section->getId()],
	'class' => 'form-horizontal panel'
]) !!}

<div class="panel-heading" data-icon="exclamation-circle">
	<span class="panel-title">@lang('datasource::core.field.information')</span>
</div>

<div class="panel-body" id="filed-type">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">@lang('datasource::core.field.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', null, [
				'class' => 'slug-generator form-control',
				'id' => 'name', 'data-separator' => '_'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="key">@lang('datasource::core.field.key')</label>
		<div class="col-md-3">
			{!! Form::text('key', null, [
				'class' => 'slugify form-control',
				'id' => 'key'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="select-field-type">@lang('datasource::core.field.type')</label>
		<div class="col-md-3">
			{!! Form::select('type', FieldManager::getAvailableTypesForSelect(), null, ['id' => 'select-field-type']) !!}
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title" data-icon="cog">@lang('datasource::core.field.settings')</span>
</div>
<div class="panel-body">
	<div id="field-settings">
		@foreach (FieldManager::getEmptyObjects() as $type => $field)

		@if(!is_null($typeObject = $field->getType()))
		<fieldset id="field-{{ $type }}" disabled="disabled">

			@if(!is_null($editTemplate = $typeObject->getEditTemplate()))
			@include($editTemplate, compact('field', 'section', 'sections'))
			<hr class="panel-wide" />
			@endif

			@include('datasource::field.partials.required', compact('field', 'section', 'sections'))
			@include('datasource::field.partials.hint', compact('field'))
			@include('datasource::field.partials.position', compact('field'))
		</fieldset>
		@endif

		@endforeach
	</div>
</div>
<div class="panel-footer form-actions">
	{!! Form::button(trans('datasource::core.button.create_field'), [
		'type' => 'submit',
		'data-icon' => 'plus',
		'class' => 'btn btn-lg btn-primary btn-labeled'
	])!!}
</div>
{!! Form::close() !!}