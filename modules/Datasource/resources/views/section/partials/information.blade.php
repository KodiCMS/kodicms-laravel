<script>
$(function() {
	function formatState(state) {
		if (!state.id) return state.text; // optgroup
		return $("<span><i class='fa fa-" + state.id + " fa-fw fa-lg'/> " + state.text+"</span>");
	}
	$("#icons").select2({
		templateResult: formatState,
		templateSelection: formatState
	});

	function chekShowInRootStatus() {
		var $cont = $('#menu-position-cont');
		$('#show_in_root_menu').is(':checked') ? $cont.show() : $cont.hide();
	}
	chekShowInRootStatus();
	$('#show_in_root_menu').on('change', chekShowInRootStatus);
});
</script>

<div class="panel-heading" data-icon="info">
	<span class="panel-title">@lang('datasource::core.title.information')</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">@lang('datasource::core.information.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="description">@lang('datasource::core.information.description')</label>
		<div class="col-md-9">
			{!! Form::textarea('description', null, [
				'class' => 'form-control', 'id' => 'description', 'rows' => 3
			]) !!}
		</div>
	</div>

	<div class="well well-sm">
		<div class="form-group">
			<div class="col-md-9 col-md-offset-3">
				<div class="checkbox">
					<label>
						{!! Form::hidden('settings[show_in_root_menu]', 0) !!}
						{!! Form::switcher('settings[show_in_root_menu]', 1, $section->showInRootMenu(), ['id' => 'show_in_root_menu']) !!} @lang('datasource::core.information.show_in_root')
					</label>
				</div>
			</div>
		</div>
		<div class="form-group form-inline" id="menu-position-cont">
			<label class="control-label col-md-3" for="name">@lang('datasource::core.information.menu_position')</label>
			<div class="col-md-9">
				{!! Form::text('settings[menu_position]', $section->getMenuPosition(), [
					'class' => 'form-control',
					'id' => 'menu_position',
					'size' => 5
				]) !!}
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="icon">@lang('datasource::core.information.icon')</label>
		<div class="col-md-9">
			{!! Form::select('settings[icon]', array_unique(config('icons', [])), $section->getIcon(), ['class' => 'form-control', 'id' => 'icons']) !!}
		</div>
	</div>
</div>