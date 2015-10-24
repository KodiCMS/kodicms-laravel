<div class="panel">
	<div class="panel-heading">
		@if (acl_check('email.event.create'))
			{!! link_to_route('backend.email.event.create', trans('email::core.button.events.create'), [], [
				'class' => 'btn btn-primary btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
			]) !!}
		@endif
	</div>

	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="250px" />
			<col width="200px" />
			<col />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('email::core.field.events.name')</th>
			<th class="hidden-xs">@lang('email::core.field.events.code')</th>
			<th class="text-right">@lang('email::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($emailEvents as $emailEvent)
			<tr class="item">
				<td class="email-name">
					@if (acl_check('email.event.edit'))
						{!! link_to_route('backend.email.event.edit', $emailEvent->name, [$emailEvent]) !!}
					@else
						{!! UI::icon('lock') !!} {{ $emailEvent->name }}
					@endif
				</td>
				<td class="email-code hidden-xs">
					{!! UI::label($emailEvent->code) !!}
				</td>
				<td class="actions text-right">
					@if (acl_check('email.event.delete'))
					{!! Form::open(['route' => ['backend.email.event.delete', $emailEvent]]) !!}
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

{!! $emailEvents->render() !!}