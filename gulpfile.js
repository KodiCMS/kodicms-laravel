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
		'libs/pnotify/jquery.pnotify.js',
		'libs/select2/select2.js',
		'libs/validation/jquery.validate.min.js',
		'libs/dropzone/dropzone.js',
		'libs/fancybox/jquery.fancybox.js',
		'libs/datepicker/jquery.datetimepicker.js',
		'libs/underscore-min.js'
	], 'public/cms/js/libraries.js', 'public/cms/');

	mix.scripts([
		'cms/core.js',
		'cms/backend.js'
		//'cms/api.js',
		//'cms/app.js',
		//'cms/ui.js',
		//'cms/run.js',
	], 'public/cms/js/backend.js');
});
