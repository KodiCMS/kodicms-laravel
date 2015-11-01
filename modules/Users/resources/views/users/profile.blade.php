<div class="page-profile clearfix">
	<div class="profile-row">
		<div class="left-col">
			<div class="profile-block">
				<div class="profile-photo">
					{!! $currentUser->getAvatar(180, ['class' => 'img-thumbnail img-responsive']) !!}
				</div>


				<div class="profile-full-name">
					<span class="text-semibold">{{ $user->username }}</span>
				</div>

				<span class="text-muted">@lang('users::core.field.last_login') {{ $user->last_login }}</span>

				<hr />

				@if (acl_check('users.edit') or $user->id == auth()->id)
				{!! link_to_route('backend.user.edit', trans('users::core.button.edit'), [$user], [
					'class' => 'btn btn-success btn-labeled',
					'data-icon' => 'user'
				]) !!}
				@endif
			</div>
		</div>
		<div class="right-col">
			<div class="profile-content panel tabbable">
				@event('view.user.profile.information', [$user->id])

				@if (!empty($permissions) and acl_check('users.view.permissions'))
				<div class="panel-heading">
					<span class="panel-title" data-icon="wheelchair">@lang('users::core.title.permissions')</span>
				</div>
				<div class="panel-body no-padding tabbable">
					@foreach($permissions as $section => $actions)
					<div class="panel-heading">
						<span class="panel-title">{{ ucfirst($section) }}</span>
					</div>
					<ul class="list-group no-margin-b">
						@foreach($actions as $action => $description)
						<li class="list-group-item" data-icon="check text-success">{{ $description }}</li>
						@endforeach
					</ul>
					@endforeach
				</div>
				@endif
			</div>
		</div>
	</div>
</div>