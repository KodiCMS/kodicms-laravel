<script>
function updateHeadline(keyword) {
	var data = {
		page: $.query.get('page'),
		section_id: SECTION.id
	};

	Api.get('/api.datasource.headline', _.extend(data, keyword), function(response) {
		if(response) {
			$('.headline').html(response);
			CMS.ui.init('icon');
		}
	});
}
</script>

@if(count($items) > 0)
<table class="table table-striped">
	<colgroup>
		<col width="30px" />
		@foreach ($fieldParams as $params)
		<col @if (!is_null(array_get($params, 'width'))) width="{{ $params['width'] }}px"; @endif />
		@endforeach
	</colgroup>
	<thead>
		<tr>
			<th></th>
			@foreach ($fieldParams as $params)
			<th class="{{ array_get($params, 'class') }}">{{ array_get($params, 'name') }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($items as $document)
		<tr data-id="{{ $document->getId() }}">

			<td class="row-checkbox">
				{!! Form::checkbox('document[]', $document->getId(), null, ['class' => 'doc-checkbox']) !!}
			</td>

			@foreach ($fieldParams as $key => $params)

			@if($document->hasField($key))
			@if(array_get($params, 'type') == 'link')
			<td class="row-{{ $key }} {{ array_get($params, 'class') }}">
				<strong>
					{!! HTML::link($document->getEditLink(), $document->getHeadlineValue($key)) !!}
				</strong>
			</td>
			@else
			<td class="row-{{ $key }} {{ array_get($params, 'class') }}">{!! $document->getHeadlineValue($key) !!}</td>
			@endif

			@else
				<td class="row-{{ $key }} {{ array_get($params, 'class') }}"></td>
			@endif

			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>

@else
	<div class="panel-body">
		<h3>@lang('datasource::core.messages.empty')</h3>
	</div>
@endif

{!! $items->render() !!}