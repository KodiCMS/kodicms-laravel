<?php

namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

use Carbon\Carbon;

class Date extends Text
{
    /**
     * @var string
     */
    protected $view = 'date';

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $pickerFormat;

    /**
     * @var string
     */
    protected $searchFormat = 'Y-m-d';

    /**
     * @var bool
     */
    protected $seconds = false;

    /**
     * @var int
     */
    protected $width = 150;

    /**
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
     * @param int|null $seconds
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
     * @param int $width
     *
     * @return $this|int
     */
    public function width($width = null)
    {
        if (is_null($width)) {
            return $this->width;
        }
        $this->width = $width;

        return $this;
    }

    /**
     * @param string|null $searchFormat
     *
     * @return $this|string
     */
    public function searchFormat($searchFormat = null)
    {
        if (is_null($searchFormat)) {
            return $this->searchFormat;
        }
        $this->searchFormat = $searchFormat;

        return $this;
    }

    /**
     * @param        $repository
     * @param        $column
     * @param        $query
     * @param        $search
     * @param        $fullSearch
     * @param string $operator
     */
    public function apply($repository, $column, $query, $search, $fullSearch, $operator = '=')
    {
        if (empty($search)) {
            return;
        }
        try {
            $time = Carbon::createFromFormat($this->format(), $search);
        } catch (\Exception $e) {
            try {
                $time = Carbon::parse($search);
            } catch (\Exception $e) {
                return;
            }
        }
        $time = $time->format($this->searchFormat());
        $name = $column->name();
        if ($repository->hasColumn($name)) {
            $query->where($name, $operator, $time);
        } elseif (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $fieldName = array_pop($parts);
            $relationName = implode('.', $parts);
            $query->whereHas($relationName, function ($q) use ($time, $fieldName, $operator) {
                $q->where($fieldName, $operator, $time);
            });
        }
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
            'width'        => $this->width(),
        ];
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
