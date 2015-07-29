<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="related_section_id">@lang('datasource::fields.many_to_many.datasource')</label>

		<div class="col-md-3">
			@if($field->exists)
				{!! Form::select('', $field->getSectionList(), $field->getRelatedSectionId(), [
				'id' => 'section_id', 'disabled'
				]) !!}
			@else
				{!! Form::select('related_section_id', $field->getSectionList(), $field->getRelatedSectionId(), [
				'id' => 'section_id'
				]) !!}
			@endif
		</div>
	</div>
</div>