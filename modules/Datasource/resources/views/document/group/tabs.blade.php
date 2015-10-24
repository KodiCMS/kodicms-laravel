
<?php $active = true; ?>
<ul class="nav nav-tabs tabs-generated">
	@foreach ($fields as $field)
		<li class="@if($active) active @endif">
			<a href="#document-tab-{{ $field->getId() }}" data-toggle="tab">
				<span>{{ $field->getName() }}</span>
			</a>
		</li>
	<?php $active = false; ?>
	@endforeach
</ul>
<?php $active = true; ?>
<div class="tab-content no-padding-t no-padding-b">
	@foreach ($fields as $field)
	<div class="tab-pane @if($active) active @endif" id="document-tab-{{ $field->getId() }}">
		<div class="panel-body">
			{!! $field->renderDocumentTemplate($document) !!}
		</div>
	</div>
	<?php $active = false; ?>
	@endforeach
</div>
<hr />