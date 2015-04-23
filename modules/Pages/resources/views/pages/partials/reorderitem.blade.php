<li class="dd-item" data-id="{{ $page['id'] }}">
	<div class="dd-handle">
		{!! UI::icon(empty($childs) ? 'file-o' : 'folder-open-o') !!}
		<span class="title">{{ $page['title'] }}</span>

		@if (!empty($page['behavior_id']))
		&nbsp;&nbsp;{!! UI::label(studly_case($page['behavior_id']), 'default') !!}
		@endif
	</div>

	{!! $childs !!}
</li>