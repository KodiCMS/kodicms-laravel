<script type="text/javascript">
	$(function() {
		$("#message-details-reply").expandingInput({
			target: 'textarea',
			placeholder: __('Click here to <strong>Reply</strong>'),
			onAfterExpand: function() {
				CMS.filters.switchOn('message-conent', DEFAULT_HTML_EDITOR);
			}
		});
	});
</script>
<div class="page-mail">
	<div class="mail-container-header no-margin-vr">
		<span class="m-details-star"><i class="fa fa-star @if( $message->is_starred == Model_API_Message::NOT_STARRED): ?>-o @endif "></i></span>
		<small>{{ __('Subject') }}:</small> {{ $message->title }}
	</div>

	<div class="mail-controls clearfix">
		<div class="btn-toolbar wide-btns" role="toolbar">

		</div>
	</div>

	@foreach ($messages as $msg)
	<?php echo $tpl->set('message', $msg)->set('from_user', ORM::factory('user', $message->from_user_id)); ?>
	@endforeach

	<?php echo $tpl->set('message', $message)->set('from_user', $from_user); ?>

	<div class="message-details-reply">
		<h4><?php echo __('Answer'); ?></h4>
		<?php echo Form::open(NULL, array('id' => 'message-details-reply')); ?>
		<?php echo Form::hidden('parent_id', $message->id); ?>
		<?php echo Form::textarea('content', NULL, array('id' => 'message-conent', 'class' => 'form-control', 'rows' => 4)); ?>
		<div class="expanding-input-hidden">
			<?php echo UI::button(__('Send message'), array('class' => 'btn-primary pull-right')); ?>
		</div>
		<?php echo Form::close(); ?>
	</div>

	<div class="clearfix"></div>
</div>