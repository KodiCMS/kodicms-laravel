<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Carbon\Carbon;

class BaseDateTime extends NamedFormItem
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @var bool
     */
    protected $seconds = false;

    /**
     * @var string
     */
    protected $pickerFormat;

    /**
     * @var string
     */
    protected $defaultConfigFormat = 'datetimeFormat';

    /**
     * @param string|null $format
     *
     * @return $this|string
     */
    public function format($format = null)
    {
        if (is_null($format)) {
            if (is_null($this->format)) {
                $this->format(config('sleeping_owl.'.$this->defaultConfigFormat));
            }

            return $this->format;
        }
        $this->format = $format;

        return $this;
    }

    /**
     * @param bool|null $seconds
     *
     * @return $this|bool
     */
    public function seconds($seconds = null)
    {
        if (is_null($seconds)) {
            return $this->seconds;
        }
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * @return $this|NamedFormItem|mixed|null|string
     */
    public function value()
    {
        $value = parent::value();
        if (empty($value)) {
            $value = null;
        }
        if (! is_null($value)) {
            try {
                $time = Carbon::parse($value);
            } catch (\Exception $e) {
                try {
                    $time = Carbon::createFromFormat($this->format(), $value);
                } catch (\Exception $e) {
                    return;
                }
            }
            $value = $time->format($this->format());
        }

        return $value;
    }

    public function save()
    {
        $name = $this->name();
        $value = parent::value();
        if (empty($value)) {
            $value = null;
        }
        if (! is_null($value)) {
            $value = Carbon::createFromFormat($this->format(), $value);
        }
        $this->instance()->$name = $value;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'seconds'      => $this->seconds(),
            'format'       => $this->format(),
            'pickerFormat' => $this->pickerFormat(),
        ];
    }

    /**
     * @param string|null $pickerFormat
     *
     * @return $this|string
     */
    public function pickerFormat($pickerFormat = null)
    {
        if (is_null($pickerFormat)) {
            if (is_null($this->pickerFormat)) {
                return $this->generatePickerFormat();
            }

            return $this->pickerFormat;
        }
        $this->pickerFormat = $pickerFormat;

        return $this;
    }

    /**
     * @return string
     */
    protected function generatePickerFormat()
    {
        $format = $this->format();
        $replacement = [
            'i' => 'mm',
            's' => 'ss',
            'h' => 'hh',
            'H' => 'HH',
            'g' => 'h',
            'G' => 'H',
            'd' => 'DD',
            'j' => 'D',
            'm' => 'MM',
            'n' => 'M',
            'Y' => 'YYYY',
            'y' => 'YY',
        ];

        return strtr($format, $replacement);
    }
}
