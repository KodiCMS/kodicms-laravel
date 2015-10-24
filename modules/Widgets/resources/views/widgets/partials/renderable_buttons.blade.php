
{!! Form::button(trans('widgets::core.settings.related_widgets'), [
	'data-icon' => 'link',
	'class' => 'btn btn-sm btn-success btn-labeled',
	'data-toggle' => 'modal',
	 'data-target' => '#relatedWidgetsModal'
]) !!}

{!! Form::button(trans('widgets::core.title.assets'), [
	'data-icon' => 'file-o',
	'class' => 'btn btn-sm btn-warning btn-labeled',
	'data-toggle' => 'modal',
	 'data-target' => '#mediaPackagesModal'
]) !!}

<div class="modal fade" id="relatedWidgetsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" data-icon="link">@lang('widgets::core.settings.related_widgets')</h4>
			</div>
			<div class="modal-body">
				{!! Form::select('relatedWidgets[]', $widgetList, $widget->getRalatedWidgets()->lists('id')->all(), [
					'class' => 'form-control', 'multiple', 'id' => 'relatedWidgets'
				]) !!}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="mediaPackagesModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" data-icon="file-o">@lang('widgets::core.title.assets')</h4>
			</div>
			<div class="modal-body">
				{!! Form::select('settings[media_packages][]', $assetsPackages, $widget->getMediaPackages(), [
				'class' => 'form-control', 'multiple'
			]) !!}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
			</div>
		</div>
	</div>
</div>