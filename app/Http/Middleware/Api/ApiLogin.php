<?php

namespace App\Http\Middleware\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Api_Trait;
use Closure;
use Exception;
use JWTAuth;
use Illuminate\Http\Request;

class ApiLogin extends Controller
{
    use Api_Trait;

    public function handle(Request $request, Closure $next)
    {
        try {

            $user = auth('user')->user();

            if (!$user) {
           return     $this->returnError([helperTrans('api.Unauthorized')],401);
            }

            if ($user->is_active==0){
                return     $this->returnError([helperTrans('api.This User Is Blocked')],401);

            }

        } catch (Exception $e) {
            return     $this->returnError([helperTrans('api.Token is invalid or expired')],401);

        }

        return $next($request);
    }
}
