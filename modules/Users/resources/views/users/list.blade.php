<div class="panel">
	<div class="panel-heading">
		@if (acl_check('users.add'))
		{!! link_to_route('backend.user.create', trans('users::core.button.create'), [], [
			'class' => 'btn btn-primary', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif
	</div>

	@if(count($users) > 0)
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
			<th>@lang('users::core.field.username')</th>
			<th class="hidden-xs">@lang('users::core.field.email')</th>
			<th class="hidden-xs">@lang('users::core.field.roles')</th>
			<th class="hidden-xs">@lang('users::core.field.last_login')</th>
			<th class="text-right">@lang('users::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($users as $user)
		<tr class="item">
			<td class="name">
				{!! $user->gravatar(20, NULL, array('class' => 'img-circle')) !!}
				{!! link_to_route('backend.user.profile', $user->username, [$user]) !!}
			</td>
			<td class="email hidden-xs">{!! UI::label(HTML::mailto($user->email)) !!}</td>
			<td class="roles hidden-xs">
				@foreach($user->roles->lists('name') as $role)
				{!! UI::label($role, 'default') !!}
				@endforeach
			</td>
			<td class="last_login hidden-xs">{{ $user->last_login }}</td>
			<td class="actions text-right">
			@if ($user->id > 1 AND acl_check('users.delete'))
				{!! link_to_route('backend.user.delete', '', [$user], [
					'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
				]) !!}
			@endif
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	@else
	<div class="panel-body">
		<h3>@lang('users::core.messages.user.empty')</h3>
	</div>
	@endif
</div>

{!! $users->render() !!}