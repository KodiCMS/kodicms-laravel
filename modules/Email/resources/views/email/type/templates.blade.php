@if ($emailType->exists)
	<div class="panel no-margin-b">
		<div class="panel-heading">
			<span class="panel-title">@lang('email::core.templates.title')</span>
			<div class="panel-heading-controls">
				@if (acl_check('email.template.create'))
					{!! link_to_route('backend.email.template.create', trans('email::core.button.templates.create'), ['email_type_id' => $emailType->id], [
						'data-icon' => 'plus', 'class' => 'btn btn-default'
					]) !!}
				@endif
			</div>
		</div>
		@if (count($emailType->templates) > 0)
			<ul class="list-group">
				@foreach ($emailType->templates as $emailTemplate)
					<li class="list-group-item">
						{!! link_to_route('backend.email.template.edit', $emailTemplate->subject, [$emailTemplate]) !!}
					</li>
				@endforeach
        	</ul>
		@endif
	</div>
@endif