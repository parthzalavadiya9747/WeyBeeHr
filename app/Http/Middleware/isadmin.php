<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class isadmin
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
        if(session()->get('logged_role') == 'Admin'){
            return $next($request);
        }
        else
        {
            session()->flush();
            return redirect()->to('/');
        }
    }
}
