<div class="panel">
	<div class="panel-body">
		@if ($hasNewVersion)
		<h3>{!! trans('cms::core.messages.new_version', ['version' => $repositoryVersion]) !!}</h3>
		@else
		<h3>@lang('cms::core.messages.no_new_version')</h3>
		@endif

		<br />
		{!! HTML::link($issueUrl, trans('cms::core.button.bug_report'), [
			'class' => 'btn btn-labeled btn-danger', 'data-icon' => 'bug fa-lg', 'target' => '_blank'
		]) !!}
	</div>

	<div id="files"></div>

	<hr class="no-margin-b" />
	<div class="note note-warning no-margin-b">
		{!! UI::icon('lightbulb-o fa-lg') !!} @lang('cms::core.messages.update_information')
	</div>
</div>