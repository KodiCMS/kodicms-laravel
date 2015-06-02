var Dashboard = {
	widgets: {
		gridster: null,
		init: function() {
			this.gridster = $(".gridster ul").gridster({
				widget_base_dimensions: [150, 100],
				widget_margins: [5, 5],
				autogrow_cols: true,
				resize: {
					enabled: true,
					start: function (e, ui, $widget) {
						var $cont = $widget.find('.dashboard-widget');
						$cont.trigger('resize_start', [this, ui, $cont.width(), $cont.height()])
							.fadeTo(100, .5);
					},
					stop: function (e, ui, $widget) {
						Dashboard.widgets.save_order();
						var $cont = $widget.find('.dashboard-widget');

						$cont
							.fadeTo(100, 1)
							.trigger('resize_stop', [this, ui, $cont.width(), $cont.height()]);
					}
				},
				draggable: {
					start: function (e, ui, $widget) {},
					drag: function (e, ui, $widget) {},
					stop: function (e, ui, $widget) {
						Dashboard.widgets.save_order();
						$('.gridster ul .preview-holder').remove();
					}
				},
				serialize_params: function($w, wgd) {
					return {
						col: wgd.col,
						row: wgd.row,
						sizex: wgd.size_x,
						sizey: wgd.size_y,
						'max-sizex': wgd.max_size_x,
						'max-sizey': wgd.max_size_y,
						'min-sizex': wgd.min_size_x,
						'min-sizey': wgd.min_size_y,
						widget_id: $w.data('widget_id')
					};
				}
			}).data('gridster');

			$('input[name="draggable"]').on('change', $.proxy(function(e) {
				if($(e.target).is(':checked')) {
					this.gridster.enable().enable_resize();
					$('.remove_widget').show();
				} else {
					this.gridster.disable().disable_resize();
					$('.remove_widget').hide();
				}
			}, this)).change();

			$('.dashboard-widget').each(function() {
				var $cont = $(this);
				$cont.trigger('widget_init', [$cont.width(), $cont.height()]);
			});
		},
		add: function(html, id, size) {
			try {
				var $widget = this.gridster.add_widget.apply(this.gridster, [$('<li />').append(html), size.x, size.y, false, false, size.max_size, size.min_size]);

				var $cont = $widget.find('.dashboard-widget');
				$widget.data('widget_id', id);
				$cont.trigger('widget_init', [$cont.width(), $cont.height()]);
			} catch (e) {
				return;
			}

			Popup.close();
			Dashboard.widgets.save_order();
		},
		remove: function(btn) {
			var $widget = btn.closest('li');
			Api.delete('/api.dashboard.widget', {
				id: $widget.data('widget_id')
			}, function(response) {
				var $cont = $widget.find('.dashboard-widget');
				$cont.trigger('widget_destroy');

				Dashboard.widgets.gridster.remove_widget($widget, function() {
					Dashboard.widgets.save_order();
				});
			});
		},
		save_order: function(array) {
			UserMeta.add('dashboard', this.gridster.serialize());
		}
	}
};

CMS.controllers.add('dashboard.get.index', function () {
	Dashboard.widgets.init();

	$(window).resize(Popup.resize);

	$('#add-widget').on('click', function(e) {
		e.preventDefault();
	});

	$('body').on('click', '.popup-btn', function() {
		var widget_type = $(this).data('type');
		Api.put('/api.dashboard.widget', {
			widget_type: widget_type
		}, function(response) {
			if(typeof response.media == 'object') {
				for (i in response.media) {
					getScript(response.media[i]);
				}
			}

			setTimeout(function() {
				Dashboard.widgets.add($(response.content), response.id, response.size);
			}, 500);
		});
	});

	$('body').on('click', '.dashboard-widget .remove_widget', function(e) {
		var $self = $(this);
		Dashboard.widgets.remove($self);
		e.preventDefault();
	});

	$('body').on('click', '.dashboard-widget .settings', function(e) {
		var $cont = $(this).closest('.dashboard-widget');

		get_widget_settings($cont.data('id'));
		e.preventDefault();
	});

	$('body').on('post::api.dashboard.widget', function(e, response) {
		var $cont = $('.dashboard-widget[data-id="' + response.widgetId + '"]');
		$cont.replaceWith(response.content);

		if(response.updateSettingsPage)
			get_widget_settings(response.widgetId);
	});
});

function get_widget_settings(widget_id) {
	Api.get('/api.dashboard.widget', {id: widget_id}, function(response) {
		Popup.openHTML(response.content);
	});
}

function getScript(url) {
	if($('script[src="' + url + '"]').length > 0) return;

	var script = document.createElement('script');
	script.type = "text/javascript";
	script.src = url;

	script.onreadystatechange = function () {
		if (script.readyState === "loaded" || script.readyState === "complete") {
			script.onreadystatechange = null;
		}
	};

	document.getElementsByTagName("head")[0].appendChild(script);
}