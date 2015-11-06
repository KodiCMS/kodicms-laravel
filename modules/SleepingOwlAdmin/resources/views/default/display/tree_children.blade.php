@foreach ($children as $entry)
	<li class="dd-item dd3-item {{ $reorderable ? '' : 'dd3-not-reorderable' }}" data-id="{{{ $entry->id }}}">
		@if ($reorderable)
			<div class="dd-handle dd3-handle"></div>
		@endif
		<div class="dd3-content">
			{{{ $entry->$value }}}
			@foreach ($controls as $control)
				<?php
					if ($control instanceof \KodiCMS\SleepingOwlAdmin\Interfaces\ColumnInterface)
					{
						$control->setModel($entry);
					}
				?>
				{!! $control !!}
			@endforeach
		</div>
		@if ($entry->children->count() > 0)
			<ol class="dd-list">
				@include(app('sleeping_owl.template')->getTemplateViewPath('display.tree_children'), ['children' => $entry->children])
			</ol>
		@endif
	</li>
@endforeach
