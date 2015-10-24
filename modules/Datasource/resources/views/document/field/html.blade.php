<script>
$(function(){
	CMS.filters.switchOn('{{ $key }}', '{{ $field->getWysiwyg() }}', {
		height: 200
	});
});
</script>

<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>
	<div class="col-md-10 col-sm-9">
		{!! Form::textarea($key, $value, [
			'class' => 'form-control', 'id' => $key, 'data-height' => '265'
		]) !!}

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>