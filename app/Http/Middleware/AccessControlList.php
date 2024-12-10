<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;   //ALC

class AccessControlList
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    use AuthorizesRequests;

    public function handle(Request $request, Closure $next): Response
    {

        //ALC
        $routeName = $request->route()->getName();
        // dd($routeName);
        $this->authorize($routeName);

        return $next($request);
    }
}
