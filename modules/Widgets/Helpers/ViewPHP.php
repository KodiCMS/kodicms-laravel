<?php

namespace KodiCMS\Widgets\Helpers;

class ViewPHP
{
    /**
     * @var string
     */
    protected $phpCode = '';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string $phpCode
     */
    public function __construct($phpCode)
    {
        $this->phpCode = $phpCode;
    }

    /**
     * Add a piece of data to the view.
     *
     * @param  string|array $key
     * @param  mixed        $value
     *
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function render()
    {
        // Import the view variables to local namespace
        extract($this->data, EXTR_SKIP);

        // Capture the view output
        ob_start();

        try {
            eval('?>'.$this->phpCode);
        } catch (\Exception $e) {
            // Delete the output buffer
            ob_end_clean();

            // Re-throw the exception
            throw $e;
        }

        // Get the captured output and close the buffer
        return ob_get_clean();
    }
}
