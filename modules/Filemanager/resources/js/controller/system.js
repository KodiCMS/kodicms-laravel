cms.init.add('system_information', function () {
	function calculateEditorHeight() {
		var conentH = cms.calculateContentHeight();
		var h = 130;
		
		return conentH - h;
	}
	
	$('#phpinfo').height(calculateEditorHeight());
	$(window).resize(function() {
		$('#phpinfo').height(calculateEditorHeight());
	});
});