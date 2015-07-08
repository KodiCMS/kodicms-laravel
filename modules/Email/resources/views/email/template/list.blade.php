<div class="panel">
	<div class="panel-heading">
		@if (acl_check('email.template.create'))
			{!! link_to_route('backend.email.template.create', trans('email::core.button.templates.create'), isset($routeParams) ? $routeParams : [], [
				'class' => 'btn btn-primary btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
			]) !!}
		@endif
	</div>

	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col />
			<col />
			<col width="200px" />
			<col width="200px" />
			<col width="100px" />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('email::core.field.templates.subject')</th>
			<th>@lang('email::core.field.templates.email_event')</th>
			<th class="hidden-xs">@lang('email::core.field.templates.email_from')</th>
			<th class="hidden-xs">@lang('email::core.field.templates.email_to')</th>
			<th class="hidden-xs">@lang('email::core.field.templates.status')</th>
			<th class="text-right">@lang('email::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($emailTemplates as $emailTemplate)
			<tr class="item">
				<td class="email-subject">
					@if (acl_check('email.template.edit'))
						{!! link_to_route('backend.email.template.edit', $emailTemplate->subject, [$emailTemplate]) !!}
					@else
						{!! UI::icon('lock') !!} {{ $emailTemplate->subject }}
					@endif
				</td>
				<td class="email-event">
					@if (acl_check('email.event.edit'))
						{!! link_to_route('backend.email.event.edit', $emailTemplate->event->name, [$emailTemplate->event]) !!}
					@else
						{!! UI::icon('lock') !!} {{ $emailTemplate->event->name }}
					@endif
				</td>
				<td class="email-from hidden-xs">
					{!! UI::label($emailTemplate->email_from) !!}
				</td>
				<td class="email-to hidden-xs">
					{!! UI::label($emailTemplate->email_to) !!}
				</td>
				<td class="email-status hidden-xs">
					{{ $emailTemplate->statusString }}
				</td>
				<td class="actions text-right">
					@if (acl_check('email.template.delete'))
					{!! Form::open(['route' => ['backend.email.template.delete', $emailTemplate]]) !!}
						{!! Form::button('', [
							'type' => 'submit',
							'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
						]) !!}
					{!! Form::close() !!}
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>

{!! $emailTemplates->render() !!}