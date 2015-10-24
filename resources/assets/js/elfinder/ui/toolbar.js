/**
 * @class  elFinder toolbar
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindertoolbar = function (fm, opts) {
	this.not('.elfinder-toolbar').each(function () {
		var commands = fm._commands,
			self = $(this).addClass('elfinder-toolbar panel-heading'),
			panels = opts || [],
			l = panels.length,
			i, cmd, panel, button;

		self.prev().length && self.parent().prepend(this);

		while (l--) {
			if (panels[l]) {
				panel = $('<div class="btn-group"/>');
				i = panels[l].length;
				while (i--) {
					if ((cmd = commands[panels[l][i]])) {
						button = 'elfinder' + cmd.options.ui;
						$.fn[button] && panel.prepend($('<div/>')[button](cmd));
					}
				}

				panel.children().length && self.prepend(panel);

			}
		}

		self.children().length && self.show();
	});

	return this;
}