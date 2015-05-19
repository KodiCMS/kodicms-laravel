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
		'libs/jquery.min.js',
		'libs/bootstrap-3.3.4/dist/js/bootstrap.js',
		'libs/jquery.noty.packaged.js',
		'libs/select2/select2.js',
		'libs/validation/jquery.validate.js',
		'libs/dropzone/dropzone.js',
		'libs/colorbox/jquery.colorbox.js',
		'libs/datepicker/jquery.datetimepicker.js',
		'libs/underscore-min.js',
		'libs/moment.js',
		'libs/fastclick.js',
		'libs/jquery.slimscroll.js',
		'libs/jquery.query-object.js',
		'libs/i18next-1.8.0.js'
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
