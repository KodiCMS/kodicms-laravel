<div class="panel">
	<div class="panel-heading">
		@if (acl_check('roles.add'))
			{!! link_to_route('backend.role.create', trans('users::role.button.create'), [], [
			'class' => 'btn btn-primary', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
			]) !!}
		@endif
	</div>

	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="150px" />
			<col />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('users::role.field.name')</th>
			<th>@lang('users::role.field.description')</th>
			<th>@lang('users::role.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($roles as $role)
		<tr class="item">
			<td class="name">
				@if (acl_check('roles.edit'))
					{!! link_to_route('backend.role.edit', $role->name, [$role], [
						'data-icon' => 'unlock'
					]) !!}
				@else
					<span data-icon="lock">{{ $role->name }}</span>
				@endif
			</td>
			<td class="description">
				{{ $role->description }}
			</td>
			<td class="actions text-center">
				@if ($role->id > 2 AND acl_check('roles.delete'))
				{!! link_to_route('backend.role.delete', '', [$role], [
					'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
				]) !!}
				@endif
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

{!! $roles->render() !!}