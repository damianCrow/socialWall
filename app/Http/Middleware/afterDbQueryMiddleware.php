<?php

namespace socialwall\Http\Middleware;

use Closure;
use Input;
use Illuminate\Support\Facades\Auth;
use Log;
use socialwall\User;

class afterDbQueryMiddleware
{
    
    public function handle($request, Closure $next) {

        $response = $next($request);

        if($request->method() == 'POST' && $request->decodedPath() == 'user') {

            $newUser = Input::except('password', '_token');
           
           Log::info(Auth::user()['username'], ['new user created' => $newUser]);
        }

        if($request->method() == 'PUT') {

            $updatedUser = User::find($request->segment(2));
            $updatedInfo = Input::except('password', '_token');
           
           Log::info(Auth::user()['username'], ['updated user id' => $updatedUser['id'], 'new details' => $updatedInfo]);
        }

        return $response;
    }
}
