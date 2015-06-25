{!! Form::open([
	'route' => ['backend.plugins.settings.post', $plugin->getName()],
	'class' => 'form-horizontal panel'
]) !!}

<div class="panel-heading">
	<span class="panel-title"><small>@lang('plugins::core.plugin_settings')</small> {{ $plugin->getTitle() }}</span>
</div>

{!! $settingsTemplate !!}
<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.plugins.list'])
</div>
{!! Form::close() !!}