<?php

return [
	/*
	 * Laravel Framework Service Providers...
	 */
	Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
	Illuminate\Auth\AuthServiceProvider::class,
	Illuminate\Broadcasting\BroadcastServiceProvider::class,
	Illuminate\Bus\BusServiceProvider::class,
	Illuminate\Cache\CacheServiceProvider::class,
	Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
	Illuminate\Routing\ControllerServiceProvider::class,
	Illuminate\Cookie\CookieServiceProvider::class,
	Illuminate\Database\DatabaseServiceProvider::class,
	Illuminate\Encryption\EncryptionServiceProvider::class,
	Illuminate\Filesystem\FilesystemServiceProvider::class,
	Illuminate\Foundation\Providers\FoundationServiceProvider::class,
	Illuminate\Hashing\HashServiceProvider::class,
	Illuminate\Mail\MailServiceProvider::class,
	Illuminate\Pagination\PaginationServiceProvider::class,
	Illuminate\Pipeline\PipelineServiceProvider::class,
	Illuminate\Queue\QueueServiceProvider::class,
	Illuminate\Redis\RedisServiceProvider::class,
	Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
	Illuminate\Session\SessionServiceProvider::class,
	Illuminate\Translation\TranslationServiceProvider::class,
	Illuminate\Validation\ValidationServiceProvider::class,
	Illuminate\View\ViewServiceProvider::class,

	/*
	 * KodiCMS Service Providers...
	 */
	KodiCMS\Support\Html\HtmlServiceProvider::class,
	KodiCMS\CMS\Providers\ModuleServiceProvider::class,
	KodiCMS\Plugins\Providers\PluginServiceProvider::class,
	KodiCMS\CMS\Providers\RouteServiceProvider::class,
	KodiCMS\CMS\Providers\EventServiceProvider::class,
	KodiCMS\CMS\Providers\BusServiceProvider::class,
	KodiCMS\CMS\Providers\AppServiceProvider::class,
	KodiCMS\CMS\Providers\ConfigServiceProvider::class,
];