@if ($emailEvent->exists)
	<div class="panel no-margin-b">
		<div class="panel-heading">
			<span class="panel-title">@lang('email::core.templates.title')</span>
		</div>
	</div>
	@if (count($emailEvent->templates) > 0)
		@include('email::email.template.list', ['emailTemplates' => $emailEvent->templates()->paginate(), 'routeParams' => ['email_event_id' => $emailEvent->id]])
	@endif
@endif