<div class="panel">
    <div class="panel-heading">
        @if (acl_check('news.add'))
            {!! link_to_route('backend.news.create', trans('butschster:news::core.button.create'), [], [
                'class' => 'btn btn-primary', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
            ]) !!}
        @endif
    </div>

    @if(count($newsList) > 0)
        <table class="table table-primary table-striped table-hover">
            <colgroup>
                <col width="150px"/>
                <col/>
                <col width="100px"/>
            </colgroup>
            <thead>
            <tr>
                <th>@lang('butschster:news::core.field.title')</th>
                <th>@lang('butschster:news::core.field.slug')</th>
                <th>@lang('butschster:news::core.field.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($newsList as $news)
                <tr class="item">
                    <td class="name">
                        @if (acl_check('news.edit'))
                            {!! link_to_route('backend.news.edit', $news->title, [$news], [
                                'data-icon' => 'unlock'
                            ]) !!}
                        @else
                            <span data-icon="lock">{{ $news->title }}</span>
                        @endif
                    </td>
                    <td class="description">
                        {{ $news->slug }}
                    </td>
                    <td class="actions text-center">
                        @if (acl_check('news.delete'))
                            {!! link_to_route('backend.news.delete', '', [$news], [
                                'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
                            ]) !!}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="panel-body">
            <h3>@lang('butschster:news::core.messages.empty')</h3>
        </div>
    @endif
</div>

{!! $newsList->render() !!}