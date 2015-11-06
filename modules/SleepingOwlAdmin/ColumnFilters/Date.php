<?php

namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use KodiCMS\SleepingOwlAdmin\Interfaces\RepositoryInterface;
use KodiCMS\SleepingOwlAdmin\Interfaces\NamedColumnInterface;

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
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return bool
     */
    public function hasSeconds()
    {
        return $this->seconds;
    }

    /**
     * @param bool $seconds
     */
    public function setSeconds($seconds)
    {
        $this->seconds = (bool) $seconds;
    }

    /**
     * @return string
     */
    public function getPickerFormat()
    {
        if (is_null($this->pickerFormat)) {
            return $this->generatePickerFormat();
        }

        return $this->pickerFormat;
    }

    /**
     * @param string $pickerFormat
     */
    public function setPickerFormat($pickerFormat)
    {
        $this->pickerFormat = $pickerFormat;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        intval($width);
        if ($width < 0) {
            $width = 0;
        }
        $this->width = (int) $width;
    }

    /**
     * @return string
     */
    public function getSearchFormat()
    {
        return $this->searchFormat;
    }

    /**
     * @param string $searchFormat
     */
    public function setSearchFormat($searchFormat)
    {
        $this->searchFormat = $searchFormat;
    }

    /**
     * @param RepositoryInterface  $repository
     * @param NamedColumnInterface $column
     * @param Builder              $query
     * @param string               $search
     * @param string               $fullSearch
     * @param string               $operator
     *
     * @return void
     */
    public function apply(
        RepositoryInterface $repository,
        NamedColumnInterface $column,
        Builder $query,
        $search,
        $fullSearch,
        $operator = '='
    ) {
        if (empty($search)) {
            return;
        }
        try {
            $time = Carbon::createFromFormat($this->getFormat(), $search);
        } catch (Exception $e) {
            try {
                $time = Carbon::parse($search);
            } catch (Exception $e) {
                return;
            }
        }
        $time = $time->format($this->getSearchFormat());
        $name = $column->getName();
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
            'seconds'      => $this->hasSeconds(),
            'format'       => $this->getFormat(),
            'pickerFormat' => $this->getPickerFormat(),
            'width'        => $this->getWidth(),
        ];
    }

    /**
     * @return string
     */
    protected function generatePickerFormat()
    {
        return strtr($this->getFormat(), [
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
        ]);
    }
}
