## KodiCMS based on Laravel PHP Framework

Для установки системы, необходимо:

 * Клонировать репозиторий `git clone git@github.com:KodiCMS/kodicms-laravel.git`
 * Запустить команду `composer install` для загрузки всех необходимых компонентов
 * Выполнить команду `php artisan cms:modules:install` для создания таблиц в БД.
 * Выполнить команду `php artisan cms:modules:seed` для заполения тестовыми данными БД

### Авторизация

	username: **admin@test.com**
	password: **password**

### События в шаблонах

#### backend/navbar
 * `view.backend.navbar.before`

#### backend/navigation
 * `view.backend.navigation.before`
 * `view.backend.navigation.after`

#### auth/login
 * `view.login.form.header`
 * `view.login.form.footer`
 * `view.login.form.after`

### auth/password
 * `view.password.form.footer`