<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $req, Closure $next)
    {
        if(Session()->has('user') )
        {
            return back();
        }

        return $next($req);
    }
}
