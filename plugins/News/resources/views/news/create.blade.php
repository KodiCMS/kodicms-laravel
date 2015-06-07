{!! Form::model($news, [
'route' => ['backend.news.create.post', $news],
'class' => 'form-horizontal panel'
]) !!}

<div class="panel-body">
	{!! $news->renderField('title') !!}

	{!! $news->renderField('slug') !!}
</div>
<hr />
<div class="panel-body">
	<h3>Description</h3>
	{!! Form::textarea('content[description]', null, ['id' => 'content_description', 'data-height' => 200]) !!}

	<h3>Content</h3>
	{!! Form::textarea('content[content]', null, ['id' => 'content_text', 'data-height' => 400]) !!}
</div>
<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.news.list'])
</div>
{!! Form::close() !!}

<script>
$(function() {
	CMS.filters.switchOn('content_description', DEFAULT_HTML_EDITOR);
	CMS.filters.switchOn('content_text', DEFAULT_HTML_EDITOR);
});
</script>