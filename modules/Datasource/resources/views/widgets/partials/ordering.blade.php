<?php
$orderedFields = [];
foreach ($ordering as $data)
{
	$orderedFields[key($data)] = $data[key($data)];
}

$orderableFields = $ids = $selectedFields = [];
foreach ($fields as $field)
{
	if (!$field->isOrderable())
	{
		continue;
	}

	if (!isset($orderedFields[$field->getDBKey()]))
	{
		$orderableFields[$field->getDBKey()] = $field->getName();
	}
	else
	{
		$ids[$field->getDBKey()] = $field->getName();
	}
}

foreach ($ordering as $data)
{
	if (isset($ids[key($data)]))
	{
		$selectedFields[key($data)] = (($data[key($data)] == \KodiCMS\Widgets\Contracts\WidgetPaginator::ORDER_ASC) ? '↑' : '↓') . ' ' . $ids[key($data)];
	}
}
?>

<script>
	$(function() {
		var sf = $('#sf'),
			af = $('#af'),
			sf_cont = $('#sf-cont'),
			input = $('<input />');

		$('.sorting-btns button').click(function() { return false; });

		$('.btn-add').click(function() {
			var selected = $('option:selected', af).remove();

			$(sf).append(selected.text('↑ ' + selected.text()))

			input.clone().attr({
				name: 'settings[ordering][]['+ selected.val() +']',
				value: 'ASC',
				type: 'hidden',
				id: 'sf_' + selected.val()
			}).appendTo(sf_cont);
		});

		$('.btn-remove').click(function() {
			var selected = $('option:selected', sf).remove();

			$(af).append(selected.text(selected.text().substr(2)))
			$('#sf_' + selected.val()).remove();
		});

		$('.btn-order').click(function() {
			var selected = $('option:selected', sf);

			if(selected.text().indexOf('↑') > -1 ) {
				selected.text(selected.text().replace('↑', '↓'));
				$('#sf_' + selected.val()).val('DESC');
			} else {
				selected.text(selected.text().replace('↓', '↑'));
				$('#sf_' + selected.val()).val('ASC');
			}
		});

		$('.btn-move').click(function() {
			var step = $(this).hasClass('up') ? -1 : 1;

			var index = $('option:selected', sf).index();

			to = index + step;

			if(index < 0 || to < 0 || !sf[0].options[to]) return;

			$('option:selected', sf).swapWith($('option:eq('+to+')', sf));
			$('option:eq('+to+')', sf).attr('selected', 'selected');
			$('input[name^="settings[ordering]"]', sf_cont).eq(index).swapWith($('input[name^="settings[ordering]"]', sf_cont).eq(to));
		});
	});

	jQuery.fn.swapWith = function(to) {
		return this.each(function() {
			var copy_to = $(to).clone(true);
			var copy_from = $(this).clone(true);
			$(to).replaceWith(copy_from);
			$(this).replaceWith(copy_to);
		});
	};
</script>

<div id="sorting_block">
	<div class="panel-heading">
		<span class="panel-title" data-icon="sort-alpha-desc">@lang('datasource::core.ordering.title')</span>
	</div>
	<table class="table table-noborder table-primary">
		<colgroup>
			<col width="220px" />
			<col width="110px" />
			<col width="220px" />
			<col />
		</colgroup>
		<thead>
		<tr>
			<td>@lang('datasource::core.ordering.order_by')</td>
			<td></td>
			<td>@lang('datasource::core.ordering.fields')</td>
			<td></td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td id="sf-cont">
				{!! Form::select('sf', $selectedFields, NULL, [
					'size' => 5, 'class' => 'no-script form-control', 'id' => 'sf'
				]) !!}

				@foreach($ordering as $data)
				{!! Form::hidden('settings[ordering][]['.key($data).']', $data[key($data)], [
					'id' => 'sf_' . key($data)
				]) !!}
				@endforeach
			</td>
			<td class="sorting-btns">
				<div class="btn-group btn-group-vertical">
					{!! Form::button('Add', [
						'class' => 'btn btn-default btn-add btn-xs',
						'data-icon' => 'plus'
					]) !!}
					{!! Form::button('Remove', [
						'class' => 'btn btn-default btn-remove btn-xs',
						'data-icon' => 'minus'
					]) !!}
					{!! Form::button('Move up', [
						'class' => 'btn btn-default btn-move btn-xs',
						'data-icon' => 'angle-up'
					]) !!}
					{!! Form::button('Move down', [
						'class' => 'btn btn-default btn-move btn-xs',
						'data-icon' => 'angle-down'
					]) !!}
					{!! Form::button('Asc / Desc', [
						'class' => 'btn btn-default btn-order btn-xs',
						'data-icon' => 'sort'
					]) !!}
				</div>
			</td>
			<td>
				{!! Form::select('af', $orderableFields, null, [
					'size' => 5, 'class' => 'no-script form-control', 'id' => 'af'
				]) !!}
			</td>
		</tr>
		</tbody>
	</table>
</div>
