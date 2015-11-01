<div class="panel-body no-padding">
	@forelse ($behavior->getRouter()->getRoutes() as $route => $params)
	<?php if(empty($route)) continue; ?>
	<div class="panel no-margin-b">
		<div class="panel-heading">
			<code class="panel-title">{{ $page->getFrontendUrl() }}{{ $route }}</code>

			@if(isset($params['regex']) and is_array($params['regex']))
			@foreach ($params['regex'] as $key => $regex)
				<span class="badge badge-success">{{ $key }}: <code class="label label-info">{{ $regex }}</code></span>
			@endforeach
			@endif

			<span class="badge badge-default">Call:
				<code class="label label-info">
					<?php $method = array_get($params, 'method', $behavior->getRouter()->getDefaultMethod()); ?>

					@if(strpos($method, '::') !== false)
					{{ $method }}
					@else
					{{ get_class($behavior) }}::{{ $method }}
					@endif
				</code>
			</span>
		</div>
	</div>
	@empty
	<div class="note note-info">
		<h4 class="no-margin">@lang('pages::core.messages.behavior_no_routes')</h4>
	</div>
	@endforelse
</div>