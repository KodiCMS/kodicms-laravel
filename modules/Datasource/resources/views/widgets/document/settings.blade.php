@include('datasource::widgets.partials.section')

@if ($widget->isDatasourceSelected())
<div class="panel-body">
	<div class="well well-sm">
		<div class="form-group">
			{!! Form::label('document_id', trans('datasource::widgets.document.settings.document_id'), ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-3">
				{!! Form::select('settings[document_id]', $widget->getIdFields(), $widget->document_id, [
				'class' => 'form-control', 'id' => 'document_id'
				]) !!}
			</div>
		</div>
		<hr />
		<div class="form-group">
			{!! Form::label('document_id_source_key', trans('datasource::widgets.document.settings.document_id_source_key'), ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-3">
				{!! Form::text('settings[document_id_source_key]', $widget->document_id_source_key, [
				'class' => 'form-control', 'id' => 'document_id_source_key'
				]) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('document_id_source', trans('datasource::widgets.document.settings.document_id_source'), ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-3">
				{!! Form::select('settings[document_id_source]', $widget->getFieldsSource(), $widget->document_id_source, [
					'class' => 'form-control', 'id' => 'document_id_source'
				]) !!}
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				{!! Form::hidden('settings[throw_404]', 0) !!}
				<label>{!! Form::switcher('settings[throw_404]', 1, $widget->throw_404) !!} @lang('datasource::widgets.document.settings.throw_404')</label>
			</div>
		</div>
	</div>
</div>
<div class="panel-heading">
	<div class="panel-title">@lang('datasource::widgets.document.settings.meta_fields')</div>
</div>
<div class="panel-body">
@foreach(['title', 'keywords', 'description'] as $metaKey)
	<div class="form-group">
		{!! Form::label('', trans('datasource::widgets.document.settings.meta_' . $metaKey), ['class' => 'control-label col-md-3']) !!}
		<div class="col-md-3">
			{!! Form::select("settings[meta_{$metaKey}]", $widget->getMetaFields(), $widget->getSetting("meta_{$metaKey}"), [
			'class' => 'form-control', 'id' => "meta_{$metaKey}"
			]) !!}
		</div>
	</div>
@endforeach
</div>

@include('datasource::widgets.partials.fields', compact('fields', 'widget'))
@endif