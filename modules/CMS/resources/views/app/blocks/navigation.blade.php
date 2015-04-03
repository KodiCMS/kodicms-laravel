@if ($navigation)
<div id="main-menu-inner">
	<ul class="navigation">
		@foreach ($navigation->getPages() as $item)
		<li @if($item->isActive())class="active"@endif>
			<a href="{{ $item->getUrl() }}">
				@if ($item->icon) {!! UI::icon($item->icon . ' menu-icon') !!}@endif
				<span class="mm-text">{{ $item->getName() }}</span>
			</a>
		</li>
		@endforeach

		@foreach ($navigation->getSections() as $section)

		@if (count($section) > 0 OR count($section->getSections()) > 0)
		<li class="mm-dropdown @if($section->isActive())open@endif">
			<a href="#">
				@if ($section->icon) {!! UI::icon($section->icon . ' menu-icon') !!}@endif
				<span class="mm-text"><?php echo $section->name(); ?></span>
			</a>
			<ul>
				@foreach($section as $item)
				<li @if ($item->isActive())class="active"@endif>
					<a href="{{ $item->getUrl() }}">
						@if ($item->icon){!! UI::icon($item->icon . ' menu-icon') !!}@endif
						<span class="mm-text">{{ $item->getName() }}</span>
					</a>
				</li>
				@endforeach

				@foreach($section->sections() as $sub_section )
				@if(!(count($sub_section) > 0)) @{{ continue }} @endif
				<li class="mm-dropdown @if($section->isActive())open@endif">
						<a href="#">
						@if ($sub_section->icon){!! UI::icon($sub_section->icon . ' menu-icon') !!}@endif
						<span class="mm-text">{{ $sub_section->getName() }}</span>
					</a>

					<ul>
						@foreach($sub_section as $sub_item)
						<li @if ($sub_item->isActive())class="active"@endif>
							<a href="{{ $sub_item->getUrl() }}">
								@if ($sub_item->icon): ?>{!! UI::icon($sub_item->icon . ' menu-icon') !!}@endif
								<span class="mm-text">{{ $sub_item->getName() }}</span>
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
	</ul>
</div>
@endif