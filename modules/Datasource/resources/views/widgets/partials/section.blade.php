<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="section_id">Datasource</label>

		<div class="col-md-3">
			{!! Form::select('settings[section_id]', DatasourceManager::getSectionsFormHTML($widget->getAllowedSectionTypes()), $widget->getSectionId(), [
				'id' => 'section_id'
			]) !!}
		</div>
	</div>
</div>

@if (!$widget->isDatasourceSelected())
<div class="alert alert-warning alert-dark">
	{!! UI::icon('lightbulb-o fa-lg') !!} You need select hybrid section
</div>
@endif