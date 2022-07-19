<?php

namespace App\Http\Controllers\Auth;
use App\Traits\Response;
use App\Rules\Recaptcha;  
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginController extends Controller
{
    use Response;
    const RECAPTCHA_URL = "https://www.google.com/recaptcha/api/siteverify";
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            "g-recaptcha-response" => 'required'
        ]);
        // function to display an error message
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }
        
        $this->verifyCaptcha($request->input("g-recaptcha-response"));
        
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 'approved',
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
    
    private function verifyCaptcha($grecaptcha) 
    { 
        $response = Http::asForm()->post(self::RECAPTCHA_URL, [ 
            "secret" => config('recaptcha.secret'), 
            "response" => $grecaptcha 
        ])->json(); 

        if (!$response["success"]) { 
            throw new HttpException(500, "CAPTCHA is not valid"); 
        } 
    }        
}
    