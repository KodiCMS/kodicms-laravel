<script type="text/javascript">
	$(function() {
		$('#set_current').on('change', function() {
			set_current($(this))
		});

		set_current($('#set_current'));

		function set_current(input) {
			var cont = $('#default_container');

			if (input.is(':checked')) {
				cont.hide();
			} else {
				cont.show();
			}
		}
	});
</script>

<div class="form-group form-inline" id="default_container">
	<label class="control-label col-md-3" for="primitive_default">@lang('datasource::core.field.default_value')</label>
	<div class="col-md-9">
		{!! Form::text('settings[default_value]', $field->getSetting('default_value'), [
			'class' => 'form-control datepicker',
			'id' => 'primitive_default',
			'size' => 10, 'maxlength' => 10,
			'autocomplete' => 'off'
		]) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
			{!! Form::switcher('settings[set_current]', 1, $field->isCurrentDateByDefault(), [
				'id' => 'set_current'
			]) !!} @lang('datasource::fields.date.set_current_date')
			</label>
		</div>
	</div>
</div>