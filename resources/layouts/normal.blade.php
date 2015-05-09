<!DOCTYPE html>
<html lang="en">
<head>
	{!! Meta::build() !!}

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="header clearfix">
			@block('header')
		</div>

		@block('content.before')

		<div class="row marketing">
			@block('content', ['comments' => false])
		</div>

		@block('content.after')

		<footer class="footer">
			@block('footer')
		</footer>
	</div>
</body>
</html>