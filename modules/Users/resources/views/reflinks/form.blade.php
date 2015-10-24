<div class="frontend-header no-padding">
	<a href="/" class="logo">
		{!! HTML::image(resources_url('/images/logo-color.png')) !!}
	</a>
</div>

<div class="page-reflink-alt">
	{!! Form::open(['route' => 'reflink.form.post', 'class' => 'panel']) !!}
	@if (count($errors) > 0)
		<ul class="alert alert-danger alert-dark list-unstyled">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	@endif

	<div class="panel-body">
		<div class="input-group input-group-lg">
			<span class="input-group-addon" data-icon="ticket"></span>
			{!! Form::text('token', NULL, [
				'class' => 'form-control',
				'placeholder' => trans('users::reflinks.field.token')
			]) !!}
		</div>
	</div>

	<div class="panel-footer">
		{!! Form::button(trans('users::reflinks.button.send_token'), [
			'class' => 'btn btn-primary btn-lg', 'type' => 'submit'
		]) !!}
	</div>
	<?php Form::close(); ?>
</div>