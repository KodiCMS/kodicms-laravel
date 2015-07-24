<div id="sorting_block">
	<div class="panel-heading">
		<span class="panel-title" data-icon="sort-alpha-desc">Documents ordering</span>
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
			<td>Order by</td>
			<td></td>
			<td>Fields</td>
			<td></td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td id="sf-cont">
				{!! Form::select('sf', $selectedFields, NULL, [
					'size' => 5, 'class' => 'no-script form-control', 'id' => 'sf'
				]) !!}

				@foreach($doc_order as $data)
				{!! Form::hidden('doc_order[]['.key($data).']', $data[key($data)], [
							'id' => 'sf_' . key($data)
				]) !!}
				@endforeach
			</td>
			<td class="sorting-btns">
				<div class="btn-group btn-group-vertical">
					<?php echo UI::button(__('Add'), array(
							'class' => 'btn-default btn-add btn-xs',
							'icon' => UI::icon('plus')
					)); ?>
					<?php echo UI::button(__('Remove'), array(
							'class' => 'btn-default btn-remove btn-xs',
							'icon' => UI::icon('minus')
					)); ?>
					<?php echo UI::button(__('Move up'), array(
							'class' => 'btn-default btn-move up btn-xs',
							'icon' => UI::icon('angle-up')
					)); ?>
					<?php echo UI::button(__('Move down'), array(
							'class' => 'btn-default btn-move down btn-xs',
							'icon' => UI::icon('angle-down')
					)); ?>
					<?php echo UI::button(__('Asc / Desc'), array(
							'class' => 'btn-default btn-order btn-xs',
							'icon' => UI::icon('sort')
					)); ?>
				</div>
			</td>
			<td>
				{!! Form::select('af', $available_fields, null, [
					'size' => 5, 'class' => 'no-script form-control', 'id' => 'af'
				]) !!}
			</td>
		</tr>
		</tbody>
	</table>
</div>