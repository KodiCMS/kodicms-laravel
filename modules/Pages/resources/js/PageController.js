var Page = {
	cacheKey: 'expanded_pages',
	_expandedPages: [],
	init: function () {
		this.expandedPages();
		this.loadChildren(1, 0, $('#page-tree-list').find('li'));
		$('#page-tree-list').on('click', '.item-expander', $.proxy(this.onExpand, this));
	},
	expandedPages: function () {
		Api.get('/api.user.meta', {key: this.cacheKey}, $.proxy(function (response) {
			this._expandedPages = _.map(response.content, function (num) {
				return parseInt(num);
			});
		}, this));
	},
	expandedPagesAdd: function (page_id) {
		this._expandedPages.push(page_id);
		Api.post('/api.user.meta', {key: this.cacheKey, value: _.uniq(this._expandedPages)});
	},
	expandedPagesRemove: function (page_id) {
		this._expandedPages = _.filter(this._expandedPages, function (num) {
			return num != parseInt(page_id);
		});
		Api.post('/api.user.meta', {key: this.cacheKey, value: _.uniq(this._expandedPages)});
	},
	loadChildren: function (parent_id, level, $container, $expander) {
		CMS.loader.show('#page-tree');

		Api.get('/api.page.children', {parent_id: parent_id, level: level}, $.proxy(function (response) {
			$container.append(response.content);
			if ($expander) {
				$expander
					.addClass('item-expander-expand')
					.removeClass('fa-plus')
					.addClass('fa-minus');

				if (parent_id > 1) {
					$container.addClass('item-expanded');
					this.expandedPagesAdd(parent_id);
				}
			}

			CMS.loader.hide();
			CMS.ui.init('icon');
		}, this));
	},
	onExpand: function (e) {
		var expander = $(e.target),
			li = expander.closest('li'),
			parent_id = li.data('id');

		if (!li.hasClass('item-expanded')) {
			var level = parseInt(li.parent().data('level'));

			this.loadChildren(parent_id, level, li, expander);
		}
		else {
			if (expander.hasClass('item-expander-expand')) {
				expander
					.removeClass('item-expander-expand')
					.removeClass('fa-minus')
					.addClass('fa-plus');

				li.find('>ul').hide();

				this.expandedPagesRemove(parent_id);
			}
			else {
				expander
					.addClass('item-expander-expand')
					.removeClass('fa-plus')
					.addClass('fa-minus');

				li.find('>ul').show();

				this.expandedPagesAdd(parent_id);
			}
		}
	}
}


CMS.controllers.add('page.get.index', function () {
	Page.init();
	$('#pageMapReorderButton').on('click', function () {
		var self = $(this);

		if (self.hasClass('btn-inverse')) {
			$('#page-search-list').empty().hide();
			$('#page-tree-header').show();
			self.removeClass('btn-inverse');

			$.get('/api.page.children', {parent_id: 1, level: 0}, function (resp) {
				$('#page-tree-list')
					.find('ul')
					.remove();

				$('#page-tree-list')
					.show()
					.find('li')
					.append(resp.content);

				CMS.ui.init('icon');
			});

		} else {
			self.addClass('btn-inverse');
			$('#page-tree-list').hide();
			$('#page-tree-header').hide();

			Api.get('/api.page.reorder', {}, function (response) {
				$('#page-search-list')
					.html(response.content)
					.show();

				$('#nestable').nestable({
					group: 1,
					maxDepth: 10,
					listNodeName: 'ul',
					listClass: 'dd-list list-unstyled',
				}).on('change', function (e, el) {
					var list = $(e.target).data('nestable');

					var data,
						depth = 0,
						array = [];

					step = function (level, depth) {
						var items = level.children(list.options.itemNodeName),
							position = 0;

						items.each(function () {
							var li = $(this),
								sub = li.children(list.options.listNodeName),
								parent_id = level.parent().data('id');

							array.push({
								parent_id: parseInt(parent_id ? parent_id : list.options.parent_id),
								id: li.data('id'),
								position: position++
							});

							if (sub.length) {
								step(sub, depth + 1);
							}
						});
					};
					step(list.el.find(list.options.listNodeName).first(), depth);

					if (!array.length)
						return false;

					Api.post('/api.page.reorder', {'pids': array});
				});
			}, self.parent());
		}
	});

	$('.form-search').on('submit', function (event) {
		var form = $(this);

		if ($('#page-seacrh-input').val() !== '') {
			$('#page-tree-list').hide();

			CMS.loader.show('#page-search-list');

			Api.get('/api.page.search', form.serialize(), function (resp) {
				$('#page-search-list').html(resp.content);
				CMS.loader.hide();
				CMS.ui.init('icon');
			});

		} else {
			$('#page-tree-list').show();
			$('#page-search-list').hide();
		}

		return false;
	});

	var editable_status = {
		selector: '.editable-status',
		type: 'select2',
		title: i18n.t('pages.core.field.status'),
		send: 'always',
		highlight: false,
		ajaxOptions: {
			dataType: 'json'
		},
		params: function (params) {
			params.page_id = $(this).closest('li').data('id');
			return params;
		},
		url: function (params) {
			var $self = $(this);
			Api.post('/api.page.changeStatus', params, function (response) {
				$self.replaceWith($(response.content).editable(editable_status));
			});
		},
		source: PAGE_STATUSES,
		select2: {
			width: 200,
			placeholder: i18n.t('pages.core.field.status')
		}
	};

	$('#page-tree-list, #page-search-list').editable(editable_status);
});


CMS.controllers.add('page.get.create', function () {
	$('body').on('keyup', 'input[name="title"]', function () {
		$('input[name="breadcrumb"]')
			.add('input[name="meta_title"]')
			.val($(this).val());
	});

	$('.panel-toggler').click();
});

CMS.controllers.add(['page.get.create', 'page.get.edit'], function () {
	$('body').on('change', 'select[name="status"]', function () {
		show_password_field($(this).val());
	});

	$('#page-meta-panel').on('click', ':input', function () {
		var $fields = {};
		var $array = ['breadcrumb', 'meta_title', 'meta_keywords', 'meta_description'];
		for (i in $array) {
			$fields[$array[i]] = $('#' + $array[i]).val();
		}

		Api.get('/api.page.parse_meta', {
			page_id: PAGE.id,
			fields: $fields
		}, function (response) {
			if (response.content) {
				for (field in response.content) {
					$('#field-' + field + ' .help-block').text(response.content[field]);
				}
			}
		});
	});

	var $redirectCheckbox = $('input[name="is_redirect"]');

	$redirectCheckbox.on('change', function () {
		show_redirect_field($(this))
	});

	$('#redirect-container').on('click', function (e) {
		if (!$(e.target).is(':input, label') && !$redirectCheckbox.is(':checked'))
			$redirectCheckbox.trigger('click')
	});

	show_redirect_field($redirectCheckbox);
	function show_redirect_field(input) {
		var cont = $('#redirect-to-container'),
			meta_cont = $('#page-meta-panel-li');

		if (input.is(':checked')) {
			input.closest('.form-group').removeClass('no-margin-b');
			cont.show();
			meta_cont.hide();
		} else {
			input.closest('.form-group').addClass('no-margin-b');
			cont.hide();
			meta_cont.show();
		}
	}
});

CMS.controllers.add(['page.get.edit'], function () {
	var partModel = Backbone.Model.extend({
		urlRoot: '/api.page.part',
		defaults: {
			name: 'part',
			wysiwyg: DEFAULT_HTML_EDITOR,
			page_id: PAGE.id,
			content: '',
			is_protected: 0,
			is_expanded: 1,
			is_indexable: 0,
			is_developer: 1,
			position: 0
		},

		parse: function (response, xhr) {
			if (_.contains('POST', 'PUT'), response.method) {
				return response.content;
			}

			return response;
		},

		validate: function (attrs) {
			if (!$.trim(attrs.name))
				return 'Name must be set';
		},

		switchProtected: function () {
			this.save({is_protected: this.get('is_protected') == 1 ? 0 : 1});
		},

		toggleMinimize: function () {
			this.save({is_expanded: this.get('is_expanded') == 1 ? 0 : 1});
		},

		switchIndexable: function () {
			this.save({is_indexable: this.get('is_indexable') == 1 ? 0 : 1});
		},

		changeFilter: function (wysiwyg) {
			if (this.get('wysiwyg') != wysiwyg)
				this.save({wysiwyg: wysiwyg});
		},

		destroyFilter: function () {
			CMS.filters.switchOff('pageEditPartContent-' + this.get('name'));
		},

		clear: function () {
			this.destroy();
		}
	});

	var partCollection = Backbone.Collection.extend({
		url: '/api.page.part',
		model: partModel,
		parse: function (response, xhr) {
			return response.content;
		},
		comparator: function (a) {
			return a.get('position');
		},
		setOrder: function (data) {
			Api.post('/api.page.part.reorder', {ids: data});
		}
	});

	var partView = Backbone.View.extend({
		tagName: 'div',

		template: _.template($('#part-body').html()),
		attributes: function () {
			return {
				'data-id': this.model.id
			};
		},
		events: {
			'click .part-options-button': 'toggleOptions',
			'click .part-minimize-button': 'toggleMinimize',
			'dblclick .panel-title': 'editName',
			'change .item-filter': 'changeFilter',
			'change .is_protected': 'switchProtected',
			'change .is_indexable': 'switchIndexable',
			'click .item-remove': 'clear',
			'click .part-rename': 'editName',
			'keypress .edit-name': 'updateOnEnter'
		},

		updateOnEnter: function (e) {
			if (e.keyCode == 13) this.closeEditName();
			this.input.val(this.input.val().replace(/[^a-z0-9\-\_]/, ''));
		},

		checkPermissions: function () {
			return !(this.model.get('is_protected') == 1 && this.model.get('is_developer') == 0);
		},

		editName: function (e) {
			if (!this.checkPermissions()) return;

			if (this.$el.hasClass("editing")) {
				this.closeEditName();
			} else {
				this.input.show().focus();
				this.$el.find('.part-name').hide();
			}

			this.$el.toggleClass("editing");
			return false;
		},

		closeEditName: function () {
			if (!this.checkPermissions()) return;

			var value = $.trim(this.input.val());
			this.model.save({name: value});

			this.render();

			return false;
		},

		toggleMinimize: function (e) {
			e.preventDefault();

			if (this.model.get('is_expanded') == 1) {
				this.$el
					.find('.part-minimize-button i')
					.addClass('fa-chevron-down')
					.removeClass('fa-chevron-up')
					.end()
					.find('.item-filter-cont').hide()
					.end()
					.find('.part-textarea').slideUp();
			} else {
				this.$el.find('.part-minimize-button i')
					.addClass('fa-chevron-up')
					.removeClass('fa-chevron-down')
					.end()
					.find('.item-filter-cont').show()
					.end()
					.find('.part-textarea').slideDown();
			}

			this.model.toggleMinimize();
		},

		changeFilter: function () {
			var wysiwyg = this.$el.find('.item-filter').val();
			this.model.changeFilter(wysiwyg);
			CMS.filters.switchOn('pageEditPartContent-' + this.model.get('name'), wysiwyg);
		},

		toggleOptions: function (e) {
			e.preventDefault();
			this.$el.find('.part-options').toggle();
		},

		switchProtected: function () {
			this.model.switchProtected();
		},

		switchIndexable: function () {
			this.model.switchIndexable();
		},

		initialize: function () {
			this.model.on('add', this.render, this);
			this.model.on('destroy', this.remove, this);
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
			this.$el.data('id', this.model.id);

			this.changeFilter();

			this.input = this.$el.find('.edit-name').hide();

			if (this.model.get('is_protected') == 1) {
				this.$el.find('.is_protected').check();
			}

			if (this.model.get('is_indexable') == 1) {
				this.$el.find('.is_indexable').check();
			}

			return this;
		},

		// Remove the item, destroy the model.
		clear: function (e) {
			e.preventDefault();
			if (confirm(__('Remove part :part_name?', {":part_name": this.model.get('name')}))) this.model.clear();
		}
	});

	var partListView = Backbone.View.extend({
		el: $("#pageEditParts"),
		initialize: function () {
			var $self = this;
			this.collection.fetch({
				data: {
					pid: PAGE.id
				},
				success: function () {
					$self.render();
				}
			});

			this.$el.sortable({
				axis: "y",
				handle: ".panel-heading-sortable-handler",
				receive: _.bind(function (event, ui) {
					// do something here?
				}, this),
				remove: _.bind(function (event, ui) {
					// do something here?
				}, this),
				update: _.bind(function (event, ui) {
					var list = ui.item.context.parentNode;
					this.collection.setOrder($(list).sortable('toArray', {attribute: 'data-id'}));
				}, this)
			});
		},

		render: function () {
			this.clear();
			this.collection.each(function (part) {
				this.addPart(part);
			}, this);

			this.collection.on('add', this.render, this);
		},

		clear: function () {
			this.$el.empty();
		},

		addPart: function (part) {
			var view = new partView({model: part});
			this.$el.append(view.render().el);
			view.changeFilter();
		}
	});

	var partPanel = Backbone.View.extend({
		el: $("#pageEditPartsPanel"),

		initialize: function () {
			if (PAGE.id == 0)
				this.$el.remove();
		},

		events: {
			'click #pageEditPartAddButton': 'createPart'
		},

		createPart: function (e) {
			e.preventDefault();

			this.model = new partModel();

			if (this.collection.length == 0)
				this.model.set('name', 'body');

			var i = 0;
			this.collection.each(function (part) {
				if (part.get('name') == this.model.get('name')) {
					do {
						i++;
						this.model.set('name', 'part' + i);
					} while (this.model.get('name') == part.get('name'));
				}

			}, this);


			this.model.save();

			this.model.on("sync", function () {
				this.collection.each(function (part) {
					part.destroyFilter();
				}, this);
				this.collection.add(this.model);
				this.model.off('sync');
			}, this);

		}
	});

	var PartCollection = new partCollection();
	new partListView({
		collection: PartCollection
	});
	new partPanel({
		collection: PartCollection
	});
});