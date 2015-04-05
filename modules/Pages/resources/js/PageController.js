var Page = {
	cacheKey: 'expanded_pages',
	_expandedPages: [],
	init: function() {
		this.expandedPages();
		this.loadChildren(1, 0, $('#page-tree-list').find('li'));
		$('#page-tree-list').on('click', '.item-expander', $.proxy(this.onExpand, this));
	},
	expandedPages: function() {
		Api.get('/api.user.meta', {key: this.cacheKey}, $.proxy(function(response) {
			this._expandedPages = _.map(response.content, function(num) { return parseInt(num); });
		}, this));
	},
	expandedPagesAdd: function(page_id) {
		this._expandedPages.push(page_id);
		Api.post('/api.user.meta', {key: this.cacheKey, value: _.uniq(this._expandedPages)});
	},
	expandedPagesRemove: function(page_id) {
		this._expandedPages = _.filter(this._expandedPages, function(num) {
			return num != parseInt(page_id);
		});
		Api.post('/api.user.meta', {key: this.cacheKey, value: _.uniq(this._expandedPages)});
	},
	loadChildren: function(parent_id, level, $container, $expander) {
		Api.get('/api.page.children', {parent_id: parent_id, level: level}, $.proxy(function(response) {
			$container.append(response.content);
			if($expander) {
				$expander
					.addClass('item-expander-expand')
					.removeClass('fa-plus')
					.addClass('fa-minus');

				if(parent_id > 1) {
					$container.addClass('item-expanded');
					this.expandedPagesAdd(parent_id);
				}
			}

			CMS.ui.init('icon');
		}, this));
	},
	onExpand: function(e) {
		var expander = $(e.target),
			li = expander.closest('li'),
			parent_id = li.data('id');

		if ( ! li.hasClass('item-expanded')) {
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


CMS.controllers.add('page.get.index', function() {
	Page.init();
	$('#pageMapReorderButton').on('click', function() {
		var self = $(this);

		if (self.hasClass('btn-inverse')) {
			$('#page-search-list').empty().hide();
			$('#page-tree-header').show();
			self.removeClass('btn-inverse');

			$.get('/api.page.children', {parent_id: 1, level: 0}, function(resp) {
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

			Api.get('/api.page.reorder', {}, function(response) {
				$('#page-search-list')
					.html(response.content)
					.show();

				$('#nestable').nestable({
					group: 1,
					maxDepth: 10,
					listNodeName: 'ul',
					listClass: 'dd-list list-unstyled',
				}).on('change', function(e, el) {
					var list = e.length ? e : $(e.target);
					var pages = list.nestable('serialize');
					if (!pages.length)
						return false;

					Api.post('/api.page.reorder', {'pids': pages});
				});
			}, self.parent());
		}
	});

	$('.form-search').on('submit', function(event) {
		var form = $(this);

		if ($('#page-seacrh-input').val() !== '') {
			$('#page-tree-list').hide();

			Api.get('/api.page.search', form.serialize(), function(resp) {
				$('#page-search-list').html(resp.content);
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
		title: __('Page status'),
		send: 'always',
		highlight: false,
		ajaxOptions: {
			dataType: 'json'
		},
		params: function(params) {
			params.page_id = $(this).closest('li').data('id');
			return params;
		},
		url: function(params) {
			var $self = $(this);
			Api.post('/api.page.changeStatus', params, function(response) {
				$self.replaceWith($(response.content).editable(editable_status));
			});
		},
		source: PAGE_STATUSES,
		select2: {
			width: 200,
			placeholder: __('Page status')
		}
	};
	
	$('#page-tree-list').editable(editable_status);
});


CMS.controllers.add('page.get.add', function() {
	$('body').on('keyup', 'input[name="page[title]"]', function() {
		$('input[name="page[breadcrumb]"]')
			.add('input[name="page[meta_title]"]')
			.val($(this).val());
	});
	
	$('.panel-toggler').click();
});

CMS.controllers.add(['page.get.add', 'page.get.edit'], function() {
	$('body').on('change', 'select[name="page[status_id]"]', function() {
		show_password_field($(this).val());
	});
	
	$('#page-meta-panel').on('click', ':input', function() {
		var $fields = {};
		var $array = ['breadcrumb', 'meta_title', 'meta_keywords', 'meta_description'];
		for(i in $array) {
			$fields[$array[i]] = $('#page_' + $array[i]).val();
		}
	
		Api.get('pages.parse_meta', {
			page_id: PAGE_ID,
			fields: $fields
		}, function(response) {
			if(response.response) {
				for(field in response.response) {
					$('#field-' + field + ' .help-block').text(response.response[field]);
				}
			}
		});
	});

	$('input[name="page[use_redirect]"]').on('change', function() {
		show_redirect_field($(this))
	});

	show_redirect_field($('input[name="page[use_redirect]"]'));
	show_password_field($('select[name="page[status_id]"]').val());

	function show_redirect_field(input) {
		var cont = $('#redirect-to-container'),
			meta_cont = $('#page-meta-panel-li');

		if (input.is(':checked')) {
			cont.show();
			meta_cont.hide();
		} else {
			cont.hide();
			meta_cont.show();
		}
	}

	function show_password_field(val) {
		var select = $('select[name="page[status_id]"]');

		if (val == 200) {
			select.parent().addClass('well well-small').find('.password-container').removeClass('hidden');
		} else {
			select.parent().removeClass('well well-small')
				.find('.password-container').addClass('hidden')
				.find('input').val('');
		}
	}
});