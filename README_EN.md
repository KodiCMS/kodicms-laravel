## KodiCMS based on Laravel PHP Framework 
### [Russian Version](https://github.com/teodorsandu/kodicms-laravel/blob/dev/readme.md)

[![Join the chat at https://gitter.im/KodiCMS/kodicms-laravel](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/KodiCMS/kodicms-laravel?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

### Installation:

 * Clone repository `git clone https://github.com/KodiCMS/kodicms-laravel.git`
 * Run command `composer install` to download all the necessary components
 * Install CMS `php artisan cms:install` (`php artisan cms:install --help`) Or rename .env.example to .env and set database connection, then run artisan command `php artisan cms:modules:migrate --seed`
 
---

### Authorization

Website: http://laravel.kodicms.ru/backend

username: **admin_en@site.com**  
password: **password**

---

### Console commands

 * `php artisan cms:install` - .env file creation, migration and adding oxides (in the future, this command will create a file and migrate)
 * `php artisan cms:modules:migrate` - create tables in the database
   - To roll back the old migrations need to add `--rollback`
   - For seeding data you need to add `--seed`

 * `php artisan cms:modules:seed` - populating tables with test data
 
 * `php artisan cms:modules:publish` - Publish view templates
 * `php artisan cms:modules:locale:publish` - generation package lang files for translation. The files will be copied to the `/resources/lang/vendor`
 * `php artisan cms:modules:locale:diff --locale=en` - check if all the keys in translation in a folder `/resources/lang/vendor` relatively modules.
 * `php artisan cms:generate:translate:js` - Generate javascript translate admin files
 
 * `php artisan cms:modules:list` - Show information about modules and plug-ins
 * `php artisan cms:wysiwyg:list` - a list of installed text editors (Show wysiwyg information)
 * `php artisan cms:packages:list` - a list of all media packages (Show asset packages list)
 * `php artisan cms:plugins:list` - Show plugins information
 
 * `php artisan cms:layout:rebuild-blocks` - indexing of marked blocks in a template (Rebuild templates blocks)
 * `php artisan cms:api:generate-key` - generating new API key (Generate API key)
 * `php artisan cms:reflinks:delete-expired` - Disposal of obsolete service links
  
 * `php artisan cms:make:controller` - creating controller (`cms:make:controller TestController --module=cms --type=backend` creates controller module `modules\CMS`. There are two types of controllers `[api, backend]`)
 
 * `php artisan cms:plugins:activate author:plugin` - Plugin activation
 * `php artisan cms:plugins:deactivate author:plugin [--removetable=no]` - Deactivating the plugin (remove tables from the database)

---

### Module structure
[https://github.com/KodiCMS/kodicms-laravel/wiki/Modules](https://github.com/KodiCMS/kodicms-laravel/wiki/Modules)

---

### Events
[https://github.com/KodiCMS/kodicms-laravel/wiki/Events](https://github.com/KodiCMS/kodicms-laravel/wiki/Events)

---

### Roadmap

* ~~Adding Laravel modular structure~~
* ~~Transfer system kernel~~
* ~~Transfer module "API"~~
* ~~Transfer module "elFinder"~~
* ~~Transfer module "Pages"~~
* ~~Transfer module "Layouts"~~
* ~~Transfer module "Snippets"~~
* ~~Transfer module "Email"~~
* ~~Transfer module "Cron jobs"~~
* ~~Transfer module "Widgets"~~
* ~~Transfer module "Dashboard"~~
* ~~Transfer module "Users, Roles, ACL"~~
* ~~Transfer module "Reflinks"~~
* ~~Implementation connect plug-ins with the structure similar modules~~
* ~~Notification unit (Notifications)~~
* Transfer module "Datasource"
* Transfer the plugin "Hybrid" and its integration into the system with enhanced functionality
* The implementation of the system installer
* Search Module (Mysql, Sphinx)
* Image Editing

### Special thanks to the team for the free provision of JetBrains key PHPStorm
![PHPStorm](https://www.jetbrains.com/phpstorm/documentation/docs/logo_phpstorm.png)
