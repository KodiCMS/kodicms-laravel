<?php

namespace KodiCMS\Datasource\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use KodiCMS\Datasource\Contracts\SectionInterface;

class DocumentQueryBuilder extends Builder
{
    /**
     * @var SectionInterface
     */
    protected $section;

    /**
     * Create a new Eloquent query builder instance.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param SectionInterface                    $section
     *
     * @return void
     */
    public function __construct(QueryBuilder $query, SectionInterface $section)
    {
        parent::__construct($query);
        $this->section = $section;
    }

    /**
     * Get the hydrated models without eager loading.
     *
     * @param  array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model[]
     */
    public function getModels($columns = ['*'])
    {
        $results = $this->query->get($columns);

        $instance = $this->model->newInstance()->setConnection($this->model->getConnectionName());

        $results = array_map(function ($item) use ($instance) {
            return $instance->newFromBuilder($item);
        }, $results);

        return $instance->newCollection($results)->all();
    }
}
