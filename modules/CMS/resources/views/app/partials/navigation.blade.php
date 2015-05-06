@if($navigation)
<div id="main-menu-inner">
	@event('view.menu', [$navigation])
</div>
@endif