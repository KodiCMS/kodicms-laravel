var UserMeta = {
	_url: '/api.user.meta',
	get: function(key, callback) {
		Api.get(this._url, {key: key}, callback);
	},
	add: function(key, data, callback) {
		Api.post(this._url, {key: key, value: data}, callback);
	},
	update: function(key, data, callback) {
		Api.put(this._url, {key: key, value: data}, callback);
	},
	'delete': function(key, callback) {
		Api.delete(this._url, {key: key, value: data}, callback);
	}
}