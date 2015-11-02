@include('datasource::widgets.partials.section')

@if ($widget->isDatasourceSelected())
<div class="panel-body">
<pre>
@foreach($fields as $field)
{!! HTML::entities(Form::label($field->getName())) !!}
{!! HTML::entities($field->getDefaultFormHTML($widget->getSection()->getEmptyDocument())) !!}
======
@endforeach
</pre>
</div>
@endif