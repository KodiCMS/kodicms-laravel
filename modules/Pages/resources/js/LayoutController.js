cms.init.add(['layout_edit', 'layout_add'], function () {
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		$('.panel').setHeightFor('#textarea_contentDiv', {
			contentHeight: true,
			updateOnResize: true,
			offset: 30,
			minHeight: 300,
			onCalculate: function(a, h) {
				cms.filters.exec('textarea_content', 'changeHeight', h);
			},
			onResize: function(a, h) {
				cms.filters.exec('textarea_content', 'changeHeight', h);
			}
		});
	});
	
	cms.filters.switchOn('textarea_content', DEFAULT_CODE_EDITOR, $('#textarea_content').data());
});

cms.init.add('layout_index', function () {
	$('body').on('post:api-layout.rebuild', function(e, response) {
		if(!response) return;

		for(i in response) {
			$('.layout-block-list', '#layout_' + i).text((response[i] instanceof Array) ? response[i].sort().join (', ') : '')
		}
	});
});