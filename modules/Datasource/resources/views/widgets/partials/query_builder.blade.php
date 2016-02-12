<script>
	$(function() {
		$('#builder').queryBuilder({
			plugins: ['sortable'],
			filters: {!! json_encode($queryBuilderFields) !!},
			rules: {!! $widget->rules !!}
		});

		$('form').on('submit', function() {
			$('#inputRules').val(JSON.stringify($('#builder').queryBuilder('getRules')) || '{}');
		});
	});
</script>

<div class="panel-heading" data-icon="filter">
	<span class="panel-title">@lang('datasource::widgets.list.filtering.title')</span>
</div>
<div class="panel-body">
	<div id="builder"></div>
	{!! Form::hidden('settings[rules]', '', ['id' => 'inputRules']) !!}
</div>