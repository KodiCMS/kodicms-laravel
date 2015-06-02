{!! Form::model($user, [
	'route' => ['backend.user.edit.post', $user],
	'class' => 'form-horizontal panel tabbable'
]) !!}
<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.general')</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="username">@lang('users::core.field.username')</label>
		<div class="col-md-4">
			<div class="input-group">
				{!! Form::text('username', NULL, [
					'class' => 'form-control', 'id' => 'username'
				]) !!}
				<span class="input-group-addon" data-icon="user"></span>
			</div>
		</div>
		<div class="col-md-offset-3 col-md-9">
			<p class="help-block">@lang('users::core.rule.username', ['num' => 3])</p>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="email">@lang('users::core.field.email')</label>
		<div class="col-md-4">
			<div class="input-group">
				{!! Form::email('email', NULL, [
					'class' => 'form-control', 'id' => 'email'
				]) !!}
				<span class="input-group-addon" data-icon="envelope"></span>
			</div>
		</div>
	</div>

	<hr class="panel-wide" />

	<div class="form-group">
		<label class="control-label col-md-3" for="locale">@lang('users::core.field.locale')</label>
		<div class="col-md-4">
			{!! Form::select('locale', config('cms.locales', []), NULL, [
				'class' => 'form-control', 'id' => 'locale'
			]) !!}
		</div>
	</div>
</div>

@if (acl_check('users.change_password') OR $user->id == auth()->user()->id)
<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.password')</span>
</div>

<div class="note note-warning">
	{!! UI::icon('lightbulb-o fa-lg') !!} @lang('users::core.rule.password_change')
</div>

<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="email">@lang('users::core.field.password')</label>
		<div class="col-md-3">
			{!! Form::password('password', [
				'class' => 'form-control', 'id' => 'password', 'autocomplete' => 'off', 'placeholder' => trans('users::core.field.password')
			]) !!}
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3" for="email">@lang('users::core.field.password_confirm')</label>
		<div class="col-md-3">
			{!! Form::password('password_confirmation', [
				'class' => 'form-control', 'id' => 'password_confirmation', 'autocomplete' => 'off', 'placeholder' => trans('users::core.field.password_confirm')
			]) !!}
		</div>
	</div>

	@event('view.user.edit.form.password', [$user])
</div>
@endif

@if (acl_check('users.change_roles') AND ($user->id > 1))
<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.roles')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<div class="col-md-12">
			{!! Form::select('user_roles[]', [], [], ['class' => 'form-control']) !!}
			<p class="help-block">@lang('users::core.rule.roles')</p>
		</div>
	</div>
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