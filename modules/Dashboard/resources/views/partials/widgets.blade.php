@if (count($types) > 0)
<div class="row">
	@foreach ($types as $type => $data)
		<div class="col-sm-6">
			<div class="panel">
				<div class="panel-body padding-sm">
					<button class="btn btn-default popup-btn pull-right" data-type="{{ $type }}">@lang('dashboard::core.buttons.place_widget')</button>
					<h4 @if (!empty($data['icon'])) data-icon="{{ $data['icon'] }} fa-lg" @endif >{{ array_get($data, 'title') }}</h4>

					@if (!empty($data['description']))
						<p class="text-muted">{{ $data['description'] }}</p>
					@endif
				</div>
			</div>
		</div>
	@endforeach
</div>
@else
<div class="panel">
	<div class="panel-body">
		<h2 class="no-margin">@lang('dashboard::core.messages.no_widgets')</h2>
	</div>
</div>
@endif

<script type="text/javascript">$(function() { CMS.ui.init('icon'); });</script>