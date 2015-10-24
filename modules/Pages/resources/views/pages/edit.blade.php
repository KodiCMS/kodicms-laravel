@event('view.page.edit', [$page])

{!! Form::model($page, [
'route' => ['backend.page.edit.post', $page],
'class' => 'panel form-horizontal'
]) !!}

<div style="position: relative;">
	<ul class="nav nav-tabs tabs-generated">
		<li class="active" id="page-content-panel-li">
			<a href="#page-content-panel" data-toggle="tab" data-icon="suitcase">
				@lang('pages::core.tab.page.content')
			</a>
		</li>
		<li id="page-meta-panel-li">
			<a href="#page-meta-panel" data-toggle="tab" data-icon="send-o">
				@lang('pages::core.tab.page.meta')
			</a>
		</li>
		<li id="page-options-panel-li">
			<a href="#page-options-panel" data-toggle="tab" data-icon="cogs">
				@lang('pages::core.tab.page.options')
			</a>
		</li>
		@if(!is_null($page->getBehaviorObject()))
		<li id="page-options-panel-li">
			<a href="#page-behavior-panel" data-toggle="tab" data-icon="random">
				@lang('pages::core.tab.page.routes')
			</a>
		</li>
		@endif

		@yield('page-tab')
	</ul>
</div>

@if(!$page->hasLayout())
<div class="alert alert-dark alert-danger">
	{!! UI::icon('exclamation-triangle fa-fw') !!} {{ trans('pages::core.messages.layout_not_set') }}
</div>
@endif

<div class="panel-heading">
	{!! $page->renderField('title') !!}

	@if ($page->id > 1)
	<hr class="panel-wide" />

	{!! $page->renderField('slug') !!}

	<div class="well well-sm no-margin-b" id="redirect-container">
		{!! $page->renderField('is_redirect') !!}
		{!! $page->renderField('redirect_url') !!}
	</div>
	@endif
</div>
<hr class="no-margin-vr" />
<div class="tab-content no-padding-vr">
	<div class="tab-pane active" id="page-content-panel">
		@yield('page-content')
		<div class="panel-body">
			{!! $page->getPublicLink() !!}
			<div class="text-right">
				@if (!is_null($creator))
					{!! UI::label(trans('pages::core.label.page.created_by', [
					'anchor' => link_to_route('backend.user.edit', $creator->username, [$creator]),
					'date' => $page->created_at
					]), 'important') !!}
				@endif

				@if (!is_null($updator))
					{!! UI::label(trans('pages::core.label.page.updated_by', [
					'anchor' => link_to_route('backend.user.edit', $updator->username, [$updator]),
					'date' => $page->updated_at
					]), 'important') !!}
				@endif
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="page-meta-panel">
		@include('pages::pages.partials.meta', ['page' => $page])
	</div>

	<div class="tab-pane fade" id="page-options-panel">
		@include('pages::pages.partials.settings', [
			'page' => $page
		])
	</div>

	@if(!is_null($behavior = $page->getBehaviorObject()))
	<div class="tab-pane fade" id="page-behavior-panel">
		@include('pages::pages.partials.behavior', ['behavior' => $behavior])
	</div>
	@endif

	@yield('page-tab-content')
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.page.list'])
</div>
{!! Form::close() !!}