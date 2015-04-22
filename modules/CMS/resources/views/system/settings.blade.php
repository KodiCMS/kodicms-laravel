{!! Form::open([
	'route' => 'api.settings.update',
	'class' => 'form-horizontal form-ajax panel tabbable'
]) !!}
@event('view.settings.top')

<div class="panel-heading" data-icon="info">
	<span class="panel-title">@lang('cms::system.tab.settings.site_information')</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.site_title')</label>
		<div class="col-md-9">
			{!! Form::text('config[cms][title]', config('cms.title'), [
				'class' => 'form-control'
			]) !!}
		</div>

	</div>
	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.site_description')</label>
		<div class="col-md-9">
			{!! Form::textarea('config[cms][description]', config('cms.description'), [
				'class' => 'form-control', 'rows' => 3
			]) !!}
		</div>
	</div>
</div>

<div class="panel-heading" data-icon="globe">
	<span class="panel-title">@lang('cms::system.tab.settings.regional')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.default_locale')</label>
		<div class="col-md-3">
			{!! Form::select('config[app][locale]', $availableLocales, config('app.locale'), ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.date_format')</label>
		<div class="col-md-3">
			{!! Form::select('config[cms][date_format]', $dateFormats, config('cms.date_format')) !!}
		</div>
	</div>
</div>
<div class="panel-heading" data-icon="cog">
	<span class="panel-title">@lang('cms::system.tab.settings.debug')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.debug_mode')</label>
		<div class="col-md-2">
			<?php echo Form::select('config[app][debug]', ['No', 'Yes'], config('app.debug')); ?>
		</div>
	</div>
</div>
<div class="panel-heading" data-icon="edit">
	<span class="panel-title">@lang('cms::system.tab.settings.wysiwig')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.html_editor')</label>
		<div class="col-md-9">
			<?php echo Form::select('config[cms][default_html_editor]', $htmlEditors, config('cms.default_html_editor')); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.code_editor')</label>
		<div class="col-md-9">
			<?php echo Form::select('config[cms][default_code_editor]', $codeEditors, config('cms.default_code_editor')); ?>
		</div>
	</div>
</div>

<div class="panel-heading" data-icon="hdd-o">
	<span class="panel-title">@lang('cms::system.tab.settings.session')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('cms::system.label.settings.session_storage')</label>
		<div class="col-md-9">
			{!! Form::select('config[session][driver]', [
				'file' => 'File', 'database' => 'Database', 'cookie' => 'Cookie', 'apc' => 'APC'
			], config('session.driver')) !!}
		</div>
	</div>
</div>

@event('view.settings.bottom')

<div class="form-actions panel-footer">
	{!! Form::button(UI::icon('check') . ' ' . trans('cms::system.button.settings.save'), [
		'type' => 'submit',
		'class' => 'btn btn-lg btn-primary',
		'data-hotkeys' => 'ctrl+s'
	]) !!}
</div>

{!! Form::close() !!}