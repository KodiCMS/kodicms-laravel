<ul class="navigation">
	@foreach($navigation->getRootSection()->getPages() as $item)
    <li @if($item->isActive())class="active"@endif>
        <a href="{{ $item->getUrl() }}">
            {!! $item->getIcon() !!}
            <span class="mm-text">{!! $item->getName() !!}</span>
        </a>
    </li>
	@endforeach

    @foreach($navigation->getRootSection()->getSections() as $section)
    @include('cms::navigation.sections', ['section' => $section])
    @endforeach
</ul>
