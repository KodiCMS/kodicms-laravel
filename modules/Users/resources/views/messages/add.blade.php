{!! Form::open([
	'route' => 'api.user.message.post',
	'class' => 'form-horizontal panel form-ajax',
]) !!}
	<div class="panel-heading">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3">@lang('users::message.field.title')</label>
			<div class="col-md-9">
				{!! Form::text('title', NULL, [
					'class' => 'form-control', 'id' => 'title'
				]) !!}
			</div>
		</div>

		@if ($to)
		{!! Form::hidden('to', $to ) !!}
		@else
		<br />

		<div class="form-group">
			<label class="control-label col-md-3">@lang('users::message.field.to')</label>
			<div class="col-md-9">
				{!! Form::text('to[]', $to, [
					'autocomplete' => 'off', 'id' => 'messageTo'
				]) !!}
			</div>
		</div>
		@endif
	</div>

	{!! Form::textarea('message', NULL, [
		'class' => 'form-control', 'id' => 'message-content', 'rows' => 2
	]) !!}
	<div class="panel-footer form-actions">
		{!! Form::button(trans('users::message.button.send'), [
			'class' => 'btn-lg btn-primary', 'type' => 'submit'
		]) !!}
	</div>
{!! Form::close() !!}