<script>
$(function() {
	window.DataTable = $('#headline').DataTable({
		serverSide: true,
		stateSave: true,
		pageLength: 50,
		createdRow: function (row, data, index) {
			$(row)
					.attr('data-id', data.primaryKey)
					.find('input[name="document[]"]')
					.val(data.primaryKey);
		},
		ajax: {
			url: '/api.datasource.headline.datatables',
			data: function ( d ) {
				d.section_id = SECTION.id;

				return d;
			}
		},
		columns: [
			{
				data: null,
				orderable: false,
				defaultContent: '{!! Form::checkbox('document[]', null, null, ['class' => 'doc-checkbox']) !!}'
			},
				@foreach ($fieldParams as $key => $params)
				{
				data: '{{ $key }}',
				name: '{{ $key }}',
				title: '{{ $params['name'] }}',
				className: '{{ array_get($params, 'class') }}',
				type: '{{ array_get($params, 'type', 'string') }}',
				orderable: {{ array_get($params, 'orderable', 'true') }},
				searchable: {{ array_get($params, 'searchable', 'true') }},
			},
			@endforeach
		]
	});
});

function updateHeadline() {
	window.DataTable.draw();
}
</script>

<table class="table table-striped headline" id="headline">
	<colgroup>
		<col width="30px" />
		@foreach ($fieldParams as $params)
		<col @if (!is_null(array_get($params, 'width'))) width="{{ array_get($params, 'width', 200) }}px"; @endif />
		@endforeach
	</colgroup>
</table>