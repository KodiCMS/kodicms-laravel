<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		<div class="input-group">
			{!! Form::select($key, $field->getRelatedDocumentValue($document), [], [
				'id' => $key, 'class' => 'col-md-12 no-script',
				'data-related-id' => $relatedSection->getId(),
				'data-related-name' => $relatedSection->getName()
			]) !!}


			<div class="input-group-btn">
				@if (!empty($value))
				{!! link_to_route('backend.datasource.document.edit', trans('datasource::fields.has_one.view_document'), [$relatedSection->getId(), $value], [
					'data-icon' => 'building',
					'class' => 'btn btn-default popup fancybox.iframe',
					'data-target' => $key
				]) !!}
				@endif
				{!! link_to_route('backend.datasource.document.create', trans('datasource::fields.has_one.create_document'), [$relatedSection->getId()], [
					'data-icon' => 'plus',
					'class' => 'btn btn-success popup fancybox.iframe',
					'data-target' => $key
				]) !!}
			</div>
		</div>
	</div>

	@if($hint)
	<p class="help-block">{{ $hint }}</p>
	@endif
</div>
