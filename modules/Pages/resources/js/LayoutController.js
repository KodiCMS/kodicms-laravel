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

CMS.controllers.add('layout.get.list', function () {
	$('body').on('post:api-layout.rebuild', function(e, response) {
		if(!response) return;

		for(i in response) {
			$('.layout-block-list', '#layout_' + i).text((response[i] instanceof Array) ? response[i].sort().join (', ') : '')
		}
	});
});