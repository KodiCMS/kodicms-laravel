<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		@if($field->getDisplayStyle() == \KodiCMS\Datasource\Fields\Primitive\Boolean::STYLE_SELECT)
		<div class="col-md-3 no-padding-hr">
			{!! Form::select($key, ['No', 'Yes'], $value) !!}
		</div>
		@elseif($field->getDisplayStyle() == \KodiCMS\Datasource\Fields\Primitive\Boolean::STYLE_CHECKBOX)
		<div class="checkbox">
			{!! Form::hidden($key, 0) !!}
			<label>{!! Form::switcher($key, 1, $value == 1) !!}</label>
		</div>
		@else
		<label class="radio radio-inline">
			{!! Form::radio($key, 1, $value == 1) !!} Yes
		</label>
		<label class="radio radio-inline">
			{!! Form::radio($key, 0, $value == 0) !!} No
		</label>
		@endif

		@if($hint)
			<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>