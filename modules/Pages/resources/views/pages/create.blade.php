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
	<div class="form-group form-group-lg">
		<label class="control-label col-md-2" for="title">@lang('pages::core.field.title')</label>
		<div class="col-md-10">
			{!! Form::text('title', NULL, [
				'class' => 'form-control slug-generator', 'id' => 'title'
			]) !!}
		</div>
	</div>

	<hr class="panel-wide" />
	<div class="form-group form-group-sm">
		<label class="control-label col-md-2" for="slug">
			@lang('pages::core.field.slug')
		</label>
		<div class="col-md-10">
			{!! Form::text('slug', NULL, [
				'class' => 'form-control slugify', 'id' => 'slug'
			]) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-10">
			<div class="checkbox-inline">
				{!! Form::checkbox('is_redirect', 1, NULL, [
					'id' => 'is_redirect'
				]) !!}

				<label for="is_redirect">
					@lang('pages::core.field.is_redirect')
				</label>
			</div>
		</div>
	</div>

	<div class="form-group" id="redirect-to-container">
		<label class="control-label col-md-2" for="redirect_url">
			@lang('pages::core.field.redirect_url')
		</label>
		<div class="col-md-10">
			{!! Form::text('redirect_url', NULL, [
				'class' => 'form-control', 'id' => 'redirect_url'
			]) !!}
		</div>
	</div>

</div>
<hr class="no-margin-vr" />
<div class="tab-content no-padding-vr">
	<div class="tab-pane active" id="page-content-panel">
		@event('view.page.create')
	</div>

	<div class="tab-pane fade" id="page-meta-panel">
		@include('pages::pages.partials.meta', ['page' => $page])
	</div>

	<div class="tab-pane fade" id="page-options-panel">
		@include('pages::pages.partials.settings', [
			'page' => $page,
			'pagesMap' => $pagesMap,
			'behaviorList' => $behaviorList
		])
	</div>
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.page.list'])
</div>
{!! Form::close() !!}