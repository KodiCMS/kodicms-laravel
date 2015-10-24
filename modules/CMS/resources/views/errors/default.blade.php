<div class="frontend-header">
	<a href="/" class="logo">
		{!! HTML::image(resources_url('/images/logo-color.png')) !!}
	</a>
</div>

<div class="error-container">
	<div class="error-code">{{ $code }}</div>

	@if($debug)
	<div class="error-text">
		{{ $error }}
	</div>
	@endif

	<div class="error-text">
		<span class="hr"></span>
		<p>{{ $message }}</p>
	</div>
</div>