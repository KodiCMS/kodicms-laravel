<?php

namespace KodiCMS\Cron\Repository;

use KodiCMS\Cron\Model\Job;
use KodiCMS\CMS\Repository\BaseRepository;

class CronRepository extends BaseRepository
{
    /**
     * @param Job $model
     */
    public function __construct(Job $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $validator = $this->validator($data, [
            'date_start' => 'required|date',
            'date_end'   => 'required|date',
            'task_name'  => 'required',
            'name'       => 'required',
            'interval'   => 'integer|min:1|required_without:crontime',
            'crontime'   => 'crontab|required_without:interval',
        ], trans('cron::validation'));

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validateOnUpdate(array $data = [])
    {
        $validator = $this->validator($data, [
            'date_start' => 'required|date',
            'date_end'   => 'required|date',
            'task_name'  => 'required',
            'name'       => 'required',
            'interval'   => 'integer|min:1|required_without:crontime',
            'crontime'   => 'crontab|required_without:interval',
        ], trans('cron::validation'));

        return $this->_validate($validator);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function runJob($id)
    {
        $job = $this->findOrFail($id);
        $job->run();

        return $job;
    }
}
