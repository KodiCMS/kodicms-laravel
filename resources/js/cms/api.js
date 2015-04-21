var Api = {
	_response: null,
	get: function(uri, data, callback, async) {
		var request = this.request('GET', uri, data, callback, async);

		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	post: function(uri, data, callback, async) {
		var request = this.request('POST', uri, data, callback, async);

		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	put: function(uri, data, callback, async) {
		var request = this.request('PUT', uri, data, callback, async);

		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	'delete': function(uri, data, callback, async) {
		var request = this.request('DELETE', uri, data, callback, async);

		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	request: function(method, uri, data, callback, async) {
		url = uri;

		var obj = new Object();

		$.ajaxSetup({
			contentType : 'application/json'
		});

		if(data instanceof jQuery) {
			data = Api.serializeObject(data);
		}

		if(typeof(data) == 'object' && method != 'GET') {
			data = JSON.stringify(data);
		}

		return $.ajax({
			type: method,
			url: url,
			data: data,
			dataType: 'json',
			async: async !== false
		})
			.done(function(response) {
				this._response = response;

				if(response.code != 200) {
					return Api.exception(response, callback);
				}

				var $event = method + url.replace(SITE_URL, ":").replace(/\//g, ':');
				window.top.$('body').trigger($event.toLowerCase(), [this._response.content]);

				if(typeof(callback) == 'function') callback(this._response);
			})
			.fail(function() {
				if(typeof(callback) == 'function') callback();
			});
	},
	serializeObject: function(form) {
		var self = form,
			json = {},
			push_counters = {},
			patterns = {
				"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
				"key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
				"push":     /^$/,
				"fixed":    /^\d+$/,
				"named":    /^[a-zA-Z0-9_]+$/
			};

		var build = function(base, key, value){
			base[key] = value;
			return base;
		};

		var push_counter = function(key){
			if(push_counters[key] === undefined){
				push_counters[key] = 0;
			}
			return push_counters[key]++;
		};

		$.each(form.serializeArray(), function(){
			// skip invalid keys
			if(!patterns.validate.test(this.name)){
				return;
			}
			var k,
				keys = this.name.match(patterns.key),
				merge = this.value,
				reverse_key = this.name;

			while((k = keys.pop()) !== undefined){
				// adjust reverse_key
				reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
				// push
				if(k.match(patterns.push)){
					merge = build([], push_counter(reverse_key), merge);
				}
				// fixed
				else if(k.match(patterns.fixed)){
					merge = build([], k, merge);
				}
				// named
				else if(k.match(patterns.named)){
					merge = build({}, k, merge);
				}
			}
			json = $.extend(true, json, merge);
		});
		return json;
	},
	exception: function(response, callback) {
		if(typeof(callback) == 'function')
			callback(response);

		switch (response.code) {
			case 220: // Page not found

				break;
			case 130: // Unknown
			case 140: // Token
			case 120: // Permissions
				noty({text: response.message, type: 'error', icon: 'fa fa-exclamation-triangle'});
				break;
			case 120: // Validation

				break;
			case 110: // Missing param

				break;
			case 301: // Redirect
			case 302: // Redirect
				window.location.href = response.targetUrl;
				break;

		}
	},
	response: function() {
		return this._response;
	}
};