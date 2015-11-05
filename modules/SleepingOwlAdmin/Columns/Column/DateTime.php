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
     * Get or set datetime format.
     *
     * @param string|null $format
     *
     * @return $this|string
     */
    public function format($format = null)
    {
        if (is_null($format)) {
            if (is_null($this->format)) {
                $this->format(config('sleeping_owl.datetimeFormat'));
            }

            return $this->format;
        }
        $this->format = $format;

        return $this;
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $value = $this->getValue($this->instance, $this->name());
        $originalValue = $value;
        if (! is_null($value)) {
            if (! $value instanceof Carbon) {
                $value = Carbon::parse($value);
            }
            $value = $value->format($this->format());
        }

        return app('sleeping_owl.template')->view('column.datetime', [
            'value'         => $value,
            'originalValue' => $originalValue,
            'append'        => $this->append(),
        ]);
    }
}
