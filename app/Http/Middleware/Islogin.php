<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Islogin
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
        if(empty(session()->get('logged_email'))){
            return redirect()->to('/');
        }
       else
       {
        return $next($request);
       }
    }
}
