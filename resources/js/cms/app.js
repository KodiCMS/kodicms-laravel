var CMS = {}

CMS.ui = {
	_elements:[],
	add:function (module, callback) {
		if (typeof(callback) != 'function')
			return this;

		CMS.ui._elements.push([module, callback]);
		return this;
	},
	init:function (module) {
		for (var i = 0; i < CMS.ui._elements.length; i++) {
			try {
				if(!module)
					CMS.ui._elements[i][1]();
				else if(_.isArray(module) && _.indexOf(module, CMS.ui._elements[i][0]) != -1 )
					CMS.ui._elements[i][1]();
				else if (module == CMS.ui._elements[i][0])
					CMS.ui._elements[i][1]();
			} catch (e) {}
		}
	}
};

CMS.controllers = {
	_controllers: [],
	add: function (rout, callback) {
		if (typeof(callback) != 'function')
			return this;

		if (typeof(rout) == 'object')
			for (var i = 0; i < rout.length; i++)
				CMS.controllers._controllers.push([rout[i], callback]);
		else if (typeof(rout) == 'string')
			CMS.controllers._controllers.push([rout, callback]);

		CMS.controllers._controllers.reverse();
		return this;
	},
	call: function () {
		var body_id = $('body:first').attr('id');
		for (var i = 0; i < CMS.controllers._controllers.length; i++)
			if (body_id == 'body.' + CMS.controllers._controllers[i][0])
				CMS.controllers._controllers[i][1](CMS.controllers._controllers[i][0]);
	}
}