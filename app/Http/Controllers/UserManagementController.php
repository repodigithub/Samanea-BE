<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    use Response;
    
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        try {
            $user = User::whereStatus('approved')->orderBy('id', 'DESC')->filterByName(request('search'))->paginate(request('limit') ?: 15,["*"], "page", request('page') ?: 1);
            return $this->successResponse($user, 'List User Management');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getMessage(), 422 );
        }
    }
    
    /**
    * Displays a list of resources by wait approval status.
    *
    * @return \Illuminate\Http\Response
    */
    public function userRequest()
    {
        try {
            $user = User::whereStatus('wait_approval')->orderBy('id', 'DESC')->filterByName(request('search'))->paginate(request('limit') ?: 15,["*"], "page", request('page') ?: 1);
            return $this->successResponse($user, 'List User Request Management');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getMessage(), 422 );
        }
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $user = User::find($id);
        if(!$user) {
            return $this->errorResponse(null, 'User not found', 404);
        } else {
            return $this->successResponse(new UserResource($user), 'Show User Management');
        }
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //Validate data
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|min:6|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'telphone' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/', 'min:11'],
            'level' => 'required',
            'team_leader' => 'nullable',
            'supervisor' => 'nullable',
        ]);
        //Send failed response if request is not valid
        if($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }
        //Request is valid, create new user
        $user = User::create([
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'telphone' => $request->get('telphone'),
            'level' => $request->get('level'),
            'status' => $request->get('level') == 'manager' ? 'approved' : 'wait_approval',
            'team_leader' => $request->get('team_leader'),
            'supervisor' => $request->get('supervisor'), 
        ]);
        //user store, return success response
        return $this->successResponse(new UserResource($user), 'User created successfully');
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        // check id is there or not 
        $user = User::find($id);
        if(!$user) {
            return $this->errorResponse(null, 'User not found', 404);
        }
        //Validate data
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|min:6|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'required|confirmed|min:8',
            'telphone' => ['required', 'regex:/^(^\+62|62|^08)(\d{3,4}-?){2}\d{3,4}$/', 'min:11'],
            'level' => 'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }
        
        //Request is valid, update user
        $user->update([
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'telphone' => $request->get('telphone'),
            'level' => $request->get('level'),
            'team_leader' => $request->get('team_leader'),
            'supervisor' => $request->get('supervisor'), 
        ]);
        
        //user updated, return success response
        return $this->successResponse(new UserResource($user), 'User updated successfully');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user) {
            return $this->errorResponse(null, 'User not found', 404);
        } else {
            $user->delete();
            return $this->successResponse(new UserResource($user), 'User deleted successfully');
        }
    }
    
    public function action(Request $request)
    {
         //Validate data
        $validator = Validator::make($request->all(), [
            'id' => 'required|array',
            // 'status' => 'required|in:approved,rejected',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }
        
        $users = [];
        foreach ($request->input('id') as $id) {
            $users[] = User::find($id);
        }
        // update data multi rows
        $user = User::whereIn('id', $request->input('id'))->update([
            'status' => $request->input('status')
        ]);
        return $this->successResponse($user, 'User Action successfully');
    }
}
                        