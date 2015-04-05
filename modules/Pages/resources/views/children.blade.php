<ul data-level="{{ $level }}" class="list-unstyled">
	@foreach ($childrens as $child)
	<li data-id="{{ $child->id }}" @if($child->isExpanded) class="item-expanded" @endif >
		<div class="tree-item">
			<div class="title col-xs-7">
			@if ($child->hasChildren)
				@if ($child->isExpanded)
					{!! UI::icon('minus fa-fw item-expander item-expander-expand') !!}
				@else
					{!! UI::icon('plus fa-fw item-expander') !!}
				@endif
			@endif

				@if (!acl_check('page.edit'))
					{!! UI::icon('lock fa-fw') !!} {{ $child->title }}
				@else
					{!! link_to($child->getBackendurl(), $child->title, [
						'data-icon' => $child->hasChildren ? 'folder-open fa-fw' : 'file-o fa-fw'
					]) !!}
				@endif

				@if ($child->behavior_id)
					{!! UI::label(studly_case($child->behavior_id), 'default') !!}
				@endif

				@if ($child->use_redirect)
					{!! UI::label(trans('pages::core.action.redirect', ['url' => $child->redirect_url])) !!}
				@endif
				{!! $child->getPublicLink() !!}
			</div>
			<div class="date col-xs-2 text-right text-muted">
				{{ $child->published_at }}
			</div>
			<div class="status col-xs-2 text-right">
				<?php echo $child->getStatus(); ?>
			</div>
			<div class="actions col-xs-1 text-right">
				<div class="btn-group">
					@if (acl_check('page.add'))
					{!! link_to_route('backend.page.add', '', ['parent_id' => $child->id], [
						'data-icon' => 'plus', 'class' => 'btn btn-default btn-xs'
					]) !!}
					@endif

					@if (acl_check('page.delete'))
					{!! link_to_route('backend.page.delete', '', ['id' => $child->id], [
						'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-confirm btn-danger'
					]) !!}
					@endif
				</div>
			</div>

			<div class="clearfix"></div>
		</div>

		@if ($child->isExpanded) {!! $child->childrenRows !!} @endif
	</li>
	@endforeach
</ul>