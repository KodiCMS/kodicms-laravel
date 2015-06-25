<div {!! HTML::attributes($group->getAttributes()) !!}>
 	<h4>{!! $field->getTitle() !!}</h4>

	{!! $field->render() !!}
</div>

@section('scripts')
@parent
<script>$(function() {CMS.filters.switchOn('{{ $field->getId() }}', DEFAULT_HTML_EDITOR)})</script>
@stop