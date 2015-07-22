<?php
	if($controllerAction == 'getCreate') {
		$contrinueButtonTitle = trans('cms::core.button.create');
		$commitButtonTitle = trans('cms::core.button.create_close');
	} else {
		$contrinueButtonTitle = trans('cms::core.button.update');
		$commitButtonTitle = trans('cms::core.button.update_close');
	}
?>

{!! Form::button(trans($contrinueButtonTitle), [
	'type' => 'submit',
	'class' => 'btn btn-success btn-save btn-lg btn-labeled',
	'data-icon' => 'retweet',
	'name' => 'continue',
	'data-hotkeys' => 'ctrl+s'
]) !!}
&nbsp;&nbsp;
{!! Form::button(trans($commitButtonTitle), [
	'type' => 'submit',
	'class' => 'btn btn-save-close btn-default hidden-xs btn-labeled',
	'data-icon' => 'check',
	'name' => 'commit',
	'data-hotkeys' => 'ctrl+shift+s'
]) !!}
&nbsp;&nbsp;&nbsp;&nbsp;

@if(isset($route))
<?php list($route, $params) = is_array($route) ? $route : [$route, []]; ?>

{!! link_to_route($route, UI::hidden(trans('cms::core.button.cancel')), $params, [
	'data-icon' => 'ban',
	'class' => 'btn btn-close btn-outline'
]) !!}
@else
{!! link_to(null, UI::hidden(trans('cms::core.button.cancel')), [
	'data-icon' => 'ban',
	'class' => 'btn btn-close btn-outline'
]) !!}
@endif