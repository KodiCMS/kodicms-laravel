{!! Form::model($user, [
	'route' => 'backend.user.create.post',
	'class' => 'form-horizontal panel'
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

<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.password')</span>
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
			'class' => 'form-control', 'id' => 'password_confirm', 'autocomplete' => 'off', 'placeholder' => trans('users::core.field.password_confirm')
			]) !!}
		</div>
	</div>

	@event('view.user.create.form.password')
</div>

<div class="panel-heading">
	<span class="panel-title">@lang('users::core.tab.roles')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<div class="col-md-12">
			{!! Form::select('user_roles[]', $rolesList, null, ['class' => 'form-control', 'multiple']) !!}
			<p class="help-block">@lang('users::core.rule.roles')</p>
		</div>
	</div>
</div>

@event('view.user.create.form.bottom')

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.user.list'])
</div>
{!! Form::close() !!}