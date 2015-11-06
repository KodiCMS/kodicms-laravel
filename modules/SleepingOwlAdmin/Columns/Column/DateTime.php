<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

use Carbon\Carbon;

class DateTime extends NamedColumn
{
    /**
     * Datetime format.
     * @var string
     */
    protected $format;

    /**
     * @return string
     */
    public function getFormat()
    {
        if (is_null($this->format)) {
            $this->setFormat(config('sleeping_owl.datetimeFormat'));
        }

        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $value = $this->getModelValue();
        $originalValue = $value;
        if (! is_null($value)) {
            if (! $value instanceof Carbon) {
                $value = Carbon::parse($value);
            }
            $value = $value->format($this->getFormat());
        }

        return app('sleeping_owl.template')->view('column.datetime', [
            'value'         => $value,
            'originalValue' => $originalValue,
            'append'        => $this->getAppend(),
        ]);
    }
}
