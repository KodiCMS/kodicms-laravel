{!! Form::open(['class' => 'panel']) !!}
	<table class="table table-primary table-striped">
		<colgroup>
			<col width="300px" />
			<col width="100px" />
			<col width="20px" />
			<col />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('widgets::core.field.blocks')</th>
			<th>@lang('widgets::core.field.position')</th>
			<th></th>
			<th>@lang('widgets::core.field.page')</th>
		</tr>
		</thead>
		<tbody>
		<?php echo recurse_pages($pages, 0, $layoutBlocks, $widgetBlocks, $blocksToExclude); ?>
		</tbody>
	</table>
	<hr />
	<div class="panel-body no-padding-vr">
		<div class="form-group form-inline">
			<div class="input-group">
				{!! Form::text('select_for_all', NULL, ['class' => 'form-control']) !!}
				<div class="input-group-btn">
				{!! Form::button(trans('widgets::core.button.select_blocks'), [
					'data-icon' => 'level-up fa-flip-horizontal',
					'class' => 'btn btn-warning btn-labeled',
					'id' => 'select_for_all'
				]) !!}
				</div>
			</div>

			@if (acl_check('layout.rebuild'))
			{!! Form::button(trans('widgets::core.button.rebuild_blocks'), [
				'data-icon' => 'refresh',
				'class' => 'btn btn-xs btn-info btn-labeled',
				'data-api-url' => '/api.layout.rebuild'
			]) !!}
			@endif
		</div>
	</div>
	<div class="panel-footer form-actions">
		{!! Form::button(trans('cms::core.button.update'), [
			'type' => 'submit',
			'class' => 'btn-lg btn-primary btn btn-labeled',
			'data-icon' => 'check',
			'data-hotkeys' => 'ctrl+s'
		]) !!}
	</div>
{!! Form::close() !!}

<?php
function recurse_pages($pages, $spaces = 0, $layoutsBlocks = [], $pageWidgets = [], $pagesWidgets = [])
{
	$data = '';
	foreach ($pages as $page)
	{
		// Блок
		$currentBlock = array_get($pageWidgets, $page['id'].'.0');
		$currentPosition = array_get($pageWidgets, $page['id'].'.1');

		$data .= '<tr data-id="'.$page['id'].'" data-parent-id="'.$page['parent_id'].'">';
		$data .= '<td>';

		if (!empty($page['childs']))
		{
			$data .= '<div class="input-group">';
		}

		$data .= Form::select('blocks['.$page['id'].'][block]', [], $currentBlock, [
			'class' => 'widget-blocks form-control',
			'data-layout' => $page['layout_file'],
			'data-value' => $currentBlock
		]);

		if (!empty($page['childs']))
		{
			$data .= "<div class=\"input-group-btn\">" . Form::button(NULL, [
				'data-icon' => 'level-down',
				'class' => 'set_to_inner_pages btn btn-warning',
				'title' => trans('widgets::core.button.select_childs')
			]) . '</div></div>';
		}

		$data .= '</td><td>';
		$data .= Form::text('blocks[' . $page['id'] . '][position]', (int) $currentPosition, ['maxlength' => 4, 'size' => 4, 'class' => 'form-control text-right widget-position']);
		$data .= '</td><td></td>';

		if (acl_check('page.edit'))
		{
			$data .= '<th>' . str_repeat("-&nbsp;", $spaces) . link_to_route('backend.page.edit', $page['title'], [$page['id']]) . '</th>';
		}
		else
		{
			$data .= '<th>' . str_repeat("-&nbsp;", $spaces) . $page['title'] . '</th>';
		}

		$data .= '</tr>';

		if (!empty($page['childs']))
		{
			$data .= recurse_pages($page['childs'], $spaces + 5, $layoutsBlocks, $pageWidgets, $pagesWidgets);
		}
	}
	return $data;
}
?>