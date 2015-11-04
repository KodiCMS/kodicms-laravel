<?php

namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Exceptions\PageNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use KodiCMS\Pages\Http\Controllers\System\FrontPageController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontendController extends FrontPageController
{
    /**
     * @param string $slug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|null
     * @throws PageNotFoundException
     * @throws \KodiCMS\Pages\Exceptions\LayoutNotFoundException
     */
    public function run($slug)
    {
        event('frontend.requested', [$slug]);

        $frontPage = FrontendPage::findByUri($slug);
        $notFoundMessage = trans('pages::core.messages.not_found');

        if ($frontPage instanceof FrontendPage) {
            if ($frontPage->isRedirect() and strlen($frontPage->getRedirectUrl()) > 0) {
                return redirect($frontPage->getRedirectUrl(), 301);
            } else {
                try {
                    event('frontend.found', [$frontPage]);

                    return $this->render($frontPage->getLayoutView(), $frontPage->getMime());
                } catch (NotFoundHttpException $e) {
                    $notFoundMessage = $e->getMessage();
                } catch (HttpException $e) {
                    $notFoundMessage = $e->getMessage();
                }
            }
        }

        if (config('cms.find_similar') and ($uri = FrontendPage::findSimilar($slug)) !== false) {
            return redirect($uri, 301);
        }

        event('frontend.not_found', [$slug]);
        throw new PageNotFoundException($notFoundMessage);
    }
}
