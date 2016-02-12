var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	mix
		.less('common.less', 'public/cms/css')
		.less('kodicms/jquery-ui/jquery-ui.less', 'public/cms/css/jquery-ui.css');

	mix
		.less('custom/page-wysiwyg.less', 'public/cms/css/page-wysiwyg.css')

	mix.scripts([
		'libs/jquery/js/jquery.min.js',
		'libs/bootstrap/js/bootstrap.js',
		'libs/noty/js/jquery.noty.packaged.js',
		'libs/select2/js/select2.full.js',
		'libs/jquery-colorbox/js/jquery.colorbox-min.js',
		'libs/bootstrap-toggle/js/bootstrap-toggle.min.js',
		'libs/jquery-validation/js/jquery.validate.js',
		'libs/jquery-validation/js/additional-methods.js',
		'libs/dropzone/js/dropzone.min.js',
		'libs/datetimepicker/js/jquery.datetimepicker.js',
		'libs/underscore/js/underscore-min.js',
		'libs/moment/js/moment.min.js',
		'libs/fastclick/js/fastclick.js',
		'libs/slimScroll/js/jquery.slimscroll.min.js',
		'libs/jquery-query-object/js/jquery.query-object.js',
		'libs/bootbox.js/js/bootbox.js',
		'libs/i18next/js/i18next.min.js'
	], 'public/cms/js/libraries.js', 'public/cms/');

	mix.scripts([
		'cms/core.js',
		'cms/app.js',
		'cms/components/messages.js',
		'cms/components/filters.js',
		'cms/components/loader.js',
		'cms/components/notifications.js',
		'cms/components/filemanager.js',
		'cms/components/controllers.js',
		'cms/components/ui.js',
		'cms/helpers.js',
		'cms/ui.js',
		'cms/hashString.js',
		'cms/popup.js',
		'cms/scroll.js',
		'cms/api.js',
		'cms/user.meta.js',
		'cms/run.js'
	], 'public/cms/js/backend.js');

	mix.scripts([
		'libs/jquery/js/jquery.min.js',
		'libs/sortable/js/Sortable.min.js',
		'libs/sortable/js/jquery.binding.js',
		'libs/jquery-colorbox/js/jquery.colorbox-min.js',
		'libs/jquery-query-object/js/jquery.query-object.js',
		'libs/underscore/js/underscore-min.js',
	], 'public/cms/js/page-wysiwyg-libraries.js', 'public/cms/');

	mix.scripts([
		'cms/app.js',
		'cms/components/ui.js',
		'cms/popup.js',
		'cms/api.js',
		'cms/page-wysiwyg.js'
	], 'public/cms/js/page-wysiwyg.js');


	mix.scripts([
		'jQuery.extendext.js',
		'doT.js',
		'main.js',
		'defaults.js',
		'core.js',
		'public.js',
		'data.js',
		'template.js',
		'model.js',
		'utils.js',
		'jquery.js',
		'fields/types/core.js',
		'fields/types/checkbox.js',
		'fields/types/datetime.js',
		'fields/types/number.js',
		'fields/types/select.js',
		'fields/types/textarea.js',
		'fields/core.js',
		'plugins/sortable.js'
	], 'public/cms/libs/query-builder/query-builder.js', 'resources/assets/js/query-builder')
		.less('query-builder/default.less', 'public/cms/libs/query-builder/query-builder.css');

	/**************************************************************
	 * elFinder
	 **************************************************************/
	mix
		.scripts([
			'elfinder.js',
			'elFinder.version.js',
			'jquery.elfinder.js',
			'elFinder.options.js',
			'elFinder.history.js',
			'elFinder.command.js',
			'elFinder.resources.js',
			'jquery.dialogelfinder.js',
			'elfinder.en.js',
			'ui/button.js',
			'ui/contextmenu.js',
			'ui/cwd.js',
			'ui/dialog.js',
			'ui/navbar.js',
			'ui/overlay.js',
			'ui/panel.js',
			'ui/path.js',
			'ui/places.js',
			'ui/searchbutton.js',
			'ui/sortbutton.js',
			'ui/stat.js',
			'ui/toolbar.js',
			'ui/tree.js',
			'ui/uploadButton.js',
			'ui/viewbutton.js',
			'ui/workzone.js',
			'commands/archive.js',
			'commands/back.js',
			'commands/copy.js',
			'commands/cut.js',
			'commands/download.js',
			'commands/duplicate.js',
			'commands/edit.js',
			'commands/extract.js',
			'commands/forward.js',
			'commands/getfile.js',
			'commands/help.js',
			'commands/home.js',
			'commands/info.js',
			'commands/mkdir.js',
			'commands/mkfile.js',
			'commands/netmount.js',
			'commands/open.js',
			'commands/paste.js',
			'commands/quicklook.js',
			'commands/quicklook/audio.js',
			'commands/quicklook/flash.js',
			'commands/quicklook/images.js',
			'commands/quicklook/pdf.js',
			'commands/quicklook/text.js',
			'commands/quicklook/video.js',
			'commands/quicklook/browser.media.js',
			'commands/reload.js',
			'commands/rename.js',
			'commands/resize.js',
			'commands/rm.js',
			'commands/search.js',
			'commands/sort.js',
			'commands/up.js',
			'commands/upload.js',
			'commands/view.js',
			'elfinder.end.js'
		], 'public/cms/libs/elfinder/js/elfinder.min.js', 'resources/assets/js/elfinder')
		.styles(['elfinder.full.css',], 'public/cms/libs/elfinder/css/elfinder.min.css', 'public/cms/libs/elfinder/css/');
});
