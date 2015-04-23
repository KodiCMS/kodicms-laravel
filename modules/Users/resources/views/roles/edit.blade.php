{!! Form::model($role, [
'route' => ['backend.role.edit.post', $role],
'class' => 'form-horizontal panel'
]) !!}

<div class="panel-heading">
	<span class="panel-title">@lang('users::role.tab.general')</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">@lang('users::role.field.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', NULL, [
				'class' => 'form-control slugify', 'id' => 'name', 'data-separator' => '_'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="description">@lang('users::role.field.description')</label>
		<div class="col-md-9">
			{!! Form::textarea('description', NULL, [
				'class' => 'form-control', 'id' => 'description', 'rows' => 2
			]) !!}
		</div>
	</div>
</div>

@if($role->id > 2)
@include('users::roles.permissions', [
	'permissions' => $permissions,
	'selected' => $selectedPermissions
])
@endif

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.role.list'])
</div>
{!! Form::close() !!}