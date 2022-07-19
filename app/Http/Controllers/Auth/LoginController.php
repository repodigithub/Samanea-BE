<?php

namespace App\Http\Controllers\Auth;
use App\Traits\Response;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    use Response;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login(Request $request,  Recaptcha $recaptcha)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => ['required', $recaptcha],
        ]);
        // function to display an error message
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 'approved'
        ];
         //Create token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse(null, 'Login credentials are invalid.', 401);
            }
        } catch (JWTException $e) {
            return $credentials;
            return $this->errorResponse(null, 'Could not create token.', 500);
        }
        return $this->respondWithToken($token);
        
    }  

}
