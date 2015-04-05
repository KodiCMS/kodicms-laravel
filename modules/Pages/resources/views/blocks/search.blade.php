{!! Form::open(['class' => 'form-inline form-search']) !!}
<div class="input-group input-group-sm">
	{!! Form::text('search', NULL, [
			'id' => 'page-seacrh-input',
			'class' => 'form-control no-margin-b',
			'placeholder' => trans('pages::pages.field.search')
	]) !!}

	<div class="input-group-btn">
		{!! Form::button(trans('pages::pages.button.search'), [
			'type' => 'submit',
			'data-icon' => 'search',
			'class' => 'btn btn-default'
		]) !!}
	</div>
</div>
{!! Form::close() !!}