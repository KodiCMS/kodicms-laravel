<div class="panel-heading panel-toggler" data-hotkeys="shift+w">
	<span class="panel-title" data-icon="cubes">@lang('widgets::core.title.list')</h4>
</div>
<div class="panel-body panel-spoiler">
	@if (is_null($page->id))
	<h4>@lang('widgets::core.title.copy_widgets')</h4>
	<select name="widgets[from_page_id]" class="col-md-12">
		<option value="">@lang('widgets::core.label.dont_copy_widgets')</option>
		@foreach ($pages as $p): ?>
		<option value="<?php echo($p['id']); ?>" <?php echo($p['id'] == $page->parent_id ? ' selected="selected"': ''); ?> ><?php echo str_repeat('- ', $p['level'] * 2).$p['title']; ?></option>
		<?php endforeach; ?>
	</select>
	@else

	@if (acl_check('widgets.location'))
	<a class="btn btn-success fancybox.ajax popup" href="/api-widget.list/<?php echo $page->id; ?>" id="addWidgetToPage"><i class="fa fa-plus"></i> <?php echo __( 'Add widget to page' ); ?></a>

	<?php if (ACL::check('layout.rebuild')): ?>
	<?php echo UI::button(__('Rebuild blocks'), array(
			'icon' => UI::icon( 'refresh' ),
			'class' => 'btn-inverse btn-xs',
			'data-api-url' => 'layout.rebuild',
			'data-method' => Request::POST
	)); ?>
	@endif

	<br /><br />
	@endif
	<table class="table table-hover" id="widget-list">
		<colgroup>
			<col />
			<col width="100px" />
			<col width="280px" />
		</colgroup>
		<tbody>
		<?php foreach ($widgets as $widget): ?>
		<?php echo View::factory( 'widgets/ajax/row', array(
				'widget' => $widget, 'page' => $page
		)); ?>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>