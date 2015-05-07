<?php namespace KodiCMS\Cron\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Cron\Model\Job;
use KodiCMS\Cron\Services\JobCreator;
use KodiCMS\Cron\Services\JobUpdator;

class CronController extends BackendController
{

	public $moduleNamespace = 'cron::';

	public function getIndex()
	{
		$jobs = Job::paginate();

		$this->setContent('cron.list', compact('jobs'));
	}

	public function getCreate()
	{
		$this->setTitle(trans('cron::core.title.cron.create'));

		$job = new Job;
		$action = 'backend.cron.create.post';

		$this->setContent('cron.form', compact('job', 'action'));
	}

	public function postCreate(JobCreator $jobCreator)
	{
		$data = $this->request->all();

		$validator = $jobCreator->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$job = $jobCreator->create($data);

		return $this->smartRedirect([$job])
			->with('success', trans('cron::core.messages.created', ['title' => $job->name]));
	}

	public function getEdit($id)
	{
		$job = $this->getJob($id);
		$this->setTitle(trans('cron::core.title.cron.edit', [
			'title' => $job->name
		]));
		$action = 'backend.cron.edit.post';

		$this->setContent('cron.form', compact('job', 'action'));
	}

	public function postEdit(JobUpdator $jobUpdator, $id)
	{
		$data = $this->request->all();
		$validator = $jobUpdator->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$job = $jobUpdator->update($id, $data);

		return $this->smartRedirect([$job])
			->with('success', trans('cron::core.messages.updated', ['title' => $job->name]));
	}

	public function getDelete($id)
	{
		$job = $this->getJob($id);
		$job->delete();

		return $this->smartRedirect()
			->with('success', trans('cron::core.messages.deleted', ['title' => $job->name]));
	}

	public function getRun($id)
	{
		$job = $this->getJob($id);
		$job->run();

		return redirect(route('backend.cron.edit', $job))
			->with('success', trans('cron::core.messages.runned', ['title' => $job->name]));
	}

	protected function getJob($id)
	{
		try {
			return Job::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			$this->throwFailException($this->smartRedirect()->withErrors(trans('cron::core.messages.not_found')));
		}
		return null;
	}

}