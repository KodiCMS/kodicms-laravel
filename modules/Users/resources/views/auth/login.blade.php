<div class="frontend-header no-padding">
	<a href="/" class="logo">
		{!! HTML::image( CMS::resourcesURL() . '/images/logo-color.png') !!}
	</a>
</div>

<div class="page-signin-alt">
	{!! Form::open(['route' => 'backend.auth.login.post', 'class' => 'panel', 'id' => 'signin-form_id']) !!}

	@event('view.login.form.header')
	<div class="panel-body">
		<div class="form-group">
			{!! Form::text('email', NULL, [
					'id' => 'email', 'class' => 'form-control input-lg', 'placeholder' => trans('users::core.field.auth.email')
			]) !!}
		</div>

		<div class="form-group signin-password">
			{!! Form::password('password', [
				'id' => 'password', 'class' => 'form-control input-lg', 'placeholder' =>  trans('users::core.field.auth.password')
			]) !!}

			{!! HTML::linkRoute('backend.auth.password', trans('users::core.field.auth.forgot'), [], ['class' => 'forgot']) !!}
		</div>

		<div class="form-group">
			<label class="checkbox-inline">
				{!! Form::checkbox('remember', 'checked', TRUE, ['class' => 'px', 'id' => 'rememder']) !!}
				<span class="lbl">
					<?php //TODO: вынести настройки lifetime в конфиг ?>
					{{ trans('users::core.field.auth.remember', ['lifetime' => 10]) }}
				</span>
			</label>
		</div>
	</div>

	@event('view.login.form.footer')

	<div class="panel-footer">
		{!! Form::button(trans('users::core.button.login'), [
			'class' => 'btn btn-success btn-lg', 'type' => 'submit'
		]) !!}
	</div>

	{!! Form::close() !!}

	@event('view.login.form.after')
</div>