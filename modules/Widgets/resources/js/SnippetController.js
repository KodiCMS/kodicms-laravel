CMS.controllers.add(['snippet.get.edit', 'snippet.get.create'], function () {
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		$('.panel').setHeightFor('#textarea_contentDiv', {
			contentHeight: true,
			updateOnResize: true,
			offset: 30,
			minHeight: 300,
			onCalculate: function(a, h) {
				CMS.filters.exec('textarea_content', 'changeHeight', h);
			},
			onResize: function(a, h) {
				CMS.filters.exec('textarea_content', 'changeHeight', h);
			}
		});
	});

	set_editor(FILE.settings.editor);
	
	$('select[name="editor"]').on('change', function() {
		set_editor($(this).val());
	});

	function set_editor($editor) {
		CMS.filters.switchOn('textarea_content', $editor, $('#textarea_content').data());
	}
});

