<div class="form-group form-inline">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		<div class="panel">

			@if(!empty($value))
			<div class="panel-heading panel-toggler" data-icon="chevron-down">
				<span class="panel-title">@lang('datasource::fields.images.upload_new')</span>
			</div>
			@endif

			<div class="panel-body padding-sm @if (!empty($value)) panel-spoiler @endif">
				{!! Form::file("{$key}[]", [
					'id' => $key, 'multiple', 'class' => 'upload-input',
					'data-target' => "{$key}_preview",
					'data-size' => $field->getMaxFileSize()
				]) !!}

				<p class="help-block">
					@if(!empty($field->getAllowedTypes()))
						@lang('datasource::fields.file.allowed_types_list', ['types' => implode(', ', $field->getAllowedTypes())])
					@endif
					<br />
					@lang('datasource::fields.file.max_size', ['size' => \KodiCMS\Support\Helpers\Text::bytes($field->getMaxFileSize())])
				</p>

				@if(!empty($files))
				<hr class="no-margin-b" />
				@endif
			</div>
			<div id="{{ $key }}_preview" class="panel-body padding-sm no-padding-hr clearfix" style="display: none;"></div>

			@if(!empty($value))
			<div class="panel-body padding-sm no-padding-hr clearfix">
				@foreach ($value as $file)
				<div class="thumbnail pull-left margin-xs-hr">
					{!! HTML::link($file->getEditLink(), HTML::image($file->image, null, ['style' => 'height: 100px']) ) !!}
					<label class="checkbox-inline">{!! Form::checkbox("{$key}_remove[]", $file->getId()) !!} @lang('datasource::fields.images.remove_file')</label>
				</div>
				@endforeach
			</div>
			@endif
			{!! Form::select($key . '_selected[]', [], [], [
				'class' => 'col-md-12 no-script section-images-select',
				'multiple',
				'data-related-images' => $relatedSection->getId(),
				'data-related-name' => $relatedSection->getName()
			]) !!}
		</div>
	</div>
</div>