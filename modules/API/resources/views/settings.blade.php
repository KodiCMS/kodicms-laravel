<div class="panel-heading" data-icon="flask">
	<span class="panel-title">@lang('api::core.title.api')</span>
</div>
<div class="panel-body api-settings">
	<div class="form-group">
		<label class="control-label col-lg-3">@lang('api::core.title.api_key')</label>
		<div class="col-md-5">
			@if (acl_check('api.view_key'))
			{!!  Form::text(NULL, config('cms.api_key'), [
				'id' => 'api-key', 'class' => 'form-control', 'readonly'
			]) !!}
			@endif
		</div>
		<div class="col-md-3">
			@if (acl_check('api.refresh_key'))
			{!! Form::button(trans('api::core.buttons.refresh_key'), [
			'class' => 'btn btn-primary btn-labeled',
			'id' => 'refresh-api-key',
			'data-icon' => 'refresh'
			]) !!}
			@endif
		</div>
	</div>

	@if (acl_check('api.view_key'))
	<hr class="panel-wide" />

	<div class="form-group" id="api-keys">
		<label class="control-label col-md-3">@lang('api::core.title.api_keys')</label>
		<div class="col-xs-9">
			<div class="row-helper hidden padding-xs-vr">
				<div class="input-group">
					<span class="input-group-addon api-key bg-success"></span>
					{!! Form::text(null, null, [
						'disabled', 'class' => 'row-value form-control',
					]) !!}

					@if (acl_check('api.delete_keys'))
					<div class="input-group-btn">
					{!! Form::button('', [
						'data-icon' => 'trash-o',
						'name' => 'trash-row',
						'class' => 'btn btn-warning remove-row'
					]) !!}
					</div>
					@endif
				</div>
			</div>

			<div class="rows-container"></div>

			@if (acl_check('api.create_keys'))
			{!! Form::button('', [
				'data-icon' => 'plus',
				'class' => 'add-row btn btn-primary',
				'data-hotkeys' => 'ctrl+a'
			]) !!}
			@endif
		</div>
	</div>
	@endif
</div>

@section('scripts')
	@parent

<script type="text/javascript">
	$(function(){
		$('body').on('click', '#refresh-api-key', function(e) {
			e.preventDefault();

			bootbox.confirm(i18n.t('api.core.messages.are_you_sure'), function(result) {
				if(!result) return;

				Api.post('/api.refresh.key', null, function(response) {
					$('#api-key').val(response.content);
				});
			});
		});

		var keys = Api.get('/api.keys', {}, function(response) {
			var $container = $('#api-keys').removeClass('hidden');

			$container.on('click', '.add-row', function(e) {
				e.preventDefault();

				bootbox.prompt(i18n.t('api.core.messages.new_key'), function(result) {
					if(result === null) {
						return true;
					} else if (result.length > 0) {
						Api.put('/api.key', {description: result}, function(response) {
							if(response.content) {
								var $row = clone_row($container);
								fill_row($row, response.content, result);
							}
						});

						return true;
					}

					return false;
				});
			});

			$container.on('click', '.remove-row', function(e) {
				var $cont = $(this).closest('.row-helper');
				Api.delete('/api.key', {key: $cont.data('id')}, function(response) {
					if(response.content) $cont.remove();
				});
				e.preventDefault();
			});

			for(key in response.content) {
				var row = clone_row($container);
				fill_row(row, key, response.content[key]);
			}
		});

		function fill_row($row, $key, $description) {
			var input = $row.find('.row-value');

			$row.find('.api-key').text($key);
			input.val($description);
			$row.data('id', $key);
		}

		function clone_row($container) {
			return $('.row-helper.hidden', $container)
					.clone()
					.removeClass('hidden')
					.appendTo($('.rows-container', $container))
					.find(':input')
					.end();
		}
	});
</script>
@stop