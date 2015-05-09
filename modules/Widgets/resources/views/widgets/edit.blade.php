{!! Form::open(['class' => 'panel form-horizontal', 'route' => ['backend.widget.edit.post', $widget]]) !!}

<div class="panel-heading panel-toggler" data-target-spoiler=".general-spoiler" data-hash="description">
	<span class="panel-title" data-icon="info-circle">@lang('widgets::core.title.general')</span>
</div>
<div class="panel-body panel-spoiler general-spoiler">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3">@lang('widgets::core.field.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', $widget->name, [
				'class' => 'form-control'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">@lang('widgets::core.field.description')</label>
		<div class="col-md-9">
			{!! Form::textarea('description', $widget->description, [
				'class' => 'form-control', 'rows' => 4
			]) !!}
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3">@lang('widgets::core.field.type')</label>
		<div class="col-md-9">
			{!! Form::text(NULL, $widget->type, [
				'class' => 'form-control', 'disabled', 'size' => 50
			]) !!}
		</div>
	</div>
</div>

@if ($widget->isRenderable())
<div class="panel-heading">
	<span class="panel-title" data-icon="hdd-o">@lang('widgets::core.title.template')</span>
</div>
<div class="note note-info no-margin-b">
	<div class="row">
		<div class="col-sm-offset-2 col-sm-10">
			<strong>@lang('widgets::core.settings.template_parameters'): </strong>
			@foreach ($commentKeys as $param)
			{!! UI::label($param) !!}
			@endforeach
		</div>
	</div>
</div>
<?php

$defaultTemplateButton = $widget->getDefaultFrontendTemplate()
	? link_to_route('backend.widget.template', UI::hidden(trans('widgets::core.button.defaultTemplate'), ['sm', 'xs']), [$widget->id], [
		'data-icon' => 'desktop', 'class' => 'btn popup fancybox.iframe btn-default',
		'id' => 'defaultTemplateButton'
	])
	: null;
?>

{!! view('widgets::snippet.snippet_select', [
	'template' => $widget->template,
	'default' => $defaultTemplateButton
]) !!}
@endif

@if ($widget->isCacheable() AND acl_check('widgets.cache'))
<div class="panel-heading">
	<span class="panel-title" data-icon="hdd-o">@lang('widgets::core.title.cache')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<div class="checkbox col-xs-offset-3">
			<label>
				{!! Form::checkbox('settings[cache]', 1, $widget->isCacheEnabled(), [
						'class' => 'px', 'id' => 'cache'
				]) !!}
				<span class="lbl">@lang('widgets::core.settings.cache')</span>
			</label>
		</div>
	</div>

	<div id="cache_settings_container">
		<div id="cache_lifetime_group" class="form-group">
			<label class="control-label col-xs-3" for="cache_lifetime">@lang('widgets::core.settings.cache_lifetime')</label>
			<div class="col-xs-3">
				{!! Form::text('settings[cache_lifetime]', $widget->getCacheLifetime(), [
					'class' => 'form-control', 'id' => 'cache_lifetime'
				]) !!}
			</div>

			<div class="col-md-6">
				<span class="flags" id="cache_lifetime_labels" data-target="#cache_lifetime">
					<span class="label" data-value="<?php echo Date::MINUTE; ?>"><?php echo __('Minute'); ?></span>
					<span class="label" data-value="<?php echo Date::HOUR; ?>"><?php echo __('Hour'); ?></span>
					<span class="label" data-value="<?php echo Date::DAY; ?>"><?php echo __('Day'); ?></span>
					<span class="label" data-value="<?php echo Date::WEEK; ?>"><?php echo __('Week'); ?></span>
					<span class="label" data-value="<?php echo Date::MONTH; ?>"><?php echo __('Month'); ?></span>
					<span class="label" data-value="<?php echo Date::YEAR; ?>"><?php echo __('Year'); ?></span>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-3">@lang('widgets::core.settings.cache_tags')</label>
			<div class="col-xs-9">
				{!! Form::textarea('settings[cache_tags][]', $widget->getCacheTagsAsString(), [
					'class' => 'tags'
				]) !!}
			</div>
		</div>
	</div>
</div>
@endif

@if (acl_check('widgets.roles') AND !$widget->isHandler())
<div class="panel-heading panel-toggler" data-target-spoiler=".roles-spoiler" data-hash="roles">
	<span class="panel-title" data-icon="users">@lang('widgets::core.title.permissions')</span>
</div>
<div class="panel-body panel-spoiler roles-spoiler">
	{!! Form::select('settings[roles][]', $usersRoles, $widget->getRoles(), [
		'class' => 'col-md-12 form-controll'
	]) !!}
</div>
@endif

@if($widget->isRenderable())
<div class="panel-heading panel-toggler" data-target-spoiler=".media-spoiler" data-hash="media">
	<span class="panel-title" data-icon="file-o">@lang('widgets::core.title.assets')</h4>
</div>
<div class="panel-body panel-spoiler media-spoiler">
	<div class="form-group">
		<div class="col-xs-12">
			<label class="control-label">@lang('widgets::core.settings.assets_package')</label>
			{!! Form::select('settings[media_packages[]]', $assetsPackages, $widget->getMediaPackages(), [
				'class' => 'form-control', 'multiple'
			]) !!}
		</div>
	</div>
</div>
@endif

<div class="panel-heading">
	<span class="panel-title" data-icon="cogs">@lang('widgets::core.title.settings')</span>
</div>
@if($widget->isRenderable())
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-xs-3">@lang('widgets::core.settings.header')</label>
		<div class="col-xs-9">
			{!! Form::text('settings[header]', $widget->getHeader(), ['class' => 'form-control']) !!}
		</div>
	</div>
</div>
@endif

{!! $settingsView !!}

@if($widget->isHandler())
<div class="alert alert-danger note-dark no-margin-b">
{!! trans('widgets::core.messages.is_handler', [
	'url' => $widget->getHandlerLink(),
	'route' => route('widget.handler', [$widget->id])
]) !!}
</div>
@endif

@if (acl_check('widgets.location') and !$widget->isHandler())
<hr class="no-margin-vr" />
<div class="panel-body">
	{!! link_to_route('backend.widget.location', trans('widgets::core.button.location'), [$widget], [
		'data-icon' => 'sitemap', 'class' => 'btn btn-xs btn-primary'
	]) !!}
</div>
@endif

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.widget.list'])
</div>
{!! Form::close() !!}