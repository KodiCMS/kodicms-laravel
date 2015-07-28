<div class="form-group">
	<label class="control-label col-md-3">@lang('datasource::fields.html.wysiwyg')</label>
	<div class="col-md-4">
		{!! Form::select('settings[wysiwyg]', WYSIWYG::htmlSelect(), $field->getWysiwyg()) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[remove_empty_tags]', 0) !!}
				{!! Form::switcher('settings[remove_empty_tags]', 1, $field->isRemoveEmptyTags(), [
				'id' => 'remove_empty_tags'
				]) !!} @lang('datasource::fields.html.remove_empty_tags')
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[filter_html]', 0) !!}
				{!! Form::switcher('settings[filter_html]', 1, $field->isFilterHTML(), [
				'id' => 'filter_html'
				]) !!} @lang('datasource::fields.html.filter_html')
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3" for="text_allowed_tags">@lang('datasource::fields.html.allowed_tags')</label>
	<div class="col-md-9">
		{!! Form::textarea('settings[allowed_tags]', $field->getAllowedHTMLTags(), [
		'id' => 'text_allowed_tags', 'rows' => 2, 'class' => 'form-control'
		]) !!}
	</div>
</div>