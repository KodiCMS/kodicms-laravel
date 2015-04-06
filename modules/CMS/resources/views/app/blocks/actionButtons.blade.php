{!! Form::button(trans('cms::core.button.save'), [
	'type' => 'submit',
	'class' => 'btn btn-success btn-save btn-lg',
	'data-icon' => 'retweet',
	'name' => 'continue',
	'data-hotkeys' => 'ctrl+s'
]) !!}
&nbsp;&nbsp;
{!! Form::button(trans('cms::core.button.save_close'), [
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