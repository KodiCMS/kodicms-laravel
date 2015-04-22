@if($navigation)
<div id="main-menu-inner">
	@event('view.menu.before')

	<ul class="navigation">
		@event('view.navigation.before')

		@foreach($navigation->getPages() as $item)
		<li @if($item->isActive())class="active"@endif>
			<a href="{{ $item->getUrl() }}">
				{!! $item->getIcon() !!}
				<span class="mm-text">{!! $item->getName() !!}</span>
			</a>
		</li>
		@endforeach

		@foreach($navigation->getSections() as $section)
		@if(count($section) > 0 OR count($section->getSections()) > 0)
		<li class="mm-dropdown @if($section->isActive()) open @endif">
			<a href="#">
				{!! $section->getIcon() !!}
				<span class="mm-text">{!! $section->getName() !!}</span>
			</a>
			<ul>
				@foreach($section as $item)
				<li @if ($item->isActive())class="active"@endif>
					<a href="{{ $item->getUrl() }}">
						{!! $item->getIcon() !!}
						<span class="mm-text">{!! $item->getName() !!}</span>
					</a>
				</li>
				@endforeach

				@foreach($section->getSections() as $sub_section )
				@if(!(count($sub_section) > 0)) <?php continue; ?>@endif
				<li class="mm-dropdown @if($section->isActive()) open @endif">
					<a href="#">
						{!! $sub_section->getIcon() !!}
						<span class="mm-text">{!! $sub_section->getName() !!}</span>
					</a>

					<ul>
						@foreach($sub_section as $sub_item)
						<li @if ($sub_item->isActive())class="active"@endif>
							<a href="{{ $sub_item->getUrl() }}">
								{!! $sub_item->getIcon() !!}
								<span class="mm-text">{!! $sub_item->getName() !!}</span>
							</a>
						</li>
						@endforeach
					</ul>
				</li>
				@endforeach
			</ul>
		</li>
		@endif
		@endforeach

		@event('view.navigation.after')
	</ul>

	@event('view.menu.after')
</div>
@endif