<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		<div class="input-group">
			{!! Form::select($key, $field->getRelatedDocumentValue($document), $value, [
				'id' => $key, 'class' => 'col-md-12 no-script', 'data-related-section' => $relatedSection->getId()
			]) !!}

			@if (!empty($value))
			<div class="input-group-btn">
				{!! link_to_route('backend.datasource.document.edit', 'View', [$relatedSection->getId(), $value], [
					'data-icon' => 'building',
					'class' => 'btn btn-default popup fancybox.iframe',
					'data-target' => $key
				]) !!}

				{!! link_to_route('backend.datasource.document.create', 'Create', [$relatedSection->getId()], [
					'data-icon' => 'plus',
					'class' => 'btn btn-success popup fancybox.iframe',
					'data-target' => $key
				]) !!}
			</div>
			@endif
		</div>
	</div>

	@if($hint)
	<p class="help-block">{{ $hint }}</p>
	@endif
</div>
