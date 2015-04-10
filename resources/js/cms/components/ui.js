CMS.ui = {
	_elements:[],
	add:function (module, callback) {
		if (typeof(callback) != 'function')
			return this;

		CMS.ui._elements.push([module, callback]);
		return this;
	},
	init:function (module) {
		$('body').trigger('ui.init.before');
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
		$('body').trigger('ui.init.after');
	}
};