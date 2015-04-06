<div class="page-profile clearfix">
	<div class="profile-full-name">
		<span class="text-semibold">{{ $user->username }}</span>
		@lang('users::core.field.last_login') {{ $user->last_login }}
	</div>

	<div class="profile-row">
		<div class="left-col">
			<div class="profile-block">
				<div class="panel profile-photo">
					{!! link_to('http://gravatar.com/emails/', $user->gravatar(100, NULL), [
						'target' => '_blank',
					]) !!}
				</div>

				<br />

				@if (acl_check('users.edit') OR $user->id == auth()->id)
				{!! link_to_route('backend.user.edit', trans('users::core.button.edit'), [$user], [
					'class' => 'btn btn-success btn-sm',
					'data-icon' => 'user'
				]) !!}
				@endif
			</div>
		</div>
		<div class="right-col">
			<hr class="profile-content-hr no-grid-gutter-h">

			<div class="profile-content tabbable">
				@event('view.user.profile.information', [$user->id])

				@if (!empty($permissions) AND acl_check('users.view.permissions'))
				<div class="panel-heading">
					<span class="panel-title">@lang('users::core.title.permissions')</span>
				</div>
				<div class="panel-body">
					@foreach($permissions as $section => $actions)
					<div class="panel-heading">
						<span class="panel-title">{{ ucfirst($section) }}</span>
					</div>
					<table class="table table-noborder">
						<tbody>
						@foreach($actions as $action => $description)
						<tr>
							<td data-icon="check text-success" class="">{{ $description }}</td>
						</tr>
						@endforeach
						</tbody>
					</table>
					@endforeach
				</div>
				@endif
			</div>
		</div>
	</div>
</div>