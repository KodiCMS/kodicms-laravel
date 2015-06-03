<div class="panel-heading">
	<span class="panel-title">@lang('users::role.tab.permissions')</span>
</div>
<div class="panel-body tabbable no-padding" id="permissions-list">
	@foreach($permissions as $module => $actions)
	<div class="panel-heading">
		<span class="panel-title">{{ $module }}</span>
	</div>
	<table class="table table-hover">
		<colgroup>
			<col />
			<col width="100px" />
		</colgroup>
		<thead class="highlight">
		<tr>
			<th></th>
			<th>
				<a href="#" class="check_all editable editable-click">
					@lang('users::role.button.select_all_permissions')
				</a>
			</th>
		</tr>
		</thead>
		<tbody>
		@foreach($actions as $action => $title)
		<tr>
			<th>{!! Form::label('permission-'.$action, $title) !!}</th>
			<td>
				{!! Form::checkbox("permissions[{$action}]", 1, in_array($action, $selected), [
					'id' => "permission-{$action}", 'class' => 'form-switcher', 'data-size' => 'mini',
					'data-on' => trans('users::role.button.permissions.grant'),
					'data-off' => trans('users::role.button.permissions.denied'),
					'data-onstyle' => 'success', 'data-offstyle' => 'danger',
					'data-width' => '80'
				]) !!}
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	@endforeach
</div>