<ul class="navigation">
	@foreach($navigation->getPages() as $item)
    <li @if($item->isActive())class="active"@endif>
        <a href="{{ $item->getUrl() }}">
            {!! $item->getIcon() !!}
            <span class="mm-text">{!! $item->getName() !!}</span>
        </a>
    </li>
	@endforeach

    @foreach($navigation->getSections() as $section)
    @include('cms::navigation.sections', ['section' => $section])
    @endforeach
</ul>
