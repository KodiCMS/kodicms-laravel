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
	'class' => 'btn btn-success btn-save btn-lg',
	'data-icon' => 'retweet',
	'name' => 'continue',
	'data-hotkeys' => 'ctrl+s'
]) !!}
&nbsp;&nbsp;
{!! Form::button(trans($commitButtonTitle), [
	'type' => 'submit',
	'class' => 'btn btn-save-close btn-default hidden-xs',
	'data-icon' => 'check',
	'name' => 'commit',
	'data-hotkeys' => 'ctrl+shift+s'
]) !!}
&nbsp;&nbsp;&nbsp;&nbsp;

{!! link_to_route(isset($route) ? $route : NULL, UI::hidden(trans('cms::core.button.cancel')), [], [
	'data-icon' => 'ban',
	'class' => 'btn btn-close btn-sm btn-outline'
]) !!}