CMS.filemanager = {
	open: function(object, type) {
		return $.fancybox.open({
			href : BASE_URL + '/filemanager.popup',
			type: 'iframe'
		}, {
			autoSize: false,
			width: 1000,
			afterLoad: function() {
				this.content[0].contentWindow.elfinderInit({
					getFileCallback: function(file) {
						if(_.isObject(file)) {
							file = file.url;
						}
						if(_.isObject(object)) {
							object.val(file);
							window.top.$.fancybox.close();
						}
						else {
							if(window.top.CMS.filters.exec(object, 'insert', file))
								window.top.$.fancybox.close();
						}
					}
				});
			}
		});
	}
}