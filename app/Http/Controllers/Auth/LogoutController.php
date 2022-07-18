<?php

namespace App\Http\Controllers\Auth;
use App\Traits\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    use Response;

    public function logout() {
        try {
            $user = JWTAuth::invalidate(JWTAuth::getToken());
            return $this->successResponse($user, 'User has been logged out');
        } catch (JWTException $exception) {
            return $this->errorResponse(null, 'Sorry, user cannot be logged out', 500);
        }
    }
}
