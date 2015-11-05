<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;

class FormItemServiceProvider extends ServiceProvider
{
    public function register()
    {
        FormItem::register('columns', \KodiCMS\SleepingOwlAdmin\FormItems\Columns::class);
        FormItem::register('text', \KodiCMS\SleepingOwlAdmin\FormItems\Text::class);
        FormItem::register('time', \KodiCMS\SleepingOwlAdmin\FormItems\Time::class);
        FormItem::register('date', \KodiCMS\SleepingOwlAdmin\FormItems\Date::class);
        FormItem::register('timestamp', \KodiCMS\SleepingOwlAdmin\FormItems\Timestamp::class);
        FormItem::register('textaddon', \KodiCMS\SleepingOwlAdmin\FormItems\TextAddon::class);
        FormItem::register('select', \KodiCMS\SleepingOwlAdmin\FormItems\Select::class);
        FormItem::register('multiselect', \KodiCMS\SleepingOwlAdmin\FormItems\MultiSelect::class);
        FormItem::register('hidden', \KodiCMS\SleepingOwlAdmin\FormItems\Hidden::class);
        FormItem::register('checkbox', \KodiCMS\SleepingOwlAdmin\FormItems\Checkbox::class);
        FormItem::register('ckeditor', \KodiCMS\SleepingOwlAdmin\FormItems\CKEditor::class);
        FormItem::register('custom', \KodiCMS\SleepingOwlAdmin\FormItems\Custom::class);
        FormItem::register('password', \KodiCMS\SleepingOwlAdmin\FormItems\Password::class);
        FormItem::register('textarea', \KodiCMS\SleepingOwlAdmin\FormItems\Textarea::class);
        FormItem::register('view', \KodiCMS\SleepingOwlAdmin\FormItems\View::class);
        FormItem::register('image', \KodiCMS\SleepingOwlAdmin\FormItems\Image::class);
        FormItem::register('images', \KodiCMS\SleepingOwlAdmin\FormItems\Images::class);
        FormItem::register('file', \KodiCMS\SleepingOwlAdmin\FormItems\File::class);
        FormItem::register('radio', \KodiCMS\SleepingOwlAdmin\FormItems\Radio::class);
    }
}
