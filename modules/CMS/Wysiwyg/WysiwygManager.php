<?php

namespace KodiCMS\CMS\Wysiwyg;

use KodiCMS\CMS\Exceptions\WysiwygException;
use Illuminate\Contracts\Foundation\Application;

class WysiwygManager
{
    const TYPE_HTML = 'html';
    const TYPE_CODE = 'code';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var
     */
    protected $config;

    /**
     * Available wysiwyg editors.
     *
     * @var array
     */
    protected $available = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $this->app['config'];
    }

    /**
     * @return string|null
     */
    public function getDefaultHTMLEditorId()
    {
        return $this->config['cms.default_html_editor'];
    }

    /**
     * @return string|null
     */
    public function getDefaultCodeEditorId()
    {
        return $this->config['cms.default_code_editor'];
    }

    /**
     * Return TYPE_CODE constant.
     *
     * @return string
     */
    public function code()
    {
        return static::TYPE_CODE;
    }

    /**
     * Return TYPE_HTML constant.
     *
     * @return string
     */
    public function html()
    {
        return static::TYPE_HTML;
    }

    /**
     * @param $editorId
     *
     * @return bool
     */
    public function isExists($editorId)
    {
        return isset($this->available[$editorId]);
    }

    /**
     * @param string $editorId
     *
     * @return bool
     */
    public function isLoaded($editorId)
    {
        if (! is_null($editor = $this->getEditor($editorId))) {
            return $editor->isUsed();
        }

        return false;
    }

    /**
     * Список используемых редакторов на странице.
     *
     * @return array
     */
    public function getUsed()
    {
        $editors = [];
        foreach ($this->getAvailable() as $editorId => $editor) {
            if ($editor->isUsed()) {
                $editors[$editorId] = $editor;
            }
        }

        return $editors;
    }

    /**
     * @param string      $editorId
     * @param string|null $name
     * @param string|null $filter
     * @param string|null $package
     * @param string      $type
     *
     * @return $this
     */
    public function add($editorId, $name = null, $filter = null, $package = null, $type = self::TYPE_HTML)
    {
        $this->available[$editorId] = new WysiwygEditor($editorId, $name, $filter, $package, $type);

        return $this;
    }

    /**
     * Получения списка доступных редакторов в системе.
     * @return array
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Получения списка доступных редакторов определенного типа.
     *
     * @param string $type
     *
     * @return array
     */
    public function getAvailableByType($type)
    {
        $editors = [];

        foreach ($this->getAvailable() as $editorId => $editor) {
            if ($type == $editor->getType()) {
                $editors[$editorId] = $editor;
            }
        }

        return $editors;
    }

    /**
     * Получение объекта редактора.
     *
     * @param $editorId
     *
     * @return WysiwygEditor|null
     */
    public function getEditor($editorId)
    {
        return array_get($this->getAvailable(), $editorId, new WysiwygEditor('dummy'));
    }

    /**
     * @return WysiwygEditor
     */
    public function getDefaultHTMLEditor()
    {
        return $this->getEditor($this->getDefaultHTMLEditorId());
    }

    /**
     * @return WysiwygEditor
     */
    public function getDefaultCodeEditor()
    {
        return $this->getEditor($this->getDefaultCodeEditorId());
    }

    /**
     * Загрузить в шаблон все редакторы.
     */
    public function loadAllEditors()
    {
        foreach ($this->getAvailable() as $editorId => $editor) {
            $this->loadEditor($editorId);
        }
    }

    /**
     * Загрузить в шаблон редакторы кода.
     */
    public function loadCodeEditors()
    {
        foreach ($this->getAvailableByType(static::TYPE_HTML) as $editorId => $editor) {
            $this->loadEditor($editorId);
        }
    }

    /**
     * Загрузить в шаблон редакторы текса.
     */
    public function loadHTMLEditors()
    {
        foreach ($this->getAvailableByType(static::TYPE_HTML) as $editorId => $editor) {
            $this->loadEditor($editorId);
        }
    }

    /**
     * Загрузить в шаблон редактор текста по умолчанию.
     */
    public function loadDefaultHTMLEditor()
    {
        $this->loadEditor($this->getDefaultHTMLEditorId());
    }

    /**
     * Загрузить в шаблон редактор кода по умолчанию.
     */
    public function loadDefaultCodeEditor()
    {
        $this->loadEditor($this->getDefaultCodeEditorId());
    }

    /**
     * Загрузить в шаблон редакторы по умолчанию.
     */
    public function loadDefaultEditors()
    {
        $this->loadDefaultHTMLEditor();
        $this->loadDefaultCodeEditor();
    }

    /**
     * Загрузить редактор в шаблон по идентификатору.
     *
     * @param string $editorId
     *
     * @return bool
     */
    public function loadEditor($editorId)
    {
        if (is_null($editorId)) {
            return false;
        }

        if (! is_null($editor = $this->getEditor($editorId))) {
            if ($editor->isUsed()) {
                return true;
            }

            return $editor->load();
        }

        return false;
    }

    /**
     * Применить фильтр используемый редактором к тексту.
     *
     * @param string $editorId
     * @param string $text
     *
     * @return string string
     * @throws WysiwygException
     */
    public function applyFilter($editorId, $text)
    {
        if (! is_null($editor = $this->getEditor($editorId))) {
            return $editor->applyFilter($text);
        }

        throw new WysiwygException("Editor [{$editorId}] not found");
    }

    /**
     * Получение списка редакторов для выпадающего списка.
     *
     * @param string $type
     *
     * @return array
     */
    public function htmlSelect($type = null)
    {
        $editors = is_null($type)
            ? $this->getAvailable()
            : $this->getAvailableByType($type);

        return $this->makeHTMLselect($editors);
    }

    /**
     * Получение списка редакторов для выпадающего списка подключенных в шаблон.
     *
     * @return array
     */
    public function usedHtmlSelect()
    {
        return $this->makeHTMLselect($this->getUsed());
    }

    /**
     * @param array $editors
     *
     * @return array
     */
    protected function makeHTMLselect(array $editors)
    {
        $options = ['' => trans('cms::core.helpers.not_select')];

        foreach ($editors as $editorId => $editor) {
            $options[$editorId] = $editor->getName();
        }

        return $options;
    }
}
