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

CMS.controllers.add('system.settings', function () {
	$('#ace-select').on('change', function() {
		change_ace_theme($(this).val());
	});

	CMS.filters.switchOn('highlight_content', 'ace', $('#textarea_content').data());

	function change_ace_theme(theme) {
		var editor = ace.edit('highlight_contentDiv');
		editor.setTheme("ace/theme/" + theme);
	}
});