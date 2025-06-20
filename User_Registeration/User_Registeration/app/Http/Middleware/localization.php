<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\URL;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale($request->segment(1));
        URL::defaults(['locale' => $request->segment(1)]);
        return $next($request);
    }
}
