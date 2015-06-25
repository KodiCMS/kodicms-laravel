{!! Form::model($user, [
	'route' => 'backend.user.create.post',
	'class' => 'form-horizontal panel'
]) !!}
<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.general')</span>
</div>
<div class="panel-body">
	{!! $user->renderField('username') !!}

	{!! $user->renderField('email') !!}

	<hr class="panel-wide" />

	{!! $user->renderField('locale') !!}
</div>

<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.password')</span>
</div>
<div class="panel-body">
	{!! $user->renderField('password') !!}
	{!! $user->renderField('password_confirmation') !!}

	@event('view.user.create.form.password')
</div>

<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.roles')</span>
</div>
<div class="panel-body">
	{!! $user->renderField('roles') !!}
</div>

@event('view.user.create.form.bottom')

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.user.list'])
</div>
{!! Form::close() !!}