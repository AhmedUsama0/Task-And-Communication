<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);
        $locales = config('app.locales');
        $uri = $request->getRequestUri();

        //  check if the request URI contains liverwire or broadcasting
        if (str_contains($uri, 'livewire') || str_contains($uri, 'broadcasting') || str_contains($uri, 'horizon')) {
            return $next($request);
        }

        if (! in_array($locale, $locales)) {
            $defaultLocale = config('app.fallback_locale');
            $requestUri = $uri == '/' ? "$defaultLocale/" : $defaultLocale.$uri;

            return redirect($requestUri);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
