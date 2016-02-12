@include('datasource::widgets.partials.section')

@if ($widget->isDatasourceSelected())
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="doc_uri">@lang('datasource::widgets.list.settings.document_uri')</label>
		<div class="col-md-9">
			{!! Form::text('settings[document_uri]', $widget->document_uri, [
				'class' => 'form-control', 'id' => 'document_uri'
			]) !!}
			<p class="help-block">
				@lang('datasource::widgets.list.settings.example', ['text' => "<code>/news/:id, /news/:field_name</code> <code>/profile/:author.username</code>"])
			</p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				{!! Form::hidden('settings[sort_by_rand]', 0) !!}
				<label>{!! Form::switcher('settings[sort_by_rand]', 1, $widget->sort_by_rand) !!} @lang('datasource::widgets.list.settings.select_random_documents')</label>
			</div>
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="search_key">@lang('datasource::widgets.list.settings.search_key')</label>
		<div class="col-md-9">
			{!! Form::text('settings[search_key]', $widget->search_key, [
				'class' => 'form-control', 'id' => 'search_key'
			]) !!}
		</div>
	</div>
</div>

@include('datasource::widgets.partials.fields', compact('fields', 'widget'))
@include('datasource::widgets.partials.ordering', compact('fields', 'ordering', 'widget'))
@include('datasource::widgets.partials.query_builder')
@endif