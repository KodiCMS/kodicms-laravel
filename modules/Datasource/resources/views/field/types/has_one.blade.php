<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="related_section_id">@lang('datasource::fields.has_one.datasource')</label>

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

	<div class="form-group">
		<label class="control-label col-md-3" for="relation_type">@lang('datasource::fields.has_one.relation_type')</label>

		<div class="col-md-3">
			@if($field->exists)
				{!! Form::select('', $field->getRelationTypes(), $field->getRelationType(), [
					'id' => 'relation_type', 'disabled'
				]) !!}
			@else
				{!! Form::select('settings[relation_type]', $field->getRelationTypes(), $field->getRelationType(), [
					'id' => 'relation_type'
				]) !!}
			@endif
		</div>
	</div>
</div>