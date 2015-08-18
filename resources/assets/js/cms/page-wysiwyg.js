CMS.ui.add('app', function () {
	var block;
	var pageId = $('meta[name="page-id"]').data('id');
	var deletedContainer = $('.page-block-placeholder[data-name="-1"]');

	var save = function (callback) {
		var data = {};
		$('.page-block-placeholder').each(function () {
			var $block = $(this);
			var name = $block.data('name');
			data[name] = data[name] || [];
			$block.find('.page-widget-placeholder').each(function () {
				var $widget = $(this);
				var id = $widget.data('id');
				data[name].push(id);
			});
		});

		Api.post('/api.page.widgets.reorder', {data: data, id: pageId}, $.proxy(callback, this));
	};

	$('.page-block-placeholder .sortable').sortable({
		draggable: '.page-widget-placeholder',
		handle: '.drag-handle',
		group: 'widgets',
		onEnd: save
	});

	$(document).on('click', '.page-widget-placeholder .widget-remove', function (e) {
		e.preventDefault();
		var $widget = $(this).closest('.page-widget-placeholder');
		$widget.appendTo(deletedContainer);
		save();
	});

	$('body').on('click', '.page-block-placeholder-buttons .add-widget', function () {
		block = $(this).closest('.page-block-placeholder').data('name');
	});

	$('body').on('click', '.popup-widget-item', function () {
		var widget_id = $(this).data('id');
		Api.put('/api.widget', {
			widget_id: widget_id,
			page_id: pageId,
			block: block
		}, function (response) {
			Popup.close();
			window.location.reload();
			return;
		});
	});
});

$(function () {
	CMS.ui.init();
});