<?php

namespace App\Http\Controllers;
use App\Models\Cluster;
use App\Traits\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClusterResource;
use Illuminate\Support\Facades\Validator;


class ClusterController extends Controller
{
    use Response;
    
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    } 

    public function index()
    {
        $cluster = Cluster::latest()->paginate(request('limit') ?: 15,["*"], "page", request('page') ?: 1);
        return $this->successResponse($cluster, 'List Cluster Property');
    }

    public function show($id)
    {
        $cluster = Cluster::find($id);
        if(!$cluster) {
            return $this->errorResponse(null, 'Cluster not found', 404);
        } else {
            return $this->successResponse(new ClusterResource($cluster), 'Show Cluster Property');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6|max:255',  
        ]);
        //Send failed response if request is not valid
        if($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }
        //Request is valid, create new user
        $cluster = Cluster::create([
            'name' => $request->get('name'),
        ]);
        //user store, return success response
        return $this->successResponse(new ClusterResource($cluster), 'Cluster created successfully');
    }
    
    public function update(Request $request, $id)
    {
        // check id is there or not 
        $cluster = Cluster::find($id);
        if(!$cluster) {
            return $this->errorResponse(null, 'Cluster not found', 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6|max:255',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }
        
        $cluster->update([
            'name' => $request->get('name'),
        ]);
    
        return $this->successResponse(new ClusterResource($cluster), 'Cluster updated successfully');
    }

    public function destroy($id)
    {
        $cluster = Cluster::find($id);
        if(!$cluster) {
            return $this->errorResponse(null, 'Cluster not found', 404);
        } else {
            $cluster->delete();
            return $this->successResponse(new ClusterResource($cluster), 'Cluster deleted successfully');
        }
    }    
}
