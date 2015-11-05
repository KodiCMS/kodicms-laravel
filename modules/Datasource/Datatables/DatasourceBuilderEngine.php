<?php

namespace KodiCMS\Datasource\Datatables;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use yajra\Datatables\Helper;
use Illuminate\Http\JsonResponse;
use League\Fractal\Resource\Collection;
use yajra\Datatables\Engines\QueryBuilderEngine;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

class DatasourceBuilderEngine extends QueryBuilderEngine
{
    /**
     * @var SectionHeadlineInterface
     */
    protected $headline;

    /**
     * @param DocumentInterface        $model
     * @param SectionHeadlineInterface $headline
     * @param Request                  $request
     */
    public function __construct(DocumentInterface $model, SectionHeadlineInterface $headline, Request $request)
    {
        $this->request = $request;
        $this->query_type = 'datasource';
        $this->headline = $headline;

        $this->query = $model->select();
        $this->columns = $this->query->getQuery()->columns;
        $this->connection = $model->getConnection();

        $this->database = $this->connection->getDriverName();

        if ($this->isDebugging()) {
            $this->connection->enableQueryLog();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ordering()
    {
        foreach ($this->request->orderableColumns() as $orderable) {
            $column = $this->setupColumnName($orderable['column']);
            if (isset($this->columnDef['order'][$column])) {
                $method = $this->columnDef['order'][$column]['method'];
                $parameters = $this->columnDef['order'][$column]['parameters'];
                $this->compileColumnQuery($this->getQueryBuilder(), $method, $parameters, $column, $orderable['direction']);
            } else {
                $this->getQueryBuilder()->orderBy($column, $orderable['direction']);
            }
        }
    }

    /**
     * Render json response.
     *
     * @param bool $object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($object = false)
    {
        $processor = new DataProcessor($this->results(), $this->headline, $this->columnDef, $this->templates);

        $data = $processor->process($object);

        $output = ['draw'            => (int) $this->request['draw'],
                   'recordsTotal'    => $this->totalRecords,
                   'recordsFiltered' => $this->filteredRecords,
        ];

        if (isset($this->transformer)) {
            $fractal = new Manager();
            $resource = new Collection($data, new $this->transformer());
            $collection = $fractal->createData($resource)->toArray();
            $output['data'] = $collection['data'];
        } else {
            $output['data'] = Helper::transform($data);
        }

        if ($this->isDebugging()) {
            $output = $this->showDebugger($output);
        }

        return new JsonResponse($output);
    }
}
