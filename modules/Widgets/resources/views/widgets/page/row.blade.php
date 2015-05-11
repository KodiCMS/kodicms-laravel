<tr>
	<th>
		@if (acl_check('widgets.edit'))
		{!! link_to_route('backend.widget.edit', $widget->getName(), [$widget->getId()]) !!}
		@else
		{!! UI::icon('lock') !!} {{ $widget->getName() }}
		@endif

		@if ($widget->getDescription())
		<p class="muted">{{ $widget->getDescription() }}</p>
		@endif
	</th>
	<td>
		@if(acl_check('widgets.location'))
		{!! Form::text('widget[' . $widget->getId() . '][position]', (int) $position, ['maxlength' => 4, 'size' => 4, 'class' => 'form-control text-right']) !!}
		@else
		<span class="label label-success">{{ __('Position: :position', [':block_name' => $block]) }}</span>
		@endif
	</td>
	<td>
		@if(acl_check('widgets.location'))
		<div class="input-group">
			{!! Form::hidden('widget[' . $widget->getId() . '][block]', ! empty($block) ? $block : 0, ['class' => 'widget-blocks', 'data-layout' => $page->layout_file]) !!}

			<div class="input-group-btn">
				{!! link_to_route('backend.widget.location', '', [$widget->getId()], [
					'data-icon' => 'sitemap', 'class' => 'btn btn-xs btn-primary popup fancybox.iframe'
				]) !!}
			</div>
		</div>
		@else
		<span class="label label-success">{{ __('Block: :block_name', [':block_name' => $block]) }}</span>
		@endif
	</td>
</tr>