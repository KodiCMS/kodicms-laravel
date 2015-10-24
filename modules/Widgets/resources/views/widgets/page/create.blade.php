<div class="panel-heading">
	<span class="panel-title" data-icon="cubes">@lang('widgets::core.title.list')</h4>
</div>
<div class="panel-body">
	<h4>@lang('widgets::core.title.copy_widgets')</h4>
	<select name="widgets[from_page_id]" class="col-md-12">
		<option value="">@lang('widgets::core.label.dont_copy_widgets')</option>
		@foreach ($pages as $id => $title)
		<option value="{{ $id }}" {{ $id == $page->parent_id ? ' selected="selected"': '' }} > {{ $title }}</option>
		@endforeach
	</select>
</div>