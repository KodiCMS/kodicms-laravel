{!! Form::open([
	'route' => 'api.settings.update',
	'class' => 'form-horizontal form-ajax panel tabbable'
]) !!}


@event('view.settings.top')

<div class="panel-heading" data-icon="info">
	<span class="panel-title"><?php echo __('Site information'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3"><?php echo __('Site title'); ?></label>
		<div class="col-md-9">
			{!! Form::text('config[cms][title]', config('cms.title'), [
				'class' => 'form-control'
			]) !!}
			<p class="help-block"><?php echo __('This text will be present at backend and can be used in frontend pages.'); ?></p>
		</div>

	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Site description'); ?></label>
		<div class="col-md-9">
			{!! Form::textarea('config[cms][description]', config('cms.description'), [
				'class' => 'form-control', 'rows' => 3
			]) !!}
		</div>
	</div>
</div>

<div class="panel-heading" data-icon="globe">
	<span class="panel-title"><?php echo __('Regional settings'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Default interface language'); ?></label>
		<div class="col-md-3">
			{!! Form::select('config[app][locale]', ['ru' => 'ru', 'en' => 'en'], config('app.locale'), ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Date format'); ?></label>
		<div class="col-md-3">
			{!! Form::select('config[cms][date_format]', $dateFormats, config('cms.date_format')) !!}
		</div>
	</div>
</div>
<div class="panel-heading" data-icon="cog">
	<span class="panel-title"><?php echo __('Debug'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Debug mode'); ?></label>
		<div class="col-md-2">
			<?php echo Form::select('config[app][debug]', ['No', 'Yes'], config('app.debug')); ?>
		</div>
	</div>
</div>
<div class="panel-heading" data-icon="edit">
	<span class="panel-title"><?php echo __('Page settings'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Default HTML editor'); ?></label>
		<div class="col-md-9">
			<?php echo Form::select('config[cms][default_html_editor]', $htmlEditors, config('cms.default_html_editor')); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Default Code editor'); ?></label>
		<div class="col-md-9">
			<?php echo Form::select('config[cms][default_code_editor]', $codeEditors, config('cms.default_code_editor')); ?>
		</div>
	</div>
</div>

<div class="panel-heading" data-icon="hdd-o">
	<span class="panel-title"><?php echo __('Session settings'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Session storage'); ?></label>
		<div class="col-md-9">
			{!! Form::select('config[session][driver]', [
				'file' => 'File', 'database' => 'Database', 'cookie' => 'Cookie', 'apc' => 'APC'
			], config('session.driver')) !!}
		</div>
	</div>
</div>

@event('view.settings.bottom')

<div class="form-actions panel-footer">
	{!! Form::button(UI::icon('check') . ' ' . __('Save settings'), [
		'type' => 'submit',
		'class' => 'btn btn-lg btn-primary',
		'data-hotkeys' => 'ctrl+s'
	]) !!}
</div>

{!! Form::close() !!}