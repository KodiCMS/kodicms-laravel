<div class="frontend-header no-padding">
	<a href="/" class="logo">
		{!! HTML::image(resources_url() . '/images/logo-color.png') !!}
	</a>

	{!! HTML::linkRoute('backend.auth.login', trans('users::core.button.login'), [], [
	'class' => 'btn btn-default navbar-btn', 'data-icon' => 'chevron-left'
	]) !!}
</div>

<div class="page-signin-alt">
	{!! Form::open(['route' => 'backend.auth.password.post', 'class' => 'panel', 'id' => 'signin-form_id']) !!}
	@if (count($errors) > 0)
	<ul class="alert alert-danger alert-dark list-unstyled no-margin-b">
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
	@endif

	@if ($status)
		<div class="alert alert-success alert-dark no-margin-b">
			{{ $status }}
		</div>
	@endif
	<div class="panel-body">
		<p class="text-muted">@lang('users::core.messages.auth.forgot')</p>
		<hr class="panel-wide" />
		<div class="input-group input-group-lg">
			<span class="input-group-addon">{!! UI::icon('envelope') !!}</span>
			{!! Form::text('email', NULL, [
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