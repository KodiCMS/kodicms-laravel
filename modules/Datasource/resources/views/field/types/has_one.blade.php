<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="ds_id">Datasource</label>

		<div class="col-md-3">
			@if($field->exists)
				{!! Form::select('', $field->getSectionList(), $field->getRelatedSectionId(), [
					'id' => 'section_id', 'disabled'
				]) !!}
			@else
			{!! Form::select('related_ds', $field->getSectionList(), $field->getRelatedSectionId(), [
				'id' => 'section_id'
			]) !!}
			@endif
		</div>
	</div>
</div>