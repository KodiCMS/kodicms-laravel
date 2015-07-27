@include('datasource::widgets.partials.section')

@if ($widget->isDatasourceSelected())
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="doc_uri">Document page (URI)</label>
		<div class="col-md-9">
			{!! Form::text('settings[document_uri]', $widget->document_uri, [
				'class' => 'form-control', 'id' => 'document_uri'
			]) !!}
			<p class="help-block">Example <code>/news/:id, /news/:field_name</code> <code>/profile/:author.username</code></p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				{!! Form::hidden('settings[throw_404]', 0) !!}
				<label>{!! Form::switcher('settings[throw_404]', 1, $widget->throw_404) !!} Generate error 404 when page has no content</label>
				<br />
				{!! Form::hidden('settings[sort_by_rand]', 0) !!}
				<label>{!! Form::switcher('settings[sort_by_rand]', 1, $widget->sort_by_rand) !!} Select random documents</label>
			</div>
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="search_key">Search key</label>
		<div class="col-md-9">
			{!! Form::text('settings[search_key]', $widget->search_key, [
				'class' => 'form-control', 'id' => 'search_key'
			]) !!}
		</div>
	</div>
</div>

@include('datasource::widgets.partials.fields', compact('fields', 'widget'))
@include('datasource::widgets.partials.ordering', compact('fields', 'ordering', 'widget'))
@include('datasource::widgets.partials.filtering', compact('fields', 'widget'))
@endif