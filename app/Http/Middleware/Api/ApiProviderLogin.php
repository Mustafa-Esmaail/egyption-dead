<?php

namespace App\Http\Middleware\Api;

use App\Http\Traits\Api_Trait;
use Closure;
use Exception;
use JWTAuth;
use Illuminate\Http\Request;

class ApiProviderLogin
{
    use Api_Trait;


    public function handle(Request $request, Closure $next)
    {
        try {

            $provider = auth('provider')->user();

            if (!$provider) {
                return     $this->returnError([helperTrans('api.Unauthorized')],401);
            }
            if ($provider->status=='pending'){
                return $this->returnError(['api.Waiting for approval']);
            }
        } catch (Exception $e) {
            return     $this->returnError([helperTrans('api.Token is invalid or expired')],401);

        }

        return $next($request);
    }
}
