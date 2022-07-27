<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\Upload;
use App\Models\Cluster;
use App\Models\Property;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClusterResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PropertyResource;
use Illuminate\Support\Facades\Validator;


class PropertyController extends Controller
{
    use Response, Upload;
    
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $property = Property::latest()->paginate(request('limit') ?: 15,["*"], "page", request('page') ?: 1);
        return $this->successResponse($property, 'List Property');
    }

    public function show($id)
    {
        $property = Property::find($id);
        if(!$property) {
            return $this->errorResponse(null, 'Property not found', 404);
        } else {
            return $this->successResponse(new PropertyResource($property), 'Show Property');
        }
    }

    public function cluster()
    {
        $cluster = Cluster::all();
        return $this->successResponse(ClusterResource::collection($cluster), 'List Cluster');
    }

    public function store(Request $request)
    {
        $rules = self::validation($request->all());
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        // function upload image multiple
        if($request->hasfile('slider')) 
        {
            foreach($request->file('slider') as $file)
            {
                $name=$file->getClientOriginalName();    
                $file->move('assets/sliders/', $name);      
                $data[] = $name;  
            }
        }

        $foto_overview = $this->uploadImage($request, 'public/overview/', 'foto_overview');
        $icon = $this->uploadImage($request, 'public/icon/', 'icon');
        $foto_arsitek = $this->uploadImage($request, 'public/arsitek/', 'foto_arsitek');
        $foto_masterplan = $this->uploadImage($request, 'public/masterplan/', 'foto_masterplan');
        $foto_fasilitas = $this->uploadImage($request, 'public/fasilitas/', 'foto_fasilitas');
        $foto_unit = $this->uploadImage($request, 'public/unit/', 'foto_unit');
        $galery_unit = $this->uploadImage($request, 'public/galery_unit/', 'galery_unit');
        $foto_footer = $this->uploadImage($request, 'public/footer/', 'foto_footer');

        DB::beginTransaction();
        try {
            $property = Property::create([
                'judul_project' => $request->get('judul_project'),
                'slider' => json_encode($data),
                'foto_overview' => $foto_overview->hashName(),
                'judul_overview' => $request->get('judul_overview'),
                'deskripsi_overview' => $request->get('deskripsi_overview'),
                'icon' => $icon->hashName(),
                'judul_icon' => $request->get('judul_icon'),
                'deskripsi_icon' => $request->get('deskripsi_icon'),
                'foto_arsitek' => $foto_arsitek->hashName(),
                'judul_arsitek' => $request->get('judul_arsitek'),
                'deskripsi_arsitek' => $request->get('deskripsi_arsitek'),
                'foto_fasilitas' => $foto_fasilitas->hashName(),
                'judul_fasilitas' => $request->get('judul_fasilitas'),
                'deskiprsi_fasilitas' => $request->get('deskiprsi_fasilitas'),
                'link' => $request->get('link'),
                'foto_masterplan' => $foto_masterplan->hashName(),
                'judul_masterplan' => $request->get('judul_masterplan'),
                'foto_unit' => $foto_unit->hashName(),
                'nama_unit' => $request->get('nama_unit'),
                'cluster_id' => $request->get('cluster_id'),
                'deskripsi_unit' => $request->get('deskripsi_unit'),
                'luas_bangunan' => $request->get('luas_bangunan'),
                'luas_tanah' => $request->get('luas_tanah'),
                'kamar_tidur' => $request->get('kamar_tidur'),
                'karpot' => $request->get('karpot'),
                'kamar_mandi' => $request->get('kamar_mandi'),
                'galery_unit' => $galery_unit->hashName(),
                'foto_footer' => $foto_footer->hashName(),
                'deskripsi_footer' => $request->get('deskripsi_footer'),
                'alamat_footer' => $request->get('alamat_footer'),
                'link_maps' => $request->get('link_maps'),
                'sosmed' => $request->get('sosmed'),
                'link_sosmed' => $request->get('link_sosmed'),
            ]);
            DB::commit();
            return $this->successResponse(new PropertyResource($property), 'Property successfully created');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse('null', 'There is something wrong'. $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {

        $rules = self::validation($request->all());
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        $property = Property::find($id);
        if(!$property) {
            return $this->errorResponse(null, 'Property not found', 404);
        }
        // function upload image multiple
        if($request->hasfile('slider')) 
        {
            foreach($request->file('slider') as $file)
            {
                $name=$file->getClientOriginalName();    
                $file->move('assets/sliders/', $name);      
                $data[] = $name;  
            }
        }
        Storage::disk('local')->delete('assets/sliders/'. basename($property->slider));
            
        $foto_overview = $this->uploadImage($request, 'public/overview/', 'foto_overview');
        $icon = $this->uploadImage($request, 'public/icon/', 'icon');
        $foto_arsitek = $this->uploadImage($request, 'public/arsitek/', 'foto_arsitek');
        $foto_masterplan = $this->uploadImage($request, 'public/masterplan/', 'foto_masterplan');
        $foto_fasilitas = $this->uploadImage($request, 'public/fasilitas/', 'foto_fasilitas');
        $foto_unit = $this->uploadImage($request, 'public/unit/', 'foto_unit');
        $galery_unit = $this->uploadImage($request, 'public/galery_unit/', 'galery_unit');
        $foto_footer = $this->uploadImage($request, 'public/footer/', 'foto_footer');

        Storage::disk('local')->delete('public/overview/'. basename($property->foto_overview));
        Storage::disk('local')->delete('public/icon/'. basename($property->icon));
        Storage::disk('local')->delete('public/arsitek/'. basename($property->foto_arsitek));
        Storage::disk('local')->delete('public/masterplan/'. basename($property->foto_masterplan));
        Storage::disk('local')->delete('public/fasilitas/'. basename($property->foto_fasilitas));
        Storage::disk('local')->delete('public/unit/'. basename($property->foto_unit));
        Storage::disk('local')->delete('public/galery_unit/'. basename($property->galery_unit));
        Storage::disk('local')->delete('public/footer/'. basename($property->foto_footer));

        DB::beginTransaction();
        try {
            $property->update([
                'judul_project' => $request->get('judul_project'),
                'slider' => json_encode($data),
                'foto_overview' => $foto_overview->hashName(),
                'judul_overview' => $request->get('judul_overview'),
                'deskripsi_overview' => $request->get('deskripsi_overview'),
                'icon' => $icon->hashName(),
                'judul_icon' => $request->get('judul_icon'),
                'deskripsi_icon' => $request->get('deskripsi_icon'),
                'foto_arsitek' => $foto_arsitek->hashName(),
                'judul_arsitek' => $request->get('judul_arsitek'),
                'deskripsi_arsitek' => $request->get('deskripsi_arsitek'),
                'foto_fasilitas' => $foto_fasilitas->hashName(),
                'judul_fasilitas' => $request->get('judul_fasilitas'),
                'deskiprsi_fasilitas' => $request->get('deskiprsi_fasilitas'),
                'link' => $request->get('link'),
                'foto_masterplan' => $foto_masterplan->hashName(),
                'judul_masterplan' => $request->get('judul_masterplan'),
                'foto_unit' => $foto_unit->hashName(),
                'nama_unit' => $request->get('nama_unit'),
                'cluster_id' => $request->get('cluster_id'),
                'deskripsi_unit' => $request->get('deskripsi_unit'),
                'luas_bangunan' => $request->get('luas_bangunan'),
                'luas_tanah' => $request->get('luas_tanah'),
                'kamar_tidur' => $request->get('kamar_tidur'),
                'karpot' => $request->get('karpot'),
                'kamar_mandi' => $request->get('kamar_mandi'),
                'galery_unit' => $galery_unit->hashName(),
                'foto_footer' => $foto_footer->hashName(),
                'deskripsi_footer' => $request->get('deskripsi_footer'),
                'alamat_footer' => $request->get('alamat_footer'),
                'link_maps' => $request->get('link_maps'),
                'sosmed' => $request->get('sosmed'),
                'link_sosmed' => $request->get('link_sosmed'),
            ]);
            DB::commit();
            return $this->successResponse(new PropertyResource($property), 'Property successfully updated');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse('null', 'There is something wrong'. $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $property = Property::find($id);
        if(!$property) {
            return $this->errorResponse(null, 'Property not found', 404);
        } else {
            //delete image old
            Storage::delete('assets/sliders/'.$property->slider);
            Storage::disk('local')->delete('public/overview/'. basename($property->foto_overview));
            Storage::disk('local')->delete('public/icon/'. basename($property->icon));
            Storage::disk('local')->delete('public/arsitek/'. basename($property->foto_arsitek));
            Storage::disk('local')->delete('public/masterplan/'. basename($property->foto_masterplan));
            Storage::disk('local')->delete('public/fasilitas/'. basename($property->foto_fasilitas));
            Storage::disk('local')->delete('public/unit/'. basename($property->foto_unit));
            Storage::disk('local')->delete('public/galery_unit/'. basename($property->galery_unit));
            Storage::disk('local')->delete('public/footer/'. basename($property->foto_footer));
            // delete property
            $property->delete();
            return $this->successResponse(new PropertyResource($property), 'Property successfully deleted');
        }
    }



    protected function validation() {
        return [
            'judul_project' => 'required',
            'slider' => 'required',
            'slider.*' => 'image|mimes:jpeg,png,jpg,gif',
            'foto_overview' => 'required|image|mimes:jpeg,png,jpg,gif',
            'judul_overview' => 'required',
            'deskripsi_overview' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif',
            'judul_icon' => 'required',
            'deskripsi_icon' => 'required',
            'foto_arsitek' => 'required|image|mimes:jpeg,png,jpg,gif',
            'judul_arsitek' => 'required',
            'deskripsi_arsitek' => 'required',
            'foto_fasilitas' => 'required|image|mimes:jpeg,png,jpg,gif',
            'judul_fasilitas' => 'required',
            'deskiprsi_fasilitas' => 'required',
            'link' => 'required',
            'foto_masterplan' => 'required|image|mimes:jpeg,png,jpg,gif',
            'judul_masterplan' => 'required',
            'foto_unit' => 'required|image|mimes:jpeg,png,jpg,gif',
            'nama_unit' => 'required',
            'cluster_id' => 'required',
            'deskripsi_unit' => 'required',
            'luas_bangunan' => 'required',
            'luas_tanah' => 'required',
            'kamar_tidur' => 'required|numeric',
            'karpot' => 'required',
            'kamar_mandi' => 'required|numeric',
            'galery_unit' => 'required|image|mimes:jpeg,png,jpg,gif',
            'foto_footer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi_footer' => 'required',
            'alamat_footer' => 'required',
            'link_maps' => 'required',
            'sosmed' => 'required|in:facebook,twitter,ig',
            'link_sosmed' => 'required'
        ];
    }
}

