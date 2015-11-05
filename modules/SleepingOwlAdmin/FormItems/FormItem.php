<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use KodiCMS\SleepingOwlAdmin\AliasBinder;

/**
 * Class AdminForm.
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Text text($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Image image($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Images images($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\File file($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Time time($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Date date($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Timestamp timestamp($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\TextAddon textaddon($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Password password($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Select select($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\MultiSelect multiselect($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Columns columns()
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Hidden hidden($name)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Custom custom()
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\View view($view)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Checkbox checkbox($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\CKEditor ckeditor($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Textarea textarea($name, $label = null)
 * @method static \KodiCMS\SleepingOwlAdmin\FormItems\Radio radio($name, $label = null)
 */
class FormItem extends AliasBinder
{
    /**
     * @var array
     */
    protected static $aliases = [];
}
