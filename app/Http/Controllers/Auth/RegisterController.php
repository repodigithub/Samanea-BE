<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
        $this->middleware('auth:api', ['except' => ['register', 'teamLeader', 'supervisor']]);
    }

    public function teamLeader()
    {
        $team_leader = User::whereLevel('team_leader')->get();
        return $this->successResponse(UserResource::collection($team_leader), 'List Team Leader');
    }

    public function supervisor()
    {
        $supervisor = User::whereLevel('supervisor')->get();
        return $this->successResponse(UserResource::collection($supervisor), 'List Supervisor');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|min:6|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'telphone' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/', 'min:11'],
            'level' => 'required',
            'team_leader' => 'nullable',
            'supervisor' => 'nullable',
        ]);
        if($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        $supervisor = User::whereLevel('supervisor')->first();
        $team_leader = User::whereLevel('team_leader')->first();

        $user = User::create([
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'telphone' => $request->get('telphone'),
            'level' => $request->get('level'), 
            'status' => 'rejected',
            'team_leader' => $team_leader->id,
            'supervisor' => $supervisor->id,
        ]);
        return $this->successResponse($user, 'Successfully Registered');
    }  
}
