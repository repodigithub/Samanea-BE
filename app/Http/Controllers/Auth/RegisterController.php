<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Traits\Response;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use Response;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    public function register(Request $request, Recaptcha $recaptcha)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|min:6|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'telphone' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/', 'min:11'],
            'level' => 'required',
            'recaptcha' => ['required', $recaptcha],
        ]);
        if($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        $user = User::create([
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'telphone' => $request->get('telphone'),
            'level' => $request->get('level'), 
        ]);
        return $this->successResponse($user, 'Successfully Registered');
    }  
}
