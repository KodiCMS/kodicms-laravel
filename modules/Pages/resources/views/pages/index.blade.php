<div id="page-tree" class="panel">
	<div class="panel-heading">
		@if (acl_check('page.add'))
		{!! link_to_route('backend.page.create', trans('pages::core.button.add'), [], [
			'class' => 'btn btn-default btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif

		@if (acl_check('page.sort'))
		{!! Form::button(trans('pages::core.button.reorder'), [
			'class' => 'btn btn-primary btn-sm btn-labeled',
			'data-icon' => 'sort',
			'data-hotkeys' => 'ctrl+s',
			'id' => 'pageMapReorderButton'
		]) !!}
		@endif

		<div class="panel-heading-controls hidden-xs hidden-sm">
			@include('pages::pages.partials.search')
		</div>
	</div>

	<table id="page-tree-header" class="table table-primary">
		<thead>
		<tr class="row">
			<th class="col-xs-7">@lang('pages::core.field.page')</th>
			<th class="col-xs-2 text-right">@lang('pages::core.field.date')</th>
			<th class="col-xs-2 text-right">@lang('pages::core.field.status')</th>
			<th class="col-xs-1 text-right">@lang('pages::core.field.actions')</th>
		</tr>
		</thead>
	</table>
	<ul id="page-tree-list" class="tree-items list-unstyled" data-level="0">
		<li data-id="{{ $page->id }}">
			<div class="tree-item">
				<div class="title col-xs-7">
					@if (!$page->hasLayout())
					{!! UI::icon('exclamation-triangle fa-fw text-warning', ['title' => trans('pages::core.messages.layout_not_set')]) !!}
					@endif

					@if (!acl_check('page.edit'))
					{!! UI::icon('lock fa-fw') !!}
					<em title="/">{{ $page->title }}</em>
					@else
					{!! link_to_route('backend.page.edit', $page->title, [$page], [
						'data-icon' => 'home fa-lg fa-fw'
					]) !!}
					@endif

					@if ($page->hasBehavior())
						{!! UI::label(trans('pages::core.label.page.behavior', ['behavior' => $page->getBehaviorTitle()]), 'default') !!}
					@endif

					{!! $page->getPublicLink() !!}
				</div>
				<div class="actions col-xs-offset-4 col-xs-1 text-right">
					@if (acl_check('page.create'))
					{!! link_to_route('backend.page.create', '', [], [
						'data-icon' => 'plus', 'class' => 'btn btn-default btn-xs'
					]) !!}
					@endif
				</div>
				<div class="clearfix"></div>
			</div>
		</li>
	</ul>

	<ul id="page-search-list" class="tree-items no-padding-hr"></ul>

	<div class="clearfix"></div>
</div>