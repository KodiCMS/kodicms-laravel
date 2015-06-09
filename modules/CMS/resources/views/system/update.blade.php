<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">@lang('cms::core.title.update')</span>
	</div>
	<div class="panel-body">
		@if ($hasNewVersion)
		<h3>{!! trans('cms::core.messages.new_version', ['version' => $repositoryVersion]) !!}</h3>
		@else
		<h3>@lang('cms::core.messages.no_new_version')</h3>
		@endif

		<div class="note note-warning">
			{!! UI::icon('lightbulb-o fa-lg') !!} @lang('cms::core.messages.update_information')
		</div>

		<div id="files"></div>
	</div>
</div>