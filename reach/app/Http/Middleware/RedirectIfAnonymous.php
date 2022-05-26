<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class RedirectIfAnonymous
{
    public function handle(Request $req, Closure $next)
    {
        if(!Session()->has('loginId'))
        {
            return redirect('login');
        }

        return $next($req);
    }
}
