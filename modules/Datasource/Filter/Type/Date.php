<?php

namespace KodiCMS\Datasource\Filter\Type;

use DB;
use KodiCMS\Datasource\Filter\Type;
use KodiCMS\Datasource\Model\Field;

class Date extends Type
{
    /**
     * @var string
     */
    protected $type = 'date';

    /**
     * @var string
     */
    protected $input = 'date_string';

    /**
     * @var string
     */
    protected $format = 'Y-m-d';

    /**
     * @param Field $field
     */
    public function __construct(Field $field)
    {
        parent::__construct($field);

        $this->format = $field->getDateFormat();
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function parseValue($value)
    {
        return $this->parseDate($value, $this->format);
    }

    /**
     * @return array|null
     */
    public function getQueryField()
    {
        return DB::raw("date_format({$this->tableField}, '{$this->getConvertedFormatToSQL()}')");
    }

    /**
     * @return mixed
     */
    protected function getConvertedFormatToSQL()
    {
        $replace = [
            'Y' => '%Y',
            'm' => "%m",
            'd' => '%d',
            'H' => '%H'
        ];

        return str_replace(array_keys($replace), array_values($replace), $this->format);
    }
}
