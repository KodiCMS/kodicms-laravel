<?php

namespace KodiCMS\CMS\Http\Middleware;

use Closure;
use Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class PostJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/json') and Request::isMethod('post')) {
            $request->request = new ParameterBag(
                json_decode($request->getContent(), true)
            );
        }

        return $next($request);
    }
}
