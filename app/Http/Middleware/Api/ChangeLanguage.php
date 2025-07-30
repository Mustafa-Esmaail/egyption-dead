<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class ChangeLanguage
{

    public function handle(Request $request, Closure $next)
    {
        $request->request->remove('_method');

        $lang = $request->header('lang') ?? 'ar';
        if (in_array($lang, ['ar', 'en']))
            app()->setLocale($lang);
        else
            app()->setLocale('ar');

        return $next($request);
    }

}
