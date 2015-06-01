<div class="panel-heading panel-toggler" data-target-spoiler=".roles-spoiler" data-hash="roles">
	<span class="panel-title" data-icon="users">@lang('widgets::core.title.permissions')</span>
</div>
<div class="panel-body panel-spoiler roles-spoiler">
	{!! Form::select('settings[roles][]', $usersRoles, $widget->getRoles(), [
		'class' => 'form-control'
	]) !!}
</div>