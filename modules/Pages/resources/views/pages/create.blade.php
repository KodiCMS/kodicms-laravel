{!! Form::model($page, [
'route' => ['backend.page.create.post', $page],
'class' => 'panel  form-horizontal'
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
	</ul>
</div>

<div class="panel-heading">
	{!! $page->renderField('title') !!}

	<hr class="panel-wide" />
	{!! $page->renderField('slug') !!}

	<div class="well well-sm no-margin-b" id="redirect-container">
		{!! $page->renderField('is_redirect') !!}
		{!! $page->renderField('redirect_url') !!}
	</div>
</div>
<hr class="no-margin-vr" />
<div class="tab-content no-padding-vr">
	<div class="tab-pane active" id="page-content-panel">
		@event('view.page.create', [$page])
	</div>

	<div class="tab-pane fade" id="page-meta-panel">
		@include('pages::pages.partials.meta', ['page' => $page])
	</div>

	<div class="tab-pane fade" id="page-options-panel">
		@include('pages::pages.partials.settings', [
			'page' => $page
		])
	</div>
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.page.list'])
</div>
{!! Form::close() !!}