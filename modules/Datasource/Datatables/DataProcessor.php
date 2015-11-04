<?php

namespace KodiCMS\Datasource\Datatables;

use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;
use yajra\Datatables\Helper;
use yajra\Datatables\Processors\RowProcessor;

class DataProcessor extends \yajra\Datatables\Processors\DataProcessor
{
    /**
     * Columns to escape value.
     *
     * @var array
     */
    private $escapeColumns = [];

    /**
     * Processed data output.
     *
     * @var array
     */
    private $output = [];

    /**
     * @var array
     */
    private $appendColumns = [];

    /**
     * @var array
     */
    private $editColumns = [];

    /**
     * @var array
     */
    private $excessColumns = [];

    /**
     * @var mixed
     */
    private $results;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var SectionHeadlineInterface
     */
    protected $headline;

    /**
     * @param array                    $results
     * @param SectionHeadlineInterface $headline
     * @param array                    $columnDef
     * @param array                    $templates
     */
    public function __construct($results, SectionHeadlineInterface $headline, array $columnDef, array $templates)
    {
        $this->results = $results;
        $this->headline = $headline;
        $this->appendColumns = $columnDef['append'];
        $this->editColumns = $columnDef['edit'];
        $this->excessColumns = $columnDef['excess'];
        $this->escapeColumns = $columnDef['escape'];
        $this->templates = $templates;
    }

    /**
     * Process data to output on browser.
     *
     * @param bool $object
     *
     * @return array
     */
    public function process($object = false)
    {
        $this->output = [];

        foreach ($this->results as $row) {
            $data = $row->toHeadlineArray($this->headline);

            $value = $this->addColumns($data, $row);
            $value = $this->editColumns($value, $row);
            $value = $this->setupRowVariables($value, $row);
            $value = $this->removeExcessColumns($value);

            $this->output[] = $object ? $value : $this->flatten($value);
        }

        return $this->escapeColumns($this->output);
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function flatten(array $array)
    {
        $return = [];
        $exceptions = ['DT_RowId', 'DT_RowClass', 'DT_RowData', 'DT_RowAttr'];

        foreach ($array as $key => $value) {
            if (in_array($key, $exceptions)) {
                $return[$key] = $value;
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /**
     * Process add columns.
     *
     * @param array $data
     * @param mixed $row
     *
     * @return array
     */
    protected function addColumns(array $data, $row)
    {
        foreach ($this->appendColumns as $key => $value) {
            $value['content'] = Helper::compileContent($value['content'], $data, $row);
            $data = Helper::includeInArray($value, $data);
        }

        return $data;
    }

    /**
     * Process edit columns.
     *
     * @param array $data
     * @param mixed $row
     *
     * @return array
     */
    protected function editColumns(array $data, $row)
    {
        foreach ($this->editColumns as $key => $value) {
            $value['content'] = Helper::compileContent($value['content'], $data, $row);
            $data[$value['name']] = $value['content'];
        }

        return $data;
    }

    /**
     * Setup additional DT row variables.
     *
     * @param mixed $data
     * @param mixed $row
     *
     * @return array
     */
    protected function setupRowVariables($data, $row)
    {
        $processor = new RowProcessor($data, $row);

        return $processor
            ->rowValue('DT_RowId', $this->templates['DT_RowId'])
            ->rowValue('DT_RowClass', $this->templates['DT_RowClass'])
            ->rowData('DT_RowData', $this->templates['DT_RowData'])
            ->rowData('DT_RowAttr', $this->templates['DT_RowAttr'])
            ->getData();
    }

    /**
     * Remove declared hidden columns.
     *
     * @param array $data
     *
     * @return array
     */
    protected function removeExcessColumns(array $data)
    {
        foreach ($this->excessColumns as $value) {
            unset($data[$value]);
        }

        return $data;
    }

    /**
     * Escape column values as declared.
     *
     * @param array $output
     *
     * @return array
     */
    protected function escapeColumns(array $output)
    {
        return array_map(function ($row) {
            foreach ($row as $key => $value) {
                if ($this->escapeColumns == '*' || in_array($key, $this->escapeColumns, true)) {
                    $row[$key] = e($value);
                }
            }

            return $row;
        }, $output);
    }
}
