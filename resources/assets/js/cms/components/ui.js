CMS.ui = {

	// 0: name
	// 1: callback
	// 2: priority

	_elements:[],
	add:function (module, callback, priority) {
		if (typeof(callback) != 'function')
			return this;

		CMS.ui._elements.push([module, callback, priority || 0]);
		return this;
	},
	call: function(module) {
		for (var i = 0; i < CMS.ui._elements.length; i++) {
			var elm = CMS.ui._elements[i];
			if(_.isArray(module) && _.indexOf(module, elm[0]) != -1 )
				elm[1]();
			else if (module == elm[0])
				elm[1]();
		}
	},
	init:function (module) {
		$('body').trigger('ui.init.before', [this]);

		CMS.ui._elements = _.sortBy(CMS.ui._elements, 2);

		for (var i = 0; i < CMS.ui._elements.length; i++) {
			var elm = CMS.ui._elements[i];

			try {
				if(!module)
					elm[1]();
				else if(_.isArray(module) && _.indexOf(module, elm[0]) != -1 )
					elm[1]();
				else if (module == elm[0])
					elm[1]();
			} catch (e) {console.log(elm[0], e);}
		}
		$('body').trigger('ui.init.after', [this]);
	}
};