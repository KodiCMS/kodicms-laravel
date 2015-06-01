{!! Form::model($emailTemplate, [
	'route' => [$action, $emailTemplate],
	'class' => 'form-horizontal panel'
]) !!}
<div class="panel-heading">
	<span class="panel-title">@lang('email::core.tab.general')</span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="status">@lang('email::core.field.templates.status')</label>
		<div class="col-md-3">
			{!! Form::select('status', $emailTemplate->statuses(), null, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="use_queue">@lang('email::core.field.templates.use_queue')</label>
		<div class="col-md-3">
			{!! Form::select('use_queue', $emailTemplate->queueStatuses(), null, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="email_event_id">@lang('email::core.field.templates.email_event')</label>
		<div class="col-md-6">
			<div class="input-group">
				{!! Form::select('email_event_id', $emailEvents, null, ['id' => 'email_event_id', 'class' => 'form-control']) !!}
				@if (acl_check('email.type.create'))
					<div class="input-group-btn">
						{!! link_to_route('backend.email.event.create', trans('email::core.button.events.create'), [], [
							'class' => 'btn btn-primary', 'data-icon' => 'plus'
						]) !!}
					</div>
				@endif
			</div>
		</div>
	</div>

	<hr/>

	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="subject">@lang('email::core.field.templates.subject')</label>
		<div class="col-md-9">
			{!! Form::text('subject', null, [
				'class' => 'form-control', 'id' => 'subject'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="email_from">@lang('email::core.field.templates.email_from')</label>
		<div class="col-md-3">
			{!! Form::text('email_from', null, [
				'class' => 'form-control', 'id' => 'email_from'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="email_to">@lang('email::core.field.templates.email_to')</label>
		<div class="col-md-3">
			{!! Form::text('email_to', null, [
				'class' => 'form-control', 'id' => 'email_to'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="cc">@lang('email::core.field.templates.cc')</label>
		<div class="col-md-3">
			{!! Form::text('cc', null, [
				'class' => 'form-control', 'id' => 'cc'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="bcc">@lang('email::core.field.templates.bcc')</label>
		<div class="col-md-3">
			{!! Form::text('bcc', null, [
				'class' => 'form-control', 'id' => 'bcc'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="reply_to">@lang('email::core.field.templates.reply_to')</label>
		<div class="col-md-3">
			{!! Form::text('reply_to', null, [
				'class' => 'form-control', 'id' => 'reply_to'
			]) !!}
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title">@lang('email::core.tab.message')</span>
</div>
<div class="note note-info no-margin-vr">
	{!! UI::icon('lightbulb-o fa-lg') !!} @lang('email::core.tab.message_info', [
		'link' => link_to('http://responsiveemailpatterns.com', null, ['target' => '_blank'])
	])
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="message_type">@lang('email::core.field.templates.message_type')</label>
		<div class="col-md-9">
			<label class="radio-inline">
				{!! Form::radio('message_type', KodiCMS\Email\Model\EmailTemplate::TYPE_TEXT) !!}
				@lang('email::core.message_types.text')
			</label>
			<label class="radio-inline">
				{!! Form::radio('message_type', KodiCMS\Email\Model\EmailTemplate::TYPE_HTML) !!}
				@lang('email::core.message_types.html')
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="message">@lang('email::core.field.templates.message')</label>
		<div class="col-md-9">
			{!! Form::textarea('message', null, ['id' => 'message', 'class' => 'form-control', 'rows' => 10]) !!}
		</div>
	</div>

	<div class="form-group" id="field_description"><div class="col-md-offset-3 col-md-9"></div></div>
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.email.template.list'])
</div>
{!! Form::close() !!}