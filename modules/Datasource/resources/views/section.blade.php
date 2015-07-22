	<div class="mail-container-header">
		{!! UI::icon($section->getIcon()) !!} {{ $section->getName() }}

		<div class="btn-group pull-right">

		</div>
	</div>
	<div class="mail-controls clearfix headline-actions">

	</div>
	<?php if (isset($toolbar)): ?>
	<div class="mail-controls">
		<?php echo $toolbar; ?>
	</div>
	<?php endif; ?>

	<div class="mail-list headline">
		{!! $headline !!}
	</div>