CMS.controllers.add('plugin.get.index', function () {
	var Plugin = Backbone.Model.extend({
		defaults: {
			title: '',
			description: '',
			version: '',
			icon: '',
			settings: false,
			isActivated: false
		},

		toggleStatus: function (remove_data) {
			if (!remove_data) remove_data = false;
			this.save({
				isActivated: !this.get("isActivated"),
				remove_data: remove_data
			});
		},

		clear: function () {
			this.destroy();
		}
	});

	var PluginsCollection = Backbone.Collection.extend({
		url: '/api.plugins',

		model: Plugin,

		parse: function (response) {
			return response.content;
		},

		activated: function () {
			return this.filter(function (plugin) {
				return plugin.get('status');
			});
		},

		comparator: function (a) {
			return !a.get('isActivated');
		}
	});

	var PluginViews = Backbone.View.extend({
		tagName: 'tr',

		template: _.template($('#plugin-item').html()),

		events: {
			"click .change-status": "toggleStatus"
		},

		initialize: function () {
			this.model.on('change', this.render, this);
			this.model.on('destroy', this.remove, this);
		},

		toggleStatus: function () {
			remove_data = false;
			if (this.model.get('isActivated') && confirm(__('Remove database data')))
				remove_data = true;

			this.model.toggleStatus(remove_data);
		},

		render: function () {
			this.$el.toggleClass('success', this.model.get('isActivated'));
			this.$el.toggleClass('danger', !this.model.get('isInstallable'));

			this.$el.html(this.template(this.model.toJSON()));

			var button = this.$el.find('button');

			if (this.model.get('isActivated')) {
				button.addClass('btn-danger');
				button.html('<span class="fa fa-power-off" />');
			} else {
				button.html('<span class="fa fa-play-circle" />');
			}

			CMS.loader.hide();
			CMS.ui.init('icon');
			return this;
		},

		// Remove the item, destroy the model.
		clear: function () {
			this.model.clear();
		}
	});

	var PluginsViews = Backbone.View.extend({

		el: $("#pluginsMap tbody"),

		initialize: function () {
			var $self = this;
			this.collection = new PluginsCollection();
			this.collection.fetch({
				success: function () {
					$self.render();
				}
			});
		},

		render: function () {
			this.clear();

			this.collection.each(function (plugin) {
				this.addPlugin(plugin);
			}, this);

			CMS.ui.init('icon');
		},

		clear: function () {
			this.$el.empty();
		},

		addPlugin: function (plugin) {
			var view = new PluginViews({model: plugin});
			this.$el.append(view.render().el);
		}
	});

	CMS.loader.show('#pluginsMap');
	new PluginsViews();
});
