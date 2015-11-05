<?php

namespace KodiCMS\CMS\Http\Controllers;

use Exception;

class ErrorController extends System\FrontendController
{
    /**
     * @param Exception $e
     */
    public function error500(Exception $e)
    {
        $this->render($e, 500, $e->getMessage());
    }

    /**
     * @param Exception $e
     */
    public function error404(Exception $e)
    {
        $this->render($e, 404, trans($this->wrapNamespace('core.messages.route_not_found')));
    }

    /**
     * @param Exception  $e
     * @param int         $code
     * @param string|null $message
     */
    protected function render(Exception $e, $code, $message = null)
    {
        $this->setContent('errors.default', [
            'message' => ! is_null($message) ? $message : $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
            'code'    => $code,
            'error'   => get_class($e),
            'debug'   => config('app.debug'),
        ]);
    }
}
