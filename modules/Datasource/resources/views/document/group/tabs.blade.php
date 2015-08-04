<div class="panel-body">
	<ul class="nav nav-tabs">
		@foreach ($fields as $field)
			<li>
				<a href="#document-tab-{{ $field->getId() }}" data-toggle="tab">
					<span class="">{{ $field->getName() }}</span>
				</a>
			</li>
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
</div>