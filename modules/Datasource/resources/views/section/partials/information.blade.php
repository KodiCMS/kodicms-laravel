<div class="panel-heading" data-icon="info">
	<span class="panel-title">Information</span>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">Title</label>
		<div class="col-md-9">
			{!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="description">Description</label>
		<div class="col-md-9">
			{!! Form::textarea('description', null, [
					'class' => 'form-control', 'id' => 'description', 'rows' => 3
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-9 col-md-offset-3">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('show_in_root_menu', 1, null, ['id' => 'show_in_root_menu']) !!} Show in root menu
				</label>
			</div>
		</div>
	</div>
</div>