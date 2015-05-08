<?php namespace KodiCMS\Widgets\Http\Controllers;

use Assets;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Assets\Package;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Widgets\Manager\WidgetManager;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\Widgets\Model\SnippetCollection;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\Widgets\Services\WidgetCreator;
use KodiCMS\Widgets\Services\WidgetUpdator;

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
		$widget = $this->getWidget($id);

		$this->breadcrumbs->add($widget->getType());

		$this->setTitle(trans('widgets::core.title.edit', [
			'name' => $widget->name
		]));

		$commentKeys = WidgetManager::getTemplateKeysByType($widget->type);
		$settingsView = $widget->renderSettingsTemplate();
		$snippets = (new SnippetCollection())->getHTMLSelectChoices();

		// TODO: добавить загрузку списка ролей
		$usersRoles = [];
		$assetsPackages = Package::getHTMLSelectChoice();

		$this->setContent('widgets.edit', compact('widget', 'commentKeys', 'settingsView', 'snippets', 'assetsPackages', 'usersRoles'));
	}

	public function postEdit($id, WidgetUpdator $updator)
	{
		$data = $this->request->all();

		$validator = $updator->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		// TODO: добавить проверку прав на установку ролей и кеша
		$widget = $updator->update($id, $data);

		return $this->smartRedirect([$widget])
			->with('success', trans('widgets::core.messages.updated', ['name' => $widget->name]));
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
		// TODO: добавить проверку виджета на поврежденность
		try {
			return Widget::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			$this->throwFailException($this->smartRedirect()->withErrors(trans('widgets::core.messages.not_found')));
		}
	}
}