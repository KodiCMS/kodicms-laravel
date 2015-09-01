@include('datasource::widgets.partials.section')

@if ($widget->isDatasourceSelected())
	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-md-3" for="field_id">Field</label>
			<div class="col-md-9">
				{!! Form::select('settings[field_id]', $fields, $widget->getSetting('field_id'), [
					'id' => 'field_id'
				]) !!}
			</div>
		</div>
	</div>

	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-md-3">Min font-size (px)</label>
			<div class="col-md-2">
				<div class="input-group">
					{!! Form::text('settings[min_size]', $widget->getSetting('min_size'), ['class' => 'form-control']) !!}
					<div class="input-group-addon">px</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Max font-size (px)</label>
			<div class="col-md-2">
				<div class="input-group">
					{!! Form::text('settings[max_size]', $widget->getSetting('max_size'), ['class' => 'form-control']) !!}
					<div class="input-group-addon">px</div>
				</div>
			</div>
		</div>

		<hr />

		<div class="form-group">
			<label class="control-label col-md-3">Order by</label>
			<div class="col-md-4">
				{!! Form::select('settings[order_by]', [
					'name_asc' => 'Tag name A &rarr; Z',
					'name_desc' => 'Tag name Z &rarr; A',
					'count_asc' => 'Count tags 0 &rarr; 9',
					'count_desc' => 'Count tags 9 &rarr; 0',
				], $widget->getSetting('order_by'), ['class' => 'form-control']) !!}
			</div>
		</div>
	</div>
@endif