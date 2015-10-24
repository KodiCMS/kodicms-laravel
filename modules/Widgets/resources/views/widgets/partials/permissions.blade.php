{!! Form::button(trans('widgets::core.title.permissions'), [
	'data-icon' => 'users',
	'class' => 'btn btn-sm btn-danger btn-labeled',
	'data-toggle' => 'modal',
 	'data-target' => '#permissionsModal'
]) !!}

<div class="modal fade" id="permissionsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" data-icon="file-o">@lang('widgets::core.title.permissions')</h4>
			</div>
			<div class="modal-body">
				{!! Form::select('settings[roles][]', $usersRoles, $widget->getRoles(), [
					'class' => 'form-control', 'multiple'
				]) !!}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
			</div>
		</div>
	</div>
</div>