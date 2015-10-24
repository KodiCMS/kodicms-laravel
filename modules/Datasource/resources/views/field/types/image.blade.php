<div class="form-group form-inline">
	<label class="control-label col-md-3">@lang('datasource::fields.image.max_file_size')</label>
	<div class="col-md-9">
		{!! Form::text('settings[max_file_size]', $field->getMaxFileSize(), [
			'class' => 'form-control', 'id' => 'max_file_size', 'size' => 10
		]) !!}
		<span class="flags">
			<span class="label" data-value="{{ \KodiCMS\Support\Helpers\Num::bytes('100K') }}">100k</span>
			<span class="label" data-value="{{ \KodiCMS\Support\Helpers\Num::bytes('1MiB') }}">1Mib</span>
			<span class="label" data-value="{{ \KodiCMS\Support\Helpers\Num::bytes('5MiB') }}">5Mib</span>
			<span class="label" data-value="{{ \KodiCMS\Support\Helpers\Num::bytes('10MiB') }}">10Mib</span>
		</span>
	</div>
</div>
<div class="panel-heading">
	<span class="panel-title">@lang('datasource::fields.image.size_settings')</span>
</div>
<div class="panel-body well">
	<div class="form-group form-inline">
		<label class="control-label col-md-3">@lang('datasource::fields.image.size')</label>

		<div class="col-md-9">
			<div class="input-group">
				{!! Form::text('settings[width]', $field->getWidth(), ['class' => 'form-control', 'size' => 6]) !!}
				<div class="input-group-addon">x</div>
				{!! Form::text('settings[height]', $field->getHeight(), ['class' => 'form-control', 'size' => 6]) !!}
			</div>
		</div>
	</div>

	<div class="form-group form-inline">
		<label class="control-label col-md-3">@lang('datasource::fields.image.quality')</label>

		<div class="col-md-9">
			{!! Form::text('settings[quality]', $field->getQuality(), ['class' => 'form-control', 'size' => 3, 'maxlength' => 3]) !!}
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label>
					<label>
						{!! Form::hidden('settings[crop]', 0) !!}
						{!! Form::switcher('settings[crop]', 1, $field->isCropable(), [
							'id' => 'crop'
						]) !!} @lang('datasource::fields.image.crop')
					</label>
				</label>
			</div>
			<div class="checkbox">
				<label>
					<label>
						{!! Form::hidden('settings[aspect_ratio]', 0) !!}
						{!! Form::switcher('settings[aspect_ratio]', 1, $field->aspectRatio(), [
							'id' => 'aspect_ratio'
						]) !!} @lang('datasource::fields.image.aspect_ratio')
					</label>
				</label>
			</div>
		</div>
	</div>
</div>
@if($field->exists)
<div class="panel-heading">
	<span class="panel-title">@lang('datasource::fields.image.same_image_fields')</span>
</div>
<div class="panel-body">
	{!! Form::hidden('settings[same_image_fields]', '') !!}
	{!! Form::select('settings[same_image_fields]', $field->getSectionImageFields(), $field->getSelectedSameImageFields(), [
		'multiple', 'class' => 'form-control'
	]) !!}
</div>

@endif
