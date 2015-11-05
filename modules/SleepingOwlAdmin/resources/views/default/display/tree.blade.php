@if ($creatable)
	<a class="btn btn-primary" href="{{ $createUrl }}"><i class="fa fa-plus"></i> {{ trans('sleepingowladmin::core.table.new-entry') }}</a>
@endif
<div class="dd nestable" data-url="{{ $url }}/reorder">
	<ol class="dd-list">
		@include(app('sleeping_owl.template')->getTemplateViewPath('display.tree_children'), ['children' => $items])
	</ol>
</div>