<div class="frontend-header">
	<a href="/" class="logo">
		{!! HTML::image( CMS::resourcesURL() . '/images/logo-color.png') !!}
	</a>
</div>

<?php //TODO: добавить возможность включения своего кода в шаблон ?>
<div class="page-signin-alt">
	{!! Form::open(['route' => 'auth.login.post', 'class' => 'panel', 'id' => 'signin-form_id']) !!}

	@event('view.login.form.header')

	@if (count($errors) > 0)
		<ul class="alert alert-danger alert-dark list-unstyled">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	@endif
	<div class="panel-body">
		<div class="form-group">
			{!! Form::text('username', NULL, [
					'id' => 'username', 'class' => 'form-control input-lg', 'placeholder' => trans('users::user.field.auth.username')
			]) !!}
		</div>

		<div class="form-group signin-password">
			{!! Form::password('password', [
				'id' => 'password', 'class' => 'form-control input-lg', 'placeholder' =>  trans('users::user.field.auth.password')
			]) !!}

			{!! HTML::linkRoute('auth.password', trans('users::user.field.auth.forgot'), [], ['class' => 'forgot']) !!}
		</div>

		<div class="form-group">
			<label class="checkbox-inline">
				{!! Form::checkbox('remember', 'checked', TRUE, ['class' => 'px', 'id' => 'rememder']) !!}
				<span class="lbl">
					<?php //TODO: вынести настройки lifetime в конфиг ?>
					{{ trans('users::user.field.auth.remember', ['lifetime' => 10]) }}
				</span>
			</label>
		</div>
	</div>

	@event('view.login.form.footer')

	<div class="panel-footer">
		{!! Form::button(trans('users::user.button.login'), [
			'class' => 'btn btn-success btn-lg', 'type' => 'submit'
		]) !!}
	</div>

	{!! Form::close() !!}

	@event('view.login.form.after')
</div>