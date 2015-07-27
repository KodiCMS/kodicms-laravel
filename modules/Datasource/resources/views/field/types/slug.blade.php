<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[from_document_title]', 0) !!}
				{!! Form::switcher('settings[from_document_title]', 1, $field->fromDocumentTitle(), [
					'id' => 'from_document_title'
				]) !!} @lang('datasource::fields.slug.from_document_title')
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[is_unique]', 0) !!}
				{!! Form::switcher('settings[is_unique]', 1, $field->isUnique(), [
					'id' => 'is_unique'
				]) !!} @lang('datasource::fields.slug.is_unique')
			</label>
		</div>
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="separator"> @lang('datasource::fields.slug.separator')</label>
	<div class="col-md-9">
		{!! Form::text('separator', $field->getSeparator(), [
			'class' => 'form-control', 'id' => 'separator', 'size' => 1, 'maxlength' => 1
		]) !!}
	</div>
</div>