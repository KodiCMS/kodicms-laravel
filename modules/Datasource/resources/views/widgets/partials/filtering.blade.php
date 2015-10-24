<script>
	var add_filter = function() {
		var filter = $('#sample_filter .filter').clone().appendTo(filters_container);
		$('.select2-container', filter).remove()
		//$("select", filter).select2();
		filter.on('click', '.remove_filter', function() {
			filter.remove();
			return false;
		});

		return filter;
	}

	var set_condition = function (filters_container, data) {
		$('.select2-container', filters_container).remove()
		//$("select", filters_container).select2();

		for (key in data) {
			if (key == 'invert' && data[key] == 1) {
				$('input[name="settings[filters][' + key + '][]"]', filters_container).check()
				continue;
			}
			$('input[name="settings[filters][' + key + '][]"]', filters_container).val(data[key]);
			$('select[name="settings[filters][' + key + '][]"]', filters_container).val(data[key]).trigger("change");

			if (key == 'field') {
				$(filters_container).find('.field-title').text(data[key]).show();
			}
		}
	}

	$(function() {
		var filters_container = $('#filters_container');
		$('#add_filter').on('click', function() {
			var filter = add_filter();

			$('input[name="settings[filters][field][]"]', filter).on('keyup', function() {
				var field_title = filter.find('.field-title');

				if(!field_title.text()) field_title.hide();
				else field_title.show();

				field_title.text($(this).val());
			});

			return false;
		});
	})
</script>

<div class="panel-heading" data-icon="filter">
	<span class="panel-title">@lang('datasource::widgets.list.filtering.title')</span>
</div>
<div class="">
	<fieldset disabled id="sample_filter" class="hide">
		<div class="filter well well-sm no-margin-b">
			<h4 class="field-title pull-left"></h4>

			{!! Form::button('', [
				'data-icon' => 'trash-o', 'class' => 'btn btn-danger btn-xs remove_filter pull-right'
			]) !!}

			<div class="clearfix"></div>
			<table style="width: 100%">
				<colgroup>
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
				<tr>
					<td></td>
					<td>
						<div class="row">
							<div class="col-xs-2">

							</div>
							<div class="col-xs-3">
								@lang('datasource::widgets.list.filtering.condition')
								<label class="text-right" style="margin-left: 30px;">
									{!! Form::checkbox('settings[filters][invert][]', 1, false) !!}
									@lang('datasource::widgets.list.filtering.invert_condition')
								</label>
							</div>
							<div class="col-xs-3">
								@lang('datasource::widgets.list.filtering.source')
							</div>
							<div class="col-xs-4">

							</div>
						</div>
					</td>
				</tr>
					<tr>
						<td>@lang('datasource::widgets.list.filtering.where')</td>
						<td>
							<div class="row">
								<div class="col-xs-3 col-sm-2">
									{!! Form::text('settings[filters][field][]', null, [
										'class' => 'form-control', 'placeholder' => trans('datasource::widgets.list.filtering.field')
									]) !!}
								</div>
								<div class="col-xs-2 col-sm-3">
									{!! Form::select('settings[filters][condition][]', [
										\KodiCMS\Datasource\Model\Document::COND_EQ => '==',
										\KodiCMS\Datasource\Model\Document::COND_GT => '>',
										\KodiCMS\Datasource\Model\Document::COND_LT => '<',
										\KodiCMS\Datasource\Model\Document::COND_GTEQ => '>=',
										\KodiCMS\Datasource\Model\Document::COND_LTEQ => '<=',
										\KodiCMS\Datasource\Model\Document::COND_BTW => 'Between',
										\KodiCMS\Datasource\Model\Document::COND_CONTAINS => 'Contains',
										\KodiCMS\Datasource\Model\Document::COND_LIKE => 'Like'
									], null, ['class' => 'no-script form-control']) !!}
								</div>
								<div class="col-xs-3">
									{!! Form::select('settings[filters][type][]', [
										\KodiCMS\Datasource\Model\Document::FILTER_VALUE_PLAIN => 'Plain',
										\KodiCMS\Datasource\Model\Document::FILTER_VALUE_GET => '$_GET',
										\KodiCMS\Datasource\Model\Document::FILTER_VALUE_POST => '$_POST',
										\KodiCMS\Datasource\Model\Document::FILTER_VALUE_BEHAVIOR => 'Page behavior',
									], null, ['class' => 'no-script form-control']) !!}
								</div>
								<div class="col-xs-4">
									{!! Form::text('settings[filters][value][]', null, [
										'class' => 'form-control', 'placeholder' => trans('datasource::widgets.list.filtering.condition_value')
									]) !!}
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td></td>
						<td>
							{!! Form::text('settings[filters][params][]', null, [
								'class' => 'form-control', 'size' => 50, 'placeholder' => trans('datasource::widgets.list.filtering.query_string')
							]) !!}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</fieldset>

	<div id="filters_container"></div>

	<?php
	if(!empty($widget->filters))
	{
		echo '<script> $(function(){';
		foreach($widget->filters as $filter)
			echo 'set_condition(add_filter(), ' . json_encode($filter) . '); ';
		echo '});</script>';
	}
	?>
	<div class="panel-body">
		{!! Form::button(trans('datasource::widgets.list.filtering.button_add'), ['data-con' => 'plus', 'id' => 'add_filter', 'class' => 'btn btn-default']) !!}
	</div>
</div>