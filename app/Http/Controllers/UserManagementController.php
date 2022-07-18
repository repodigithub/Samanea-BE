<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

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
            $user = User::orderBy('id', 'DESC')->get();
            return $this->successResponse(new UserCollection($user), 'List User Management');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getInfo, 422 );
        }
    }

    public function approved(Request $request, $id)
    {   
        $user = User::findOrFail($id);
        try {
            if($user) {
                $user->where('status',0)->update(['status' => 1]);
            }
            return $this->successResponse($user, 'User approved successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getInfo, 422 );
        }
    }

    public function rejected(Request $request, $id)
    {
        $user = User::findOrFail($id);
        try {
            if($user) {
                $user->where('status',1)->update(['status' => 0]);
            }
            return $this->successResponse($user, 'User rejected successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('null', 'Failed'. $e->getInfo, 422 );
        }
    }

}
