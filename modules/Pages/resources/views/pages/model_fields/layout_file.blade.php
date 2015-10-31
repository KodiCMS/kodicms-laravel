<?php
$layoutName = $model->getLayout();
$layoutLink = '';

if ((acl_check('layout.edit') or acl_check('layout.view')) and ! empty($layoutName))
{
	$layoutLink = link_to_route('backend.layout.edit', $layoutName, [$layoutName], [
		'class' => 'popup fancybox.iframe'
	]);
}
?>
<div {!! HTML::attributes($group->getAttributes()) !!}>
	{!! $label->render(['class' => $group->labelCol ]) !!}

	<div class="{{ $group->fieldCol }}">
		<div class="well well-sm">
			{!! $field->render() !!}

			<br />
			@if (!empty($layoutName))
			{!! UI::label(trans('pages::core.label.page.current_layout', ['name' => $layoutLink])) !!}
			@else
			{!! UI::label(trans('pages::core.label.page.layout_not_set'), 'danger') !!}
			@endif
		</div>
	</div>
</div>