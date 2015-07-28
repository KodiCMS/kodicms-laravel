<script>
$(function() {
	$('#headline').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: '/api.datasource.headline.datatables',
			data: function ( d ) {
				d.section_id = SECTION.id;

				return d;
			},
			dataSrc: function(response) {
				return response.content;
			}
		},
		columns: [
			@foreach ($fieldParams as $key => $params)
			{ data: '{{ $key }}', name: '{{ $key }}' },
			@endforeach
		]
	});
});
</script>

<table class="table table-striped" id="headline">
	<thead>
	<tr>
		@foreach ($fieldParams as $params)
		<th class="{{ array_get($params, 'class') }}">{{ array_get($params, 'name') }}</th>
		@endforeach
	</tr>
	</thead>
	<tbody></tbody>
</table>