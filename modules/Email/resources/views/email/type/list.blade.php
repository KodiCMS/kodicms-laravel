<div class="panel">
	<div class="panel-heading">
		@if (acl_check('email.type.create'))
			{!! link_to_route('backend.email.type.create', trans('email::core.button.types.create'), [], [
				'class' => 'btn btn-primary', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
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
			<th>@lang('email::core.field.types.name')</th>
			<th class="hidden-xs">@lang('email::core.field.types.code')</th>
			<th class="text-right">@lang('email::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($emailTypes as $emailType)
			<tr class="item">
				<td class="email-name">
					@if (acl_check('email.type.edit'))
						{!! link_to_route('backend.email.type.edit', $emailType->name, [$emailType]) !!}
					@else
						{!! UI::icon('lock') !!} {{ $emailType->name }}
					@endif
				</td>
				<td class="email-code hidden-xs">
					{!! UI::label($emailType->code) !!}
				</td>
				<td class="actions text-right">
					@if (acl_check('email.type.delete'))
						{!! link_to_route('backend.email.type.delete', '', [$emailType], [
							'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
						]) !!}
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>

{!! $emailTypes->render() !!}