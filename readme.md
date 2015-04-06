## KodiCMS based on Laravel PHP Framework

Для установки системы, необходимо:

 * Клонировать репозиторий `git clone git@github.com:KodiCMS/kodicms-laravel.git`
 * Запустить команду `composer install` для загрузки всех необходимых компонентов
 * Выполнить команду `php artisan cms:modules:migrate` для создания таблиц в БД.
 * Выполнить команду `php artisan cms:modules:seed` для заполения тестовыми данными БД

### Авторизация

Сайт: http://laravel.kodicms.ru/backend

username: **admin@site.com**
password: **password**

username: **test@test.com**
password: **password**

### Консольные команды

 * `cms:modules:migrate` - создание таблиц в БД
 * `cms:modules:seed` - заполнение таблиц тестовыми данными
 * `cms:generate:translate:js` - генерация JS языковых файлов

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
 
### user/profile
 * `view.user.profile.information`

### user/edit
 * `view.user.edit.form.password [$user]`
 * `view.user.edit.form.bottom [$user]`

### user/create
  * `view.user.create.form.password`
  * `view.user.create.form.bottom`