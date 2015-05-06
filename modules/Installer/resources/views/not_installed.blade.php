<div class="container-fluid margin-sm-vr">
	<div class="panel">
		<div class="panel-body">
			<h1>{{ $title }}</h1>
			<p class="lead">@lang('installer::core.messages.not_installed', ['file' => app()->environmentFile()])
			</p>
			<hr />
			{!! HTML::linkRoute('installer.get', trans('installer::core.button.install'), [], [
					'class' => 'btn btn-lg btn-primary pull-right'
			]) !!}
		</div>
	</div>
</div>