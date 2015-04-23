<div class="panel-body no-padding">
	@foreach ($behavior->getRouter()->getRoutes() as $route => $params)
	<?php if(empty($route)) continue; ?>

	<div class="panel no-margin-b">
		<div class="panel-heading">
			<code class="panel-title">{{ $page->getFrontendUrl() }}{{ $route }}</code>

			@if(isset($params['regex']) AND is_array($params['regex']))
			@foreach ($params['regex'] as $key => $regex)
				<span class="badge badge-success">{{ $key }}: <code class="label label-info">{{ $regex }}</code></span>
			@endforeach
			@endif
		</div>
	</div>
	@endforeach
</div>