<div class="panel-heading" data-icon="flask">
	<span class="panel-title">@lang('api::core.title.api')</span>
</div>
<div class="panel-body api-settings">
	<div class="form-group">
		<label class="control-label col-lg-3">@lang('api::core.title.api_key')</label>
		<div class="col-md-7">
			<div class="input-group">
				{!!  Form::text(NULL, config('cms.api_key'), [
				'id' => 'api-key', 'class' => 'form-control', 'readonly'
				]) !!}
				@if (acl_check('system.api.refresh'))
				<div class="input-group-btn">
				{!! Form::button(trans('api::core.buttons.refresh_key'), [
					'class' => 'btn btn-primary',
					'id' => 'refresh-api-key',
					'data-icon' => 'refresh'
				]) !!}
				</div>
				@endif
			</div>
		</div>
	</div>
</div>


@section('scripts')
	@parent

<script type="text/javascript">
$(function(){
	$('body').on('click', '#refresh-api-key', function() {
		Api.post('/api.refresh.key', null, function(response) {
			$('#api-key').val(response.content);
		});

		return false;
	});

//	TODO: реализовать генерацию API ключей issue #31
//	var keys = Api.get('/api.keys', {}, function(response) {
//		var $container = $('#api-keys').removeClass('hidden');
//
//		$container.on('click', '.add-row', function(e) {
//			e.preventDefault();
//			var $description = prompt("Please enter key description");
//
//			if(!$description) return;
//
//			Api.put('api.key', {description: $description}, function(response) {
//				if(response.content) {
//					var $row = clone_row($container);
//					fill_row($row, response.response, $description);
//				}
//			});
//		});
//
//		$container.on('click', '.remove-row', function(e) {
//			var $cont = $(this).closest('.row-helper');
//			Api.delete('api.key', {key: $cont.data('id')}, function(response) {
//				$cont.remove();
//			});
//			e.preventDefault();
//		});
//
//		for(key in response.response) {
//			var row = clone_row($container);
//			fill_row(row, key, response.response[key]);
//		}
//	});
//
//	function fill_row($row, $key, $description) {
//		var input = $row.find('.row-value');
//
//		$row.find('.api-key').text($key);
//		input.val($description);
//		$row.data('id', $key);
//	}
//
//	function clone_row($container) {
//		return $('.row-helper.hidden', $container)
//				.clone()
//				.removeClass('hidden')
//				.appendTo($('.rows-container', $container))
//				.find(':input')
//				.end();
//	}
});
</script>
@stop