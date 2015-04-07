CMS.controllers.add('system.about', function () {
	function calculateEditorHeight() {
		var conentH = CMS.calculateContentHeight();
		var h = 90;
		
		return conentH - h;
	}
	
	$('#phpinfo').height(calculateEditorHeight());
	$(window).resize(function() {
		$('#phpinfo').height(calculateEditorHeight());
	});
});