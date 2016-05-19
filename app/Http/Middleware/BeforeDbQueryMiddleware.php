<?php

namespace socialwall\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Log;
use socialwall\User;

class BeforeDbQueryMiddleware  {
    
    public function handle($request, Closure $next) {

       if($request->method() === "DELETE") {

           $deletedUser = User::find($request->segment(2));
           
           Log::info(Auth::user()['username'], ['deleted user ' => [$deletedUser['id'], $deletedUser['username'], $deletedUser['email']]]);
        }

        return $next($request);
    }

}
