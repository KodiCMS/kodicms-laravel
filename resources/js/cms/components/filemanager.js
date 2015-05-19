CMS.filemanager = {
	open: function(object, type) {
		return Popup.openIframe(BASE_URL + '/filemanager.popup', {
			onComplete: function(a) {
				$("#cboxLoadedContent iframe").load(function(){
					this.contentWindow.elfinderInit({
						getFileCallback: function(file) {
							if(_.isObject(file)) file = file.url;

							if(_.isObject(object)) {
								object.val(file);
								Popup.close();
							}
							else if(window.top.CMS.filters.exec(object, 'insert', file))
								Popup.close();
						}
					});
				});
			}
		});
	}
}