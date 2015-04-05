<div id="page-tree" class="panel">
	<div class="panel-heading">
		@if (acl_check('page.add'))
		{!! link_to_route('backend.page.add', trans('pages::pages.button.add'), [], [
			'class' => 'btn btn-default', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif

		@if (acl_check('page.sort'))
		{!! Form::button(trans('pages::pages.button.reorder'), [
			'class' => 'btn btn-primary btn-sm', 'data-icon' => 'sort', 'data-hotkeys' => 'ctrl+s', 'id' => 'pageMapReorderButton'
		]) !!}
		@endif

		<div class="panel-heading-controls hidden-xs hidden-sm">
			@include('pages::blocks.search')
		</div>
	</div>

	<table id="page-tree-header" class="table table-primary">
		<thead>
		<tr class="row">
			<th class="col-xs-7">@lang('pages::pages.field.page')</th>
			<th class="col-xs-2 text-right">@lang('pages::pages.field.date')</th>
			<th class="col-xs-2 text-right">@lang('pages::pages.field.status')</th>
			<th class="col-xs-1 text-right">@lang('pages::pages.field.actions')</th>
		</tr>
		</thead>
	</table>
	<ul id="page-tree-list" class="tree-items list-unstyled" data-level="0">
		<li data-id="{{ $page->id }}">
			<div class="tree-item">
				<div class="title col-xs-7">
					@if (!acl_check('page.edit'))
					{!! UI::icon('lock fa-fw') !!}
					<em title="/">{{ $page->title }}</em>
					@else
					{!! link_to($page->getFrontendUrl(), $page->title, ['data-icon' => 'home fa-lg fa-fw']) !!}
					@endif

					{!! $page->getPublicLink() !!}
				</div>
				<div class="actions col-xs-offset-4 col-xs-1 text-right">
					@if (acl_check('page.add'))
					{!! link_to_route('backend.page.add', '', [], [
						'data-icon' => 'plus', 'class' => 'btn btn-default btn-xs'
					]) !!}
					@endif
				</div>
				<div class="clearfix"></div>
			</div>

			{{-- $content_children --}}
		</li>
	</ul>

	<ul id="page-search-list" class="tree-items no-padding-hr"></ul>

	<div class="clearfix"></div>
</div>