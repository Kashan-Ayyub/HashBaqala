<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class SuperAdmin extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (auth()->user()->user_type == '1') {
            return $next($request);
        } elseif(auth()->user()->user_type == '2') {
            return redirect('/login');     
        }
        else{
            return redirect('/login');     
        }
        
    }
    
    
    
}
