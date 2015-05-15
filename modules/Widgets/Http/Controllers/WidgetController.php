<?php namespace KodiCMS\Widgets\Http\Controllers;

use Assets;
use DB;
use Illuminate\View\View;
use KodiCMS\Widgets\Engine\WidgetRenderSettingsHTML;
use WYSIWYG;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
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

	public function getPopupList($pageId)
	{
		intval($pageId);

		$query = DB::table('page_widgets')->select('widget_id');

		if($pageId > 0)
		{
			$query->where('page_id', $pageId);
		}

		$ids = $query->lists('widget_id');

		$widgetList = (new Widget)->newQuery();

		if(count($ids) > 0)
		{
			$widgetList->whereNotIn('id', $ids);
		}

		$widgets = [];

		foreach($widgetList->get() as $widget)
		{
			if($widget->isCorrupt() or $widget->isHandler()) continue;

			$widgets[$widget->getType()][$widget->id] = $widget;
		}

		return $this->setContent('widgets.page.ajax_list', compact('widgets'));
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

	public function getEdit($id)
	{
		$widget = $this->getWidget($id);

		$this->breadcrumbs->add($widget->getType());

		$this->setTitle(trans('widgets::core.title.edit', [
			'name' => $widget->name
		]));

		$settingsView = (new WidgetRenderSettingsHTML($widget->toWidget()))->render();
		$this->setContent('widgets.edit', compact('widget', 'settingsView', 'assetsPackages', 'usersRoles'));
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

	public function getLocation($id)
	{
		$widget = $this->getWidget($id);
		list($widgetBlocks, $blocksToExclude) = $widget->getLocations();

		$pages = PageSitemap::get(true)->asArray();

		$this->breadcrumbs
			->add($widget->getType())
			->add($widget->name, route('backend.widget.edit', [$widget]));

		$this->setTitle(trans('widgets::core.title.location', [
			'name' => $widget->name
		]));

		$layoutBlocks = (new LayoutBlock)->getBlocksGroupedByLayouts();

		$this->setContent('widgets.location', compact('widget', 'pages', 'widgetBlocks', 'blocksToExclude', 'layoutBlocks'));
	}

	public function postLocation($id)
	{
		$widget = $this->getWidget($id);
		WidgetManagerDatabase::placeWidgetsOnPages($id, $this->request->input('blocks', []));
		return back();
	}

	public function getTemplate($id)
	{
		$widget = $this->getWidget($id);
		WYSIWYG::loadAll(WYSIWYG::TYPE_CODE);

		$template = $widget->getDefaultFrontendTemplate();

		$content = null;
		if (!($template instanceof View))
		{
			$template = view($template);
		}

		$content = file_get_contents($template->getPath());

		$this->setContent('widgets.template', compact('content'));
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