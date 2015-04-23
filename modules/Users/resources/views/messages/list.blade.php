<div class="page-mail">
	<div class="mail-container-header  no-margin-vr">
		{{ $currentPage->getName() }}
	</div>

	<div class="mail-controls clearfix">
		<div class="btn-toolbar" role="toolbar">
			{!! link_to_route('backend.message.create', trans('users::message.button.create'), [], [
				'class' => 'btn btn-primary', 'data-icon' => 'envelope-o', 'data-hotkeys' => 'ctrl+a'
			]) !!}

			<button type="button" class="btn btn-default btn-check-new"><i class="fa fa-repeat"></i></button>

			@if ($messages)
			<button type="button" class="btn btn-remove btn-danger"><i class="fa fa-trash-o"></i></button>
			@endif
		</div>
	</div>

	<div id="messages-container">
		@include('users::messages.messages', [
			'messages' => $messages
		])
	</div>
</div>