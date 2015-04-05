<div class="frontend-header">
	<a href="/" class="logo">
		{!! HTML::image( CMS::resourcesURL() . '/images/logo-color.png') !!}
	</a>

	{!! HTML::linkRoute('auth.login', trans('users::core.button.login'), [], [
		'class' => 'btn btn-default', 'data-icon' => 'chevron-left'
	]) !!}

	<div class="clearfix"></div>
</div>

<div class="page-signin-alt">
	{!! Form::open(['route' => 'auth.password.post', 'class' => 'panel', 'id' => 'signin-form_id']) !!}
	@if (count($errors) > 0)
		<ul class="alert alert-danger alert-dark list-unstyled">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	@endif
	<div class="panel-body">
		<p class="text-muted">@lang('users::core.messages.auth.forgot')</p>
		<hr class="panel-wide" />
		<div class="input-group input-group-lg">
			<span class="input-group-addon">{!! UI::icon('envelope') !!}</span>
			{!! Form::text('forgot[email]', NULL, [
					'class' => 'form-control',
					'placeholder' => trans('users::core.field.auth.email')
			]) !!}
		</div>
	</div>

	@event('view.password.form.footer')

	<div class="panel-footer">
		{!! Form::button(trans('users::core.button.send_password'), [
			'class' => 'btn btn-primary btn-lg', 'type' => 'submit'
		]) !!}
	</div>
	<?php Form::close(); ?>
</div>