<script>
$(function () {
	$('#copy-from-header{{ $key }}').on('click', function (e) {
		$(this).parent().prev().val(getSlug($('input[name="{{ $field->getSection()->getDocumentTitleKey() }}"]').val(), {separator: '{{ $field->getSeparator() }}'})).keyup();
		e.preventDefault();
	});
})
</script>

<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		<div class="input-group">
			{!! Form::text($key, $value, [
				'class' => 'form-control slugify ' . ($field->fromDocumentTitle() ? 'from-header' : ''),
				'id' => $key, 'maxlength' => 255,
				'data-separator' => $field->getSeparator()
			]) !!}
			<div class="input-group-btn">
				{!! Form::button('', [
					'class' => 'btn btn-default',
					'data-icon' => 'magnet',
					'id' => 'copy-from-header' . $key
				]) !!}
			</div>
		</div>

		@if($field->isUnique())
		<span class="help-inline text-muted">@lang('datasource::fields.slug.must_be_unique')</span>
		@endif

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>