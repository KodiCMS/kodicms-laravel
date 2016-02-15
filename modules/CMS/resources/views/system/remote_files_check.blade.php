@if (!empty($files['new_files']))
<div class="panel-heading">
	<div class="panel-title">@lang('cms::core.label.update.new_files')</div>
</div>
<ul class="list-group">
	@foreach ($files['new_files'] as $link)
		<li class="list-group-item">{!! HTML::link($link) !!}</li>
	@endforeach
</ul>
@endif

@if (!empty($files['diff_files']))
<div class="panel-heading">
	<div class="panel-title">@lang('cms::core.label.update.changed_files')</div>
</div>
<ul class="list-group">
	@foreach ($files['diff_files'] as $row)
		<li class="list-group-item" data-path="{{ $row['path'] }}">
			{!! HTML::link($row['url']) !!}
			{!! Form::button('diff', [
				'class' => 'btn btn-default btn-xs pull-right show-diff', 'data-icon' => 'code-fork'
			]) !!}
		</li>
	@endforeach
</ul>
@endif