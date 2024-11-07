<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // rules admin
        if(!$request->session()->has('admin')){
            return redirect('admin/login');
        }

        // error_log('cekAdmin');
        // return $next($request);

        $response = $next($request);
        return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

    }
}
