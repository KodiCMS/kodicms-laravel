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

	/*
		elFinder
	 */
	mix
		.scripts(['elfinder.full.js'], 'public/cms/libs/elfinder/js/elfinder.min.js', 'public/cms/libs/elfinder/js/')
		.styles(['elfinder.full.css',], 'public/cms/libs/elfinder/css/elfinder.min.css', 'public/cms/libs/elfinder/css/');
});
