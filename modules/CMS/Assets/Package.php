<?php
namespace KodiCMS\CMS\Assets;

use HTML;
use Iterator;

class Package implements Iterator
{

    /**
     * @var array
     */
    protected $list = [];


    /**
     * Добавление пакета
     *
     * @param string $handle
     *
     * @return Package
     */
    public function add($handle)
    {
        return new static($handle, $this);
    }


    /**
     * Загрузка пакета
     *
     * @param string $name
     *
     * @return Package|NULL
     */
    public function load($name)
    {
        $package = array_get($this->list, $name);

        return $package;
    }


    /**
     * Получение списка всех пакетов
     * @return array
     */
    public function getAll()
    {
        return $this->list;
    }


    /**
     * @param array|string $names
     *
     * @return array
     */
    public function getScripts($names)
    {
        if ( ! is_array($names)) {
            $names = [$names];
        }

        $scripts = [];

        foreach ($names as $name) {
            $package = $this->load($name);

            if ($package === null) {
                continue;
            }

            foreach ($package as $item) {
                switch ($item['type']) {
                    case 'js':
                        $scripts[] = $item['src'];
                        break;
                }
            }
        }

        return $scripts;
    }


    /**
     * @return array
     */
    public function getHTMLSelectChoice()
    {
        $options = array_keys($this->list);

        return array_combine($options, $options);
    }


    /**
     *
     * @var string
     */
    protected $handle = null;

    /**
     *
     * @var array
     */
    protected $data = [];

    /**
     *
     * @var integer
     */
    private $position = 0;


    /**
     * @param string|null $handle
     * @param string|null $parent
     */
    public function __construct($handle = null, $parent = null)
    {
        if (is_null($handle) || is_null($parent)) {
            return;
        }

        $this->handle = $handle;

        $parent->list[$handle] = $this;
    }


    /**
     * @param string       $handle
     * @param string       $src
     * @param array|string $deps
     * @param array        $attrs
     *
     * @return $this
     */
    public function css($handle = null, $src = null, $deps = null, array $attrs = null)
    {
        if ($handle === null) {
            $handle = $this->handle;
        }

        // Set default media attribute
        if ( ! isset( $attrs['media'] )) {
            $attrs['media'] = 'all';
        }

        $this->data[] = [
            'type'   => 'css',
            'src'    => $src,
            'deps'   => (array) $deps,
            'attrs'  => $attrs,
            'handle' => $handle,
            'type'   => 'css',
        ];

        return $this;
    }


    /**
     * @param string|bool $handle
     * @param string      $src
     * @param array       $deps
     * @param bool        $footer
     *
     * @return $this
     */
    public function js($handle = false, $src = null, $deps = null, $footer = false)
    {
        if ($handle === null) {
            $handle = $this->handle;
        }

        $this->data[] = [
            'type'   => 'js',
            'src'    => $src,
            'deps'   => (array) $deps,
            'footer' => $footer,
            'handle' => $handle,
            'type'   => 'js',
        ];

        return $this;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        $string = '';

        foreach ($this->data as $item) {
            switch ($item['type']) {
                case 'css':
                    $string .= HTML::style($item['src'], $item['attrs']);
                    break;
                case 'js':
                    $string .= HTML::script($item['src']);
                    break;
            }
        }

        return $string;
    }


    /**
     * @return void
     */
    function rewind()
    {
        $this->position = 0;
    }


    /**
     * @return mixed
     */
    function current()
    {
        return $this->data[$this->position];
    }


    /**
     * @return int
     */
    function key()
    {
        return $this->position;
    }


    /**
     * @return void
     */
    function next()
    {
        ++$this->position;
    }


    /**
     * @return bool
     */
    function valid()
    {
        return isset( $this->data[$this->position] );
    }
}