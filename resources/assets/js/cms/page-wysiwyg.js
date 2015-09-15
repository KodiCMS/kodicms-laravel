CMS.messages = {
	show: function(msg, type, icon) {}
}

CMS.ui.add('app', function () {
	var block;
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

	$('body').on('click', '.page-block-add-widget', function () {
		block = $(this).closest('.page-block-placeholder').data('name');
	});

	$('body').on('click', '.popup-widget-item', function () {
		var widget_id = $(this).data('id');
		Api.put('/api.widget', {
			widget_id: widget_id,
			page_id: PAGE.id,
			block: block
		}, function (response) {
			Popup.close();
			window.location.reload();
			return;
		});
	});
}).add('icon', function () {
	$('*[data-icon]').add('*[data-icon-prepend]').each(function () {
		var cls = $(this).data('icon');
		if ($(this).hasClass('btn-labeled')) cls += ' btn-label icon';

		$(this).html('<i class="fa fa-' + cls + '"></i> ' + $(this).html());
		$(this).removeAttr('data-icon-prepend').removeAttr('data-icon');
	});
});

$(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	CMS.ui.init();
});