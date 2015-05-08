<script type="text/javascript">
	$(function() {
		$('body').on('change', '#snippet-select', function() {
			var $value = $(this).val();
			if($value == 0)
				$('#EditTemplateButton').hide();
			else
				$('#EditTemplateButton')
					.show()
					.css({display: 'inline-block'})
					.attr('href', BASE_URL + '/snippet/edit/' + $value);
		}).change();

		$('body').on('post:backend:api.snippet', update_snippets_list);
		$('body').on('put:backend:api.snippet', update_snippets_list);
	});

	function update_snippets_list(e, response) {
		var select = $('#snippet-select');

		select
			.append($('<option>', {value: response.name, text: response.name}))
			.select2('val', response.name)
			.change();
	}
</script>

<?php
if (empty($templates))
{
	$templates = (new \KodiCMS\Widgets\Model\SnippetCollection())->getHTMLSelectChoices();
}

if (empty($template)) $template = null;
if (empty($default)) $default = null;
if (empty($selectName)) $selectName = 'template';

$hidden = empty($template) ? 'hidden' : '';
?>

@if (!empty($header))
<div class="panel-heading @if (!empty($spoiler)) panel-toggler @endif" @if (!empty($spoiler)) data-target-spoiler=".{{ $spoiler }}" @endif >
	<span class="panel-title" data-icon="desktop">{{ $header }}</h4>
</div>
@endif
<div class="panel-body @if (!empty($spoiler)) panel-spoiler {{ $spoiler}} @endif">
	<div class="form-group form-inline">
		<label class="control-label col-sm-2" data-icon="file-code-o">@lang('widgets::snippet.title.list')</label>
		<div class="col-md-9">
			<div class="input-group">
				{!! Form::select($selectName, $templates, $template, [
					'id' => 'snippet-select', 'class' => 'form-control', 'style' => 'width: 250px'
				]) !!}

				<div class="btn-group">
					@if (acl_check('snippet.edit'))
					{!! link_to_route('backend.snippet.edit', UI::hidden(trans('widgets::snippet.button.edit'), ['md', 'sm', 'xs']), [$template], [
						'data-icon' => 'edit', 'class' => 'btn popup fancybox.iframe btn-primary '.$hidden,
						'id' => 'EditTemplateButton'
					]) !!}
					@endif

					@if (acl_check('snippet.add'))
					{!! link_to_route('backend.snippet.edit', UI::hidden(trans('widgets::snippet.button.add'), ['md', 'sm', 'xs']), [], [
						'data-icon' => 'plus', 'class' => 'btn popup fancybox.iframe btn-success',
						'id' => 'AddTemplateButton'
					]) !!}
					@endif

					{!! $default !!}
				</div>
			</div>
		</div>
	</div>
</div>