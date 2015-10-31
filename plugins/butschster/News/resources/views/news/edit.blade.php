{!! Form::model($news, [
'route' => ['backend.news.edit.post', $news],
'class' => 'form-horizontal panel'
]) !!}

<div class="panel-body">
    {!! $news->renderField('title') !!}

    {!! $news->renderField('slug') !!}
</div>
<hr/>
<div class="panel-body">
    {!! $news->renderField('content::description') !!}
    {!! $news->renderField('content::content') !!}
</div>
<div class="form-actions panel-footer">
    @include('cms::app.partials.actionButtons', ['route' => 'backend.news.list'])
</div>
{!! Form::close() !!}

<script>
    $(function () {
        CMS.filters.switchOn('content_description', DEFAULT_HTML_EDITOR);
        CMS.filters.switchOn('content_text', DEFAULT_HTML_EDITOR);
    });
</script>