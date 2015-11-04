<?php

namespace KodiCMS\Pages\Http\Controllers;

use Meta;
use Block;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Helpers\BlockWysiwyg;
use KodiCMS\Widgets\Collection\PageWidgetCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;

class PageWysiwygController extends TemplateController
{
    /**
     * @var bool
     */
    protected $authRequired = true;

    /**
     * @param int $id
     *
     * @return string
     */
    public function getPageWysiwyg($id)
    {
        $frontendPage = $this->getPage($id);
        $this->templateScripts['PAGE'] = $frontendPage;

        Meta::addMeta([
                'name'    => 'page-id',
                'data-id' => $id,
                'name'    => 'csrf-token',
                'content' => csrf_token(),
            ])
            ->loadPackage(['page-wysiwyg'], true)
            ->addToGroup('site-url', '<script type="text/javascript">'.$this->getTemplateScriptsAsString().'</script>');

        app()->singleton('frontpage', function () use ($frontendPage) {
            return $frontendPage;
        });

        app()->singleton('layout.widgets', function () use ($frontendPage) {
            return new PageWidgetCollection($frontendPage->getId());
        });

        app()->singleton('layout.block', function () use ($frontendPage) {
            return new BlockWysiwyg(app('layout.widgets'), $frontendPage);
        });

        if (is_null($layout = $frontendPage->getLayoutView())) {
            return trans('pages::core.messages.layout_not_set');
        }

        $html = $layout->with('page', $frontendPage)->render();

        $injectHTML = view('pages::pages.wysiwyg.system_blocks');
        $matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if (count($matches) > 1) {
            $html = $matches[0].$injectHTML->render().$matches[1].$matches[2];
        }

        return $html;
    }

    /**
     * @param int $id
     *
     * @return bool|FrontendPage
     */
    protected function getPage($id)
    {
        try {
            return FrontendPage::findById($id, [
                FrontendPage::STATUS_HIDDEN,
                FrontendPage::STATUS_DRAFT,
            ]);
        } catch (ModelNotFoundException $e) {
            $this->throwFailException(
                $this->smartRedirect()->withErrors(trans('pages::core.messages.not_found'))
            );
        }
    }
}
