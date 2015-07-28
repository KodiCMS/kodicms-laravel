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
		<ul class="nav nav-pills nav-stacked">
		@foreach ($sections as $section)
			<li @if(!is_null($currentSection) and $currentSection->getId() == $section->getId()) class="active" @endif >
				{!! HTML::link($section->getLink(), $section->name, [
					'data-icon' => $section->getIcon()
				]) !!}
			</li>
		@endforeach
		</ul>
	@endif
	</div>
</div>