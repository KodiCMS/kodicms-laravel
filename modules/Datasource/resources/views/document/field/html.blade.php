<script>
$(function(){
	CMS.filters.switchOn('{{ $field->getDBKey() }}', '{{ $field->getWysiwyg() }}', {
		height: 200
	});
});
</script>

<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $field->getDBKey() }}">
		{{ $field->getName() }} @if($field->isRequired())*@endif
	</label>
	<div class="col-md-10 col-sm-9">
		{!! Form::textarea($field->getDBKey(), $value, [
			'class' => 'form-control', 'id' => $field->getDBKey(), 'data-height' => '265'
		]) !!}

		@if($hint = $field->getHint()): ?>
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>