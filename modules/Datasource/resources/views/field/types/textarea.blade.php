<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[allow_html]', 0) !!}
				{!! Form::switcher('settings[allow_html]', 1, $field->isAllowHTML(), [
					'id' => 'allow_html'
				]) !!} @lang('datasource::fields.textarea.allow_html')
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
				]) !!} @lang('datasource::fields.textarea.filter_html')
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3" for="text_allowed_tags">@lang('datasource::fields.textarea.allowed_tags')</label>
	<div class="col-md-9">
		{!! Form::textarea('settings[allowed_tags]', $field->getAllowedHTMLTags(), [
			'id' => 'text_allowed_tags', 'rows' => 2, 'class' => 'form-control'
		]) !!}
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="rows">@lang('datasource::fields.textarea.num_rows')</label>
	<div class="col-md-9">
		{!! Form::text('settings[rows]', $field->getRows(), [
			'class' => 'form-control', 'id' => 'rows', 'size' => 3, 'maxlength' => 3
		]) !!}
	</div>
</div>