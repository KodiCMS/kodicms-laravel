var CMS = {
	models: {},
	views: {},
	collections: {},
	routes: {},
	settings: {},
	translations: {},
	plugins: {},

	popup_target: null,
	/**
	 * Вычисление высоты контейнера с контентом
	 */
	content_height: null,
	calculateContentHeight: function() {
		if(this.content_height != null)
			return this.content_height;

		var contentCont = $('#content-wrapper'),
			headerCont = $('#main-navbar'),
			footerCont = $('footer'),
			windowCont = $(window);

		var contentContHeight = windowCont.outerHeight() - headerCont.outerHeight(),
			contentContPadding = contentCont.outerHeight(!$('body').hasClass('iframe')) - contentCont.innerHeight() + ($('body').hasClass('iframe')) ? 0 : 140;

		this.content_height = contentContHeight - contentContPadding;

		return this.content_height;
	}
};