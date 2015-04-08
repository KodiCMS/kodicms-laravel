## KodiCMS based on Laravel PHP Framework

Для установки системы, необходимо:

 * Клонировать репозиторий `git clone git@github.com:KodiCMS/kodicms-laravel.git`
 * Запустить команду `composer install` для загрузки всех необходимых компонентов
 * Выполнить команду `php artisan cms:modules:migrate` для создания таблиц в БД.
 * Выполнить команду `php artisan cms:modules:seed` для заполения тестовыми данными БД
 
---

### Авторизация

Сайт: http://laravel.kodicms.ru/backend

username: **admin@site.com**  
password: **password**

username: **test@test.com**  
password: **password**

---

### Консольные команды

 * `cms:modules:migrate` - создание таблиц в БД
 * `cms:modules:seed` - заполнение таблиц тестовыми данными
 * `cms:generate:translate:js` - генерация JS языковых файлов

---

### Структура модуля
 * `config` - конфиги приложения, могут быть перезаписаны из папки `/config/`
 * `Console`
  * `Commands` - расположение файлов консольных компанды
 * `database`
  * `migrations` - файлы миграции, будут запущены по команде `cms:modules:migrate`
  * `seeds`
   * `DatabaseSeeder.php` - если существует, то будет запущен по команде `cms:modules:seed`
 * `Http`
  * `Controllers` - контроллеры модуля
  * `Middleware`
  * `routes.php` - роуты текущего модуля, оборачиваются в неймспейс `KodiCMS\{module}`
 * `Providers`
  * `ModuleServiceProvider.php` - Сервис провайдер, если есть, будет запущен в момент инициализации приложения
 * `resources`
  * `js` - JavaScript файлы, в этой папке происходит поиск js файлов по виртуальным путям `/backend/cms/js/{script.js}`
  * `lang` - Файлы переводов для модуля, доступны по ключу названия модуля приведенного в нижний регистр `trans('{module}::file.key')`
  * `views` - Шаблоны модуля, доступны по ключу названия модуля приведенного в нижний регистр `view('{module}::template')`
  * `packages.php` - В данном файле можно подключать свои Assets (Media) пакеты
 * `ModuleContainer.php` - Если данный файл существует, то он будет подключен как системный файл модуля, в котором указаны относительыне пути и действия в момент инициализации. Необходимо наследовать от `KodiCMS\CMS\Loader\ModuleContainer`

---

### Состав модулей
 * CMS
  1. Dashboard
 * Pages
  1. Page
  2. Layout
  3. PagePart
 * Users
  1. User
  2. Role
  3. Permission
 * Widgets
  1. Widget
  2. Blocks
  3. Snippet
 * Filemanager
  1. elFinder
 * Email
  1. Email
  2. Email Templates
  3. Email Types

---

### События в шаблонах

#### pages/create
 * `view.page.create`
 
#### pages/edit
 * `view.page.edit.before [$page]`
 * `view.page.edit [$page]`

#### backend/navbar
 * `view.navbar.before`

#### backend/navigation
 * `view.navigation.before`
 * `view.navigation.after`

### system/about
 * `view.system.about`

#### auth/login
 * `view.login.form.header`
 * `view.login.form.footer`
 * `view.login.form.after`

### auth/password
 * `view.password.form.footer`
 
### user/profile
 * `view.user.profile.information`

### user/edit
 * `view.user.edit.form.password [$user]`
 * `view.user.edit.form.bottom [$user]`

### user/create
  * `view.user.create.form.password`
  * `view.user.create.form.bottom`