<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProfileJsonResponse
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
        $response = $next($request);

        // check if debugbar is enabled
        if (! app()->bound('debugbar') || ! app('debugbar')->isEnabled()) {
            return $response;
        }

        // profile the json response
        if ($response instanceof JsonResponse && $request->has('_debug')) {
            /*$response->setData(array_merge($response->getData(true), [
                'debugbar' => app('debugbar')->getData(true)
            ]));*/
            $response->setData(array_merge($response->getData(true), [
                'debugbar' => Arr::only(app('debugbar')->getData(), 'queries')
            ]));
        }
        return $response;
     }
}
