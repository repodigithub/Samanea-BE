<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserManagementController extends Controller
{
    use Response;
    
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    public function index()
    {
        try {
            $user = User::whereStatus('approved')->orderBy('id', 'DESC')->get();
            return $this->successResponse(UserResource::collection($user), 'List User Management');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getInfo, 422 );
        }
    }

    public function userRequest()
    {
        try {
            $user = User::whereStatus('wait_approval')->orderBy('id', 'DESC')->get();
            return $this->successResponse(UserResource::collection($user), 'List User Request Management');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getInfo, 422 );
        }
    }

    public function approved($id)
    {   
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('null', 'User Not Found', 404 );
        } else {
            $user->update(['status' => 'approved']);
            return $this->successResponse($user, 'User approved successfully');
        }
    }

    public function rejected($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('null', 'User Not Found', 404 );
        } else {
            $user->update(['status' => 'rejected']);
            return $this->successResponse($user, 'User rejected successfully');
        }
    }
}
