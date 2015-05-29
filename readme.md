## KodiCMS based on Laravel PHP Framework

[![Join the chat at https://gitter.im/KodiCMS/kodicms-laravel](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/KodiCMS/kodicms-laravel?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Для установки системы, необходимо:

 * Клонировать репозиторий `git clone git@github.com:KodiCMS/kodicms-laravel.git`
 * Запустить команду `composer install` для загрузки всех необходимых компонентов
 * Выполнить команду `php artisan cms:install` создание .env файла, миграция и добавление сидов (`php artisan cms:install --help` для просмотра доступных параметров)
 
---

### Авторизация

Сайт: http://laravel.kodicms.ru/backend

username: **admin@site.com**  
password: **password**

username: **test@test.com**  
password: **password**

---

### Консольные команды

 * `layout:rebuild_blocks` - индексация размеченых блоков в шаблонах
 * `api:generate_key` - генерация нового API ключа
 * `cms:install` - создание .env файла, миграция и добавление сидов (в будущем данная команда будет создавать файл и производить миграцию)
 * `cms:modules:publish` - публикация `view` шаблонов
 * `cms:modules:migrate` - создание таблиц в БД
 * `cms:modules:seed` - заполнение таблиц тестовыми данными
 * `cms:generate:translate:js` - генерация JS языковых файлов
 * `cms:modules:locale:publish` - генерация пакета lang файлов для перевода. Файлы будут скопированы в `/resources/lang/packages`
 * `cms:make:controller` - создание контроллера (`cms:make:controller TestController --module=cms --type=backend` создаст контроллер в модуле `modules\CMS`. Существует два типа контроллеров `[api, backend]`)
 * `cms:packages:list` - список всех media пакетов

---

### Загрузка сервис-провайдеров и алиасов
Изначально Laravel загружает сервис-провайдеры и алиасы из конфиг файла `config/app.php`, но чтобы отделить системных провайдеров от пользовательских, они были вынесены в отдельные файлы `modules/CMS/providers.php` и `modules/CMS/aliases.php`, пользовательские подключать можно по прежнему через конфиг.

### Структура модуля 
[https://github.com/KodiCMS/kodicms-laravel/wiki/Modules](https://github.com/KodiCMS/kodicms-laravel/wiki/Modules)

---

### События
[https://github.com/KodiCMS/kodicms-laravel/wiki/Events](https://github.com/KodiCMS/kodicms-laravel/wiki/Events)

---

### Регистрация консольных комманд через ServiceProvider
В KodiCMS есть базовый сервисный провайдер, в котором уже реализован метод для регистрации комманд. Для использования необходимо наследовать класс провайдера от `KodiCMS\CMS\Providers\ServiceProvider`
Пример регистрации команды

	public function register()
	{
		$this->registerConsoleCommand('module.seed', '\KodiCMS\Installer\Console\Commands\ModuleSeed');
	}

### Отдельное спасибо команде JetBrains за бесплатно предоставленый ключ для PHPStorm
![PHPStorm](https://www.jetbrains.com/phpstorm/documentation/docs/logo_phpstorm.png)