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
		.less('common.less', 'public/cms/css');

	mix.scripts([
		'libs/jquery/dist/jquery.js',
		'libs/bootstrap/dist/js/bootstrap.js',
		'libs/noty/js/noty/packaged/jquery.noty.packaged.js',
		'libs/select2/dist/js/select2.full.js',
		'libs/colorbox/jquery.colorbox.js',
		'libs/jquery-validation/dist/jquery.validate.js',
		'libs/jquery-validation/dist/additional-methods.js',
		'libs/dropzone/dist/dropzone.js',
		'libs/datetimepicker/jquery.datetimepicker.js',
		'libs/underscore/underscore.js',
		'libs/moment/moment.js',
		'libs/fastclick/lib/fastclick.js',
		'libs/slimScroll/jquery.slimscroll.js',
		'libs/jquery-query-object/jquery.query-object.js',
		'libs/i18next/i18next.js'
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
		'cms/popup.js',
		'cms/api.js',
		'cms/user.meta.js',
		'cms/run.js'
	], 'public/cms/js/backend.js');
});
