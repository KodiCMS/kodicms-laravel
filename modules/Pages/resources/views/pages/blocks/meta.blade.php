<div class="panel-body">
	@foreach ($page->getMetaFields() as $field)
	<div class="form-group" id="field-{{ $field }}>">
		<label class="control-label col-md-3" for="{{ $field }}">
			@lang("pages::core.field.{$field}")
		</label>
		<div class="col-md-9">
			{!! Form::text($field, NULL, [
				'class' => 'form-control', 'id' => $field
			]) !!}
			<span class="help-block text-muted"></span>
		</div>
	</div>
	@endforeach

	<hr class="panel-wide" />

	<div class="form-group">
		<label class="control-label col-md-3">@lang("pages::core.field.robots")</label>
		<div class="col-md-4">
			{!! Form::select('robots', $page->getRobotsList(), $page->robots) !!}
		</div>
	</div>
</div>