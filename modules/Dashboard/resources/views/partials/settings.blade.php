{!! Form::open([
	'route' => ['api.dashboard.widget.settings', $widget->getId()],
	'class' => 'form-horizontal panel form-popup',
	'data-api-method' => 'post', 'data-api-url' => route('api.dashboard.widget.settings')
]) !!}

{!! Form::hidden('id', $widget->getId()) !!}

<div class="panel-heading">
	<span class="panel-title" data-icon="cogs">@lang('dashboard::core.title.widget_settings')</span>
</div>

{!! $settingsView !!}

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['controllerAction' => null])
</div>
{!! Form::close() !!}

<script>CMS.ui.init();</script>