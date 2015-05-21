@if ($emailEvent->exists)
	<div class="panel no-margin-b">
		<div class="panel-heading">
			<span class="panel-title">@lang('email::core.templates.title')</span>
			<div class="panel-heading-controls">
				@if (acl_check('email.template.create'))
					{!! link_to_route('backend.email.template.create', trans('email::core.button.templates.create'), ['email_event_id' => $emailEvent->id], [
						'data-icon' => 'plus', 'class' => 'btn btn-default'
					]) !!}
				@endif
			</div>
		</div>
		@if (count($emailEvent->templates) > 0)
			<ul class="list-group">
				@foreach ($emailEvent->templates as $emailTemplate)
					<li class="list-group-item">
						{!! link_to_route('backend.email.template.edit', $emailTemplate->subject, [$emailTemplate]) !!}
					</li>
				@endforeach
        	</ul>
		@endif
	</div>
@endif