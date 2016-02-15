<?php
if (empty($data))
{
	$data = [];
}
if (empty($container))
{
	$container = 'rows-container';
}
if (empty($field))
{
	$field = 'data';
}
$container = str_slug($container);
$field = str_slug($field, '_');
$slugify = !isset($slugify) ? TRUE : (bool) $slugify;
?>

@section('scripts')
	@parent
<script type="text/javascript">
	var ROWS_DATA = {!! json_encode($data) !!};

	$(function() {
		var $container = $('#{{ $container }}');
		$container.on('click', '.add-row', function(e) {
			clone_row($container);
			e.preventDefault();
		});

		$container.on('click', '.remove-row', function(e) {
			$(this).closest('.row-helper').remove();
			e.preventDefault();
		});
		for(key in ROWS_DATA) {
			row = clone_row($container);
			row.find('.row-key').val(key);
			row.find('.row-value').val(ROWS_DATA[key]);
		}
	});

	function clone_row($container) {
		return $('.row-helper.hidden', $container)
			.clone()
			.removeClass('hidden')
			.appendTo($('.rows-container', $container))
			.find(':input')
			.removeAttr('disabled')
			.end();
	}
</script>
@stop

<div class="form-group" id="{{ $container }}">
	@if( ! empty($label))
		<label class="control-label col-md-3">{{ $label }}</label>
	@endif
	<div class="<?php if (!empty($label)): ?>col-xs-9<?php else: ?>col-xs-12<?php endif; ?>">
		<div class="row-helper hidden padding-xs-vr">
			<div class="input-group">
				<input type="text" name="{{ $field }}[key][]" disabled="disabled" class="form-control @if($slugify) slugify @endif row-key" data-separator="_" placeholder="{{ trans('cms::core.helpers.key') }}">
				<span class="input-group-addon"> - </span>
				<input type="text" name="{{ $field }}[value][]" disabled="disabled" class="form-control row-value" placeholder="{{ trans('cms::core.helpers.description') }}">
				<div class="input-group-btn">
					{!! Form::button(UI::icon('trash-o'), [
						'class' => 'btn btn-warning remove-row'
					]) !!}
				</div>
			</div>
		</div>
		<div class="rows-container"></div>
		{!! Form::button(UI::icon('plus'), [
			'class' => 'add-row btn btn-primary', 'data-hotkeys' => 'ctrl+a'
		]) !!}
	</div>
</div>