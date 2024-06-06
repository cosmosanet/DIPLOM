<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class CheckSessionData
{

     /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('name')) {
            //return redirect('https://oauth.yandex.ru/authorize?response_type=code&client_id=fbfc3c48acd74430842651ca8a93e385');
            return redirect('/loginPage');
        }

        return $next($request);
    }
}