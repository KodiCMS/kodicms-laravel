<div class="panel-heading">
	<span class="panel-title" data-icon="cubes">
		@lang('widgets::core.title.list') <span class="text-muted">[{{ $page->getLayout() }}]</span>
	</h4>
</div>
<div class="panel-body no-padding">
	<iframe src="{{ route('backend.pages.wysiwyg', [$page]) }}" style="width:100%;height:500px;border:none;"></iframe>
</div>

@section('scripts')
	<script src="/backend/cms/js/PageWidgetController.js"></script>
@stop