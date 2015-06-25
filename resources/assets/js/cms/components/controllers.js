CMS.controllers = {
	_controllers: [],
	add: function (rout, callback) {
		if (typeof(callback) != 'function')
			return this;

		if (typeof(rout) == 'object')
			for (var i = 0; i < rout.length; i++)
				this._controllers.push([rout[i], callback]);
		else if (typeof(rout) == 'string')
			this._controllers.push([rout, callback]);

		return this;
	},
	call: function () {
		$('body').trigger('controller.call.before');

		var body_id = $('body:first').attr('id');
		for (var i = 0; i < this._controllers.length; i++)
			if (body_id == 'body.' + this._controllers[i][0])
				this._controllers[i][1](this._controllers[i][0]);

		$('body').trigger('controller.call.after');
	}
}