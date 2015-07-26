<div class="navigation">
	@if (!empty($types))
	<div class="compose-btn">
		<div class="btn-group">
			{!! HTML::link('#', trans('datasource::core.button.create'), [
				'class' => 'dropdown-toggle btn btn-primary btn-labeled btn-block',
				'data-icon-append' => 'caret-down',
				'data-toggle' => 'dropdown'
			]) !!}
			<ul class="dropdown-menu">
				@foreach ($types as $type => $object)
				<li>
					{!! HTML::link($object->getLink(), $object->getTitle(), [
						'data-icon' => $object->getIcon()
					]) !!}
				</li>
				@endforeach
			</ul>
		</div>

		<br /><br />
		{!! HTML::link('#', trans('datasource::core.button.create_folder'), [
			'class' => 'btn btn-default btn-xs create-folder-button',
		]) !!}
	</div>
	@endif

	<div class="sections-list">
	@if (!empty($sections))
		<ul class="sections">
		@foreach ($sections as $section)
			<li>
				{!! HTML::link($section['object']->getLink(), $section['object']->name, [
					'data-icon' => $section['type']->getIcon()
				]) !!}
			</li>
		@endforeach
		</ul>
	@endif
	</div>
</div>