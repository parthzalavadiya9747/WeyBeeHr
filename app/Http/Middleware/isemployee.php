<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class isemployee
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
        if(session()->get('logged_role') == 'Employee'){
            return $next($request);
        }
        else
        {
            return redirect()->to('/');
        }
    }
}
