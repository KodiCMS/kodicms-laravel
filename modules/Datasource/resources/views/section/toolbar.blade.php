<script type="text/javascript">
$(function() {
	$(document).on('click', '.headline tr[data-id]', function(event) {
		if (event.target.type !== 'checkbox') {
			$(':checkbox', this).trigger('click');
		}
	});

	$(document).on('change', '.headline [data-id] .doc-checkbox', function() {
		checkbox_check();
	});

	checkbox_check();

	$('.checkbox-control .action[data-action]').on('click', function(e) {
		var action = $(this).data('action');
		var sibling = ':checked';

		if(action == 'check_all') sibling = ':not(:checked)';

		$('.headline [data-id] .doc-checkbox' + sibling).trigger('click');

		e.preventDefault();
	});

	$('.headline-actions .doc-actions .action').on('click', function() {
		var action = $(this).data('action');

		var data = Api.serializeObject($('.headline [data-id] .doc-checkbox:checked'));

		data['page'] = $.query.get('page') || 1;
		data['section_id'] = SECTION.id;

		Api.post('/api.datasource.document.' + action, data, function(response) {
			updateHeadline();
		});
	});
});

function checkbox_check() {
	var $checkboxes = $('.headline [data-id] .doc-checkbox');
	var $total_checked = $checkboxes.filter(':checked').size();

	if($total_checked > 0)
		$('.headline-actions .doc-actions .action').removeClass('disabled');
	else
		$('.headline-actions .doc-actions .action').addClass('disabled');

	$checkboxes.each(function() {
		if (!$(this).prop('checked')) {
			$(this).closest('[data-id]').removeClass("info");
		} else {
			$(this).closest('[data-id]').addClass("info");
		}
	});
}
</script>

<div class="btn-toolbar" role="toolbar">

	<div class="btn-group">
		{!! link_to_route('backend.datasource.document.create', $section->getCreateDocumentButtonTitle(), [$section->getId()], [
			'data-icon' => 'plus',
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn btn-primary'
		]) !!}
	</div>

	<div class="btn-group checkbox-control pull-right">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-check-square-o"></i>&nbsp;<i class="fa fa-caret-down"></i>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="#" class="action" data-action="check_all">@lang('datasource::core.toolbar.check_all')</a></li>
				<li class="divider"></li>
				<li><a href="#" class="action" data-action="uncheck_all">@lang('datasource::core.toolbar.uncheck_all')</a></li>
			</ul>
		</div>
	</div>
	<div class="btn-group doc-actions pull-right">
		<button type="button" data-action="remove" class="btn btn-danger action disabled" data-icon="trash-o" title="@lang('datasource::core.toolbar.remove_selected')"></button>
	</div>
</div>