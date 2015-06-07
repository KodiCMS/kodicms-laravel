{!! Form::model($user, [
	'route' => ['backend.user.edit.post', $user],
	'class' => 'form-horizontal panel tabbable'
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

@if (acl_check('users.change_password') OR $user->id == auth()->user()->id)
<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.password')</span>
</div>

<div class="note note-warning">
	{!! UI::icon('lightbulb-o fa-lg') !!} @lang('users::core.rule.password_change')
</div>

<div class="panel-body">

	{!! $user->renderField('password') !!}
	{!! $user->renderField('password_confirmation') !!}

	@event('view.user.edit.form.password', [$user])
</div>
@endif

@if (acl_check('users.change_roles') AND ($user->id > 1))
<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.roles')</span>
</div>
<div class="panel-body">
	{!! $user->renderField('roles') !!}
</div>
@endif

<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.theme')</span>
</div>
<div class="panel-body">
	<?php
	$themes = config('cms.theme.list', []);
	$currentTheme = $user->getCurrentTheme();
	?>

	<div id="themes" class="row">
		@foreach ($themes as $theme)
		<div class="col-md-2 col-sm-3 col-xs-4">
			<a href="#" class="theme @if ($theme == $currentTheme) active @endif thumbnail" data-theme="{{ $theme }}">
				{!! HTML::image(resources_url() . '/images/themes/' . $theme . '.jpg') !!}
			</a>
		</div>
		@endforeach
	</div>
</div>

@event('view.user.edit.form.bottom', [$user])

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.user.list'])
</div>
{!! Form::close() !!}