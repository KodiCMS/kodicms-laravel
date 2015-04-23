@if($messages)
<ul class="mail-list padding-sm-vr no-margin-t">
	@foreach($messages as $message)
	<li class="mail-item @if($message->is_read == \KodiCMS\Users\Model\Messages::STATUS_NEW) unread @endif @if($message->is_starred == \KodiCMS\Users\Model\Messages::STARRED) >starred @endif" data-id="{{ $message->id }}">
		<div class="m-chck"><label class="px-single">
				<input type="checkbox" name="" value="" class="select-checkbox"></label>
		</div>
		<div class="m-star">
			<a href="#"></a>
		</div>
		<div class="m-from">
			{!! link_to_route('backend.user.profile', $message->author, ['id' => $message->from_user_id]) !!}
		</div>
		<div class="m-subject">
			@if( $message->is_read == \KodiCMS\Users\Model\Messages::STATUS_NEW){!! UI::label(__('New'), 'info') !!}@endif

			{!! link_to_route('backend.message.read', $message->title, [$message]) !!}
		</div>
		<div class="m-date">{{ Date::format($message->created_at) }}</div>
	</li>
	@endforeach

	<div class="clearfix"></div>
</ul>
@else
<div class="panel-body">
	<h2 class="no-margin-t"><?php echo __('You don\'t have messages'); ?></h2>
</div>
@endif