<ul data-level="{{ $level }}" class="list-unstyled">
	@foreach ($children as $child)
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

			@if(!$child->hasLayout())
				{!! UI::icon('exclamation-triangle fa-fw text-warning', ['title' => trans('pages::core.messages.layout_not_set')]) !!}
			@endif

			@if (!acl_check('page.edit'))
				{!! UI::icon('lock fa-fw') !!} {{ $child->title }}
			@else
				{!! link_to_route('backend.page.edit', $child->title, [$child], [
					'data-icon' => $child->hasChildren ? 'folder-open fa-fw' : 'file-o fa-fw'
				]) !!}
			@endif

			@if ($child->hasBehavior())
				{!! UI::label(trans('pages::core.label.page.behavior', ['behavior' => $child->getBehaviorTitle()]), 'default') !!}
			@endif

			@if ($child->is_redirect)
				{!! UI::label(trans('pages::core.label.page.redirect', ['url' => $child->redirect_url]), 'danger') !!}
			@endif
				{!! $child->getPublicLink() !!}
			</div>
			<div class="date col-xs-2 text-right text-muted">
				{{ Date::format($child->published_at) }}
			</div>
			<div class="status col-xs-2 text-right">
				<?php echo $child->getStatus(); ?>
			</div>
			<div class="actions col-xs-1 text-right">
					@if (acl_check('page.create'))
					{!! link_to_route('backend.page.create', '', ['parent_id' => $child->id], [
						'data-icon' => 'plus', 'class' => 'btn btn-default btn-xs'
					]) !!}
					@endif

					@if (acl_check('page.delete'))
					{!! Form::open(['route' => ['backend.page.delete', $child], 'style' => 'display: inline-block']) !!}
						{!! Form::button('', [
							'type' => 'submit',
							'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
							]) !!}
					{!! Form::close() !!}
					@endif
			</div>

			<div class="clearfix"></div>
		</div>

		@if ($child->isExpanded) {!! $child->childrenRows !!} @endif
	</li>
	@endforeach
</ul>