<div class="panel-heading">
	<span class="panel-title" data-icon="hdd-o">@lang('widgets::core.title.template')</span>
</div>
<div class="note note-info no-margin-b">
	<div class="row">
		<div class="col-sm-12">
			<strong>@lang('widgets::core.settings.template_parameters'): </strong>
			@foreach ($commentKeys as $param)
				{!! UI::label($param) !!}
			@endforeach
		</div>
	</div>
</div>

<?php
$defaultTemplateButton = $widget->getDefaultFrontendTemplate()
	? link_to_route('backend.widget.template', UI::hidden(trans('widgets::core.button.defaultTemplate'), ['sm', 'xs']), [$widget->id], [
			'data-icon' => 'desktop', 'class' => 'btn popup fancybox.iframe btn-default btn-labeled',
			'id' => 'defaultTemplateButton'
	])
	: null;
?>

{!! view('widgets::snippet.snippet_select', [
'template' => $widget->template,
'default' => $defaultTemplateButton
]) !!}

<div class="panel-body">
	<div class="form-group form-inline">
		<label class="control-label col-xs-2">@lang('widgets::core.settings.header')</label>
		<div class="col-xs-10">
			{!! Form::text('settings[header]', $widget->getHeader(), ['class' => 'form-control', 'size' => 40]) !!}
		</div>
	</div>
</div>