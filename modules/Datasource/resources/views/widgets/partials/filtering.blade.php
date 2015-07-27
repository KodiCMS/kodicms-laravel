<script>
	var add_filter = function() {
		var filter = $('#sample_filter .filter').clone().appendTo(filters_container);
		$('.select2-container', filter).remove()
		$("select", filter).select2();
		filter.on('click', '.remove_filter', function() {
			filter.remove();
			return false;
		});

		return filter;
	}

	var set_condition = function (filters_container, data) {
		$('.select2-container', filters_container).remove()
		$("select", filters_container).select2();

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
	<span class="panel-title">Filters</span>
</div>
<div class="panel-body">
	<fieldset disabled id="sample_filter" class="hide">
		<div class="filter well well-sm">
			<div class="clearfix"></div>
			<h4 class="field-title hide pull-left"></h4>

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
					<td>Where field</td>
					<td class="form-inline">
						{!! Form::text('settings[filters][field][]', null, [
							'class' => 'form-control'
						]) !!}
						{!! Form::text('settings[filters][params][]', null, [
							'class' => 'form-control', 'size' => 50, 'placeholder' => 'Field params as Query string'
						]) !!}
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Condition</td>
					<td>
						<div class="row">
							<div class="col-md-2">
								{!! Form::select('settings[filters][condition][]', [
									\KodiCMS\Datasource\Model\Document::COND_EQ => '==',
									\KodiCMS\Datasource\Model\Document::COND_GT => '>',
									\KodiCMS\Datasource\Model\Document::COND_LT => '<',
									\KodiCMS\Datasource\Model\Document::COND_GTEQ => '>=',
									\KodiCMS\Datasource\Model\Document::COND_LTEQ => '<=',
									\KodiCMS\Datasource\Model\Document::COND_BTW => 'Between',
									\KodiCMS\Datasource\Model\Document::COND_CONTAINS => 'Contains',
									\KodiCMS\Datasource\Model\Document::COND_LIKE => 'Like'
								]) !!}
							</div>
							<div class="col-md-5">
								<div class="checkbox">
									<label>
										{!! Form::checkbox('settings[filters][invert][]', 1, false) !!}
										Invert condition
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Conition value</td>
					<td>
						<div class="row">
							<div class="col-md-4">
								{!! Form::select('settings[filters][type][]', [
									\KodiCMS\Datasource\Model\Document::FILTER_VALUE_PLAIN => 'Plain',
									\KodiCMS\Datasource\Model\Document::FILTER_VALUE_GET => '$_GET',
									\KodiCMS\Datasource\Model\Document::FILTER_VALUE_POST => '$_POST',
									\KodiCMS\Datasource\Model\Document::FILTER_VALUE_BEHAVIOR => 'Page behavior',
								]) !!}
							</div>
							<div class="col-md-8">
								{!! Form::text('settings[filters][value][]', null, [
									'class' => 'form-control'
								]) !!}
							</div>
						</div>
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

	{!! Form::button('Add filter', ['data-con' => 'plus', 'id' => 'add_filter', 'class' => 'btn btn-default']) !!}
</div>