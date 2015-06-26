CMS.hashString = {
	query: null,
	_init: function() {
		this._loadHashParams();

		$(window).on('hashchange', $.proxy(function() {
			this._loadHashParams();
		}, this));
	},
	getParam: function(key) {
		if(_.isNull(this.query)) return null;

		return this.query.get(key);
	},
	setParam: function(key, value) {
		if(_.isArray(value)) {
			for(i in value)
				this.setParam(key + '[]', value[i]);
		} else if(_.isObject(value)) {
			for(i in value)
				this.setParam(key + '[' + i + ']', value[i]);
		} else {
			if (!this.findInParam(key, value))
				this.query.SET(key, value);
		}

		this._updateHash();
	},
	removeParam: function(key, hash) {
		this.query.REMOVE(key, hash);
		this._updateHash();
	},
	findInParam: function (key, value) {
		var param = this.getParam(key.replace(/\[.*?\]/g,""));

		if(_.isNull(param)) return false;

		if(_.isArray(param)) {
			return _.indexOf(param, value) >= 0;
		}

		return param == value;
	},
	_updateHash: function() {
		var string = this.query.toString().substring(1);

		if(string.length > 0)
			window.location.hash = this.query.toString().substring(1);
		else
			history.pushState("", document.title, window.location.pathname + window.location.search);
	},
	_loadHashParams: function() {
		this.query = $.query.parseNew('?' + window.location.hash.substring(1));
	}
}

CMS.ui.add('location.hash', function() {
	CMS.hashString._init();
}, -999);