CMS.controllers.add(['user.get.edit', 'user.get.create'], function () {
	$('input[name="user_roles"]').select2({
		placeholder: __("Click to get list of roles"),
		minimumInputLength: 0,
		multiple: true,
		ajax: {
			url: '/api.roles',
			data: function(query, pageNumber, context) {
				return {
					key: query
				}
			},
			dataType: 'json',
			results: function (resp, page) {
				var roles = [];
				if(resp.content) {
					for(i in resp.content) {
						roles.push({
							id: resp.content[i]['id'],
							text: resp.content[i]['name']
						});
					}
				}

				return {results: roles};
			}
		},
		initSelection: function(element, callback) {
			element.val('');
			if (!_.has(USER, 'id')  ) {
				callback([{'id':1, 'text':'login'}]);
				return ;
			}

			Api.get('/api.user.roles', {
					uid: USER.id
			}, function(resp, page) {
				var roles = [];
				if(resp.content) {
					for(i in resp.content) {
						roles.push({
							id: resp.content[i]['id'],
							text: resp.content[i]['name']
						});
					}
				}

				callback(roles);
			});
		}
	});
});

CMS.controllers.add('user.get.profile', function () {
	var toolbar = $('.profile-toolbar');
	var toolbar_l = toolbar.text().replace(/\t/g, '').replace(/\n/g, '').replace(/&nbsp;/g, '').replace(/ /g, '').length;
	
	if(!toolbar_l) toolbar.css({'padding': 0});
});

CMS.controllers.add('user.get.edit', function () {
	$('#themes .theme').on('click', function (e) {
		if ($(this).hasClass('active'))
			e.preventDefault();

		$('#themes .active').removeClass('active');
		$(this).addClass('active');

		activateTheme($(this).data('theme'));
		e.preventDefault();
	});
});


var activateTheme = function(theme) {
	Api.post('/api.user.meta', {key: 'cms_theme', value:theme, uid: USER.id});
	document.body.className = document.body.className.replace(/theme\-[a-z0-9\-\_]+/ig, 'theme-' + theme);
}