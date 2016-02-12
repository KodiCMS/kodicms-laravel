<?php

namespace KodiCMS\Widgets\Http\Controllers;

use Meta;
use WYSIWYG;
use Illuminate\View\View;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Repository\PageRepository;
use KodiCMS\Widgets\Repository\WidgetRepository;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\Widgets\Engine\WidgetRenderSettingsHTML;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class WidgetController extends BackendController
{

    /**
     * @param WidgetRepository $repository
     * @param string           $type
     */
    public function getIndex(WidgetRepository $repository, $type = null)
    {
        Meta::loadPackage('editable');

        $query = $repository->getModel()->newQuery();

        foreach (WidgetManagerDatabase::getAvailableTypes() as $group => $types) {
            if (isset($types[$type])) {
                $this->breadcrumbs->add($types[$type]);
            }
        }

        if (! is_null($type)) {
            $query->where('type', $type);
        }

        $widgets = $query->paginate();

        $this->setContent('widgets.list', compact('widgets'));
    }

    /**
     * @param WidgetRepository $repository
     * @param int          $pageId
     *
     * @return \View
     */
    public function getPopupList(WidgetRepository $repository, $pageId)
    {
        $widgets = $repository->getByPageId($pageId);

        return $this->setContent('widgets.page.ajax_list', compact('widgets'));
    }

    public function getCreate()
    {
        $this->setTitle(trans($this->wrapNamespace('core.title.create')));

        $types = WidgetManagerDatabase::getAvailableTypes();

        $this->setContent('widgets.create', compact('types'));
    }

    /**
     * @param WidgetRepository $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(WidgetRepository $repository)
    {
        $data = $this->request->all();

        $repository->validateOnCreate($data);
        $widget = $repository->create($data);

        return $this->smartRedirect([$widget])
            ->with('success', trans($this->wrapNamespace('core.messages.created'), [
                'name' => $widget->name,
            ]));
    }

    /**
     * @param WidgetRepository $repository
     * @param int          $id
     */
    public function getEdit(WidgetRepository $repository, $id)
    {
        $widget = $repository->findOrFail($id);
        $this->breadcrumbs->add($widget->getType(), route('backend.widget.list.by_type', ['type' => $widget->type]));

        $this->setTitle(trans($this->wrapNamespace('core.title.edit'), [
            'name' => $widget->getName(),
        ]));

        $settingsView = (new WidgetRenderSettingsHTML($widget->toWidget()))->render();
        $this->setContent('widgets.edit', compact('widget', 'settingsView', 'usersRoles'));
    }

    /**
     * @param WidgetRepository $repository
     * @param int          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(WidgetRepository $repository, $id)
    {
        $data = $this->request->all();

        $repository->validateOnUpdate($data);
        $widget = $repository->update($id, $data);

        return $this->smartRedirect([$widget])
            ->with('success', trans($this->wrapNamespace('core.messages.updated'), [
                'name' => $widget->name,
            ]));
    }

    /**
     * @param WidgetRepository $repository
     * @param int          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(WidgetRepository $repository, $id)
    {
        $widget = $repository->delete($id);

        return $this->smartRedirect()
            ->with('success', trans($this->wrapNamespace('core.messages.deleted'), [
                'name' => $widget->name,
            ]));
    }

    /**
     * @param WidgetRepository $repository
     * @param PageRepository   $pageRepository
     * @param int          $id
     */
    public function getLocation(WidgetRepository $repository, PageRepository $pageRepository, $id)
    {
        $widget = $repository->findOrFail($id);
        list($widgetBlocks, $blocksToExclude) = $widget->getLocations();

        $pages = $pageRepository->getSitemap(true);

        $this->breadcrumbs
            ->add($widget->getType())
            ->add($widget->name, route('backend.widget.edit', [$widget]));

        $this->setTitle(trans($this->wrapNamespace('core.title.location'), [
            'name' => $widget->name,
        ]));

        $layoutBlocks = (new LayoutBlock)->getBlocksGroupedByLayouts();
        $this->setContent(
            'widgets.location', compact('widget', 'pages', 'widgetBlocks', 'blocksToExclude', 'layoutBlocks')
        );
    }

    /**
     * @param WidgetRepository $repository
     * @param int          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLocation(WidgetRepository $repository, $id)
    {
        $repository->findOrFail($id);
        WidgetManagerDatabase::placeWidgetsOnPages($id, $this->request->input('blocks', []));

        return back();
    }

    /**
     * @param WidgetRepository $repository
     * @param int          $id
     */
    public function getTemplate(WidgetRepository $repository, $id)
    {
        $widget = $repository->findOrFail($id);
        WYSIWYG::loadDefaultCodeEditor();

        $template = $widget->getDefaultFrontendTemplate();

        $content = null;
        if (! ($template instanceof View)) {
            $template = view($template);
        }

        $content = file_get_contents($template->getPath());
        $this->setContent('widgets.template', compact('content'));
    }
}
