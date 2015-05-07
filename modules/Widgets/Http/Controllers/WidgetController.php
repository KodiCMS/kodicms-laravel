<?php namespace KodiCMS\Widgets\Http\Controllers;

use Assets;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\Widgets\Services\WidgetCreator;

class WidgetController extends BackendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'widgets::';

	public function getIndex()
	{
		Assets::package(['editable']);

		$widgets = Widget::paginate();
		$this->setContent('widgets.list', compact('widgets'));
	}

	public function getCreate()
	{
		$this->setTitle(trans('widgets::core.title.create'));

		$types = WidgetManagerDatabase::getAvailableTypes();

		$this->setContent('widgets.create', compact('types'));
	}

	public function postCreate(WidgetCreator $creator)
	{
		$data = $this->request->all();

		$validator = $creator->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$widget = $creator->create($data);

		return $this->smartRedirect([$widget])
			->with('success', trans('widgets::core.messages.created', ['name' => $widget->name]));
	}

	public function getLocation($id)
	{
		$this->setTitle(trans('widgets::core.title.location'));

		$this->setContent('widgets.location');
	}

	public function getEdit($id)
	{
		$this->setTitle(trans('widgets::core.title.edit'));

		$this->setContent('widgets.edit');
	}

	public function postEdit($id)
	{

	}

	public function getDelete($id)
	{
		$widget = $this->getWidget($id);
		$widget->delete();

		return $this->smartRedirect()
			->with('success', trans('widgets::core.messages.deleted', ['name' => $widget->name]));
	}

	/**
	 * @param integer $id
	 * @return Widget
	 * @throws HttpResponseException
	 */
	protected function getWidget($id)
	{
		try {
			return Widget::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			$this->throwFailException($this->smartRedirect()->withErrors(trans('widgets::core.messages.not_found')));
		}
	}
}