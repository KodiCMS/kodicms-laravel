<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('pages::widgets.page_list.setting.start_page')</label>
		<div class="col-md-4">
			{!! Form::select('settings[page_id]', $select, $widget->page_id, ['id' => 'select_page_id', 'class' => 'form-control']) !!}
		</div>
	</div>

	<hr class="panel-wide"/>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('include_user_object', 1, $widget->include_users_object == 1) !!}
					@lang('pages::widgets.page_list.setting.include_user_object')
				</label>
			</div>
		</div>
	</div>
</div>