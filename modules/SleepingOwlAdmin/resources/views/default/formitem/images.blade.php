<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
	<label for="{{ $name }}">{{ $label }}</label>
	<div class="imageUploadMultiple" data-target="{{ route('admin.formitems.image.uploadImage') }}" data-token="{{ csrf_token() }}">
		<div class="row form-group images-group">
			@foreach ($value as $image)
				<div class="col-xs-6 col-md-3 imageThumbnail">
					<div class="thumbnail">
						<img data-value="{{ $image }}" src="{{ asset($image) }}" />
						<a href="#" class="imageRemove">Remove</a>
					</div>
				</div>
			@endforeach
		</div>
		<div>
			<div class="btn btn-primary imageBrowse"><i class="fa fa-upload"></i> {{ trans('sleepingowladmin::core.image.browseMultiple') }}</div>
		</div>
		<input name="{{ $name }}" class="imageValue" type="hidden" value="{{ implode(',', $value) }}">
		<div class="errors">
			@include(app('sleeping_owl.template')->getTemplateViewPath('formitem.errors'))
		</div>
	</div>
</div>