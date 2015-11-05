<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
	<label for="{{ $name }}">{{ $label }}</label>
	<div class="imageUpload" data-target="{{ route('admin.formitems.image.uploadFile') }}" data-token="{{ csrf_token() }}">
		<div>
			<div class="thumbnail">
				<div class="no-value {{ empty($value) ? '' : 'hidden' }}">
					<i class="fa fa-fw fa-file-o"></i> no file
				</div>
				<div class="has-value {{ empty($value) ? 'hidden' : '' }}">
					<a href="{{ asset($value) }}" data-toggle="tooltip" title="{{ trans('sleepingowladmin::core.table.download') }}"><i class="fa fa-fw fa-file-o"></i> <span>{{ $value }}</span></a>
				</div>
			</div>
		</div>
		<div>
			<div class="btn btn-primary imageBrowse"><i class="fa fa-upload"></i> {{ trans('sleepingowladmin::core.file.browse') }}</div>
			<div class="btn btn-danger imageRemove"><i class="fa fa-times"></i> {{ trans('sleepingowladmin::core.file.remove') }}</div>
		</div>
		<input name="{{ $name }}" class="imageValue" type="hidden" value="{{ $value }}">
		<div class="errors">
			@include(app('sleeping_owl.template')->getTemplateViewPath('formitem.errors'))
		</div>
	</div>
</div>