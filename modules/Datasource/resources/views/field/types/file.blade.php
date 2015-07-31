<div class="form-group">
	<label class="control-label col-md-3">@lang('datasource::fields.file.allowed_types')</label>
	<div class="col-md-9">
		{!! Form::select('settings[allowed_types][]', $field->getMimeTypes(), $field->getAllowedTypes(), [
			'id' => 'allowed_types', 'multiple'
		]) !!}
		<br /><br />
		<span class="flags" data-array="true">
			<span class="label" data-value="bmp,gif,jpg,png,tif">Image types</span>
			<span class="label" data-value="doc,docx,xls,txt,pdf">Document types</span>
			<span class="label" data-value="rar,zip,tar,gz,7z">Archive types</span>
			<span class="label" data-value="mp3,wav">Audio types</span>
		</span>
	</div>
</div>

<hr />
<div class="form-group form-inline">
	<label class="control-label col-md-3">@lang('datasource::fields.file.max_file_size')</label>
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