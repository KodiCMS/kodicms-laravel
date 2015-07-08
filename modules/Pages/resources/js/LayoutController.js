CMS.controllers.add(['layout.get.edit', 'layout.get.create'], function () {
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
	
	CMS.filters.switchOn('textarea_content', DEFAULT_CODE_EDITOR, $('#textarea_content').data());
});

CMS.controllers.add('layout.get.index', function () {
	$('body').on('get:api.layout.rebuild', function(e, response) {
		for (layout in response.content) {
			$('.layout-block-list', '#layout_' + layout).html(response.content[layout]);
		}
	});
});