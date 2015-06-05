CMS.filters = {
	filters: [],
	switchedOn: {},
	editors: {},
	add: function (name, switchOn_handler, switchOff_handler, exec_handler) {
		if (switchOn_handler == undefined || switchOff_handler == undefined) {
			CMS.messages.error('System try to add filter without required callbacks.', name, switchOn_handler, switchOff_handler);
			return;
		}
		this.filters.push([ name, switchOn_handler, switchOff_handler, exec_handler ]);
	},
	switchOn: function (textarea_id, filter, params) {
		$('#' + textarea_id).css('display', 'block');
		if (this.filters.length > 0) {
			var old_filter = this.get(textarea_id);
			var new_filter = null;

			for (var i = 0; i < this.filters.length; i++) {
				if (this.filters[i][0] == filter) {
					new_filter = this.filters[i];
					break;
				}
			}
			if(old_filter !== new_filter) {
				this.switchOff(textarea_id);
			}
			try {
				this.switchedOn[textarea_id] = new_filter;
				this.editors[textarea_id] = new_filter[1](textarea_id, params);
				$('#' + textarea_id).trigger('filter:switch:on', this.editors[textarea_id]);
			}
			catch (e) {}
		}
	},
	switchOff: function (textarea_id) {
		var filter = this.get(textarea_id);
		try {
			if ( filter && typeof(filter[2]) == 'function' ) {
				filter[2](this.editors[textarea_id], textarea_id);
			}
			this.switchedOn[textarea_id] = null;
			$('#' + textarea_id).trigger('filter:switch:off');
		}
		catch (e) {}
	},
	get: function(textarea_id) {
		for (var key in this.switchedOn) {
			if ( key == textarea_id )
				return this.switchedOn[key];
		}
		return null;
	},
	exec: function(textarea_id, command, data) {
		var filter = this.get(textarea_id);
		if( filter && typeof(filter[3]) == 'function' )
			return filter[3](this.editors[textarea_id], command, textarea_id, data);
		return false;
	}
}