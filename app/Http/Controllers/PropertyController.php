<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Traits\Upload;
use App\Models\Cluster;
use App\Models\Property;
use App\Traits\Response;
use Illuminate\Http\Request;
use App\Models\FasilitasPublik;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClusterResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PropertyResource;
use App\Models\BuldFasilitas;
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
        $property = Property::latest()->paginate(request('limit') ?: 15, ["*"], "page", request('page') ?: 1);
        return $this->successResponse($property, 'List Property');
    }

    public function show($id)
    {
        $property = Property::find($id);
        if (!$property) {
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
        $rules = self::validationStore($request->all());
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        // function upload image multiple
        if ($request->hasfile('slider')) {
            foreach ($request->file('slider') as $file) {
                $name = str_replace(' ', '_', $file->getClientOriginalName());
                $file_path = implode("/", ['slider']);
                $file->move(storage_path('/app/public/' . $file_path), $name);
                $data[] = "/storage/${file_path}/{$name}";
            }
        }

        $foto_overview = $this->uploadImage($request, 'overview', 'foto_overview');
        $icon = $this->uploadImage($request, 'icon', 'icon');
        $foto_arsitek = $this->uploadImage($request, 'arsitek', 'foto_arsitek');
        $foto_masterplan = $this->uploadImage($request, 'masterplan', 'foto_masterplan');
        $foto_fasilitas = $this->uploadImage($request, 'fasilitas', 'foto_fasilitas');
        $foto_unit = $this->uploadImage($request, 'unit', 'foto_unit');
        $galery_unit = $this->uploadImage($request, 'galery_unit', 'galery_unit');
        $foto_fasilitas_publik = $this->uploadImage($request, 'foto_fasilitas_publik', 'foto_fasilitas_publik');
        $foto_footer = $this->uploadImage($request, 'footer', 'foto_footer');

        DB::beginTransaction();
        try {

            $unit = Unit::create([
                'foto_unit' => $foto_unit,
                'nama_unit' => $request->get('nama_unit'),
                'cluster_id' => $request->get('cluster_id'),
                'deskripsi_unit' => $request->get('deskripsi_unit'),
                'luas_bangunan' => $request->get('luas_bangunan'),
                'luas_tanah' => $request->get('luas_tanah'),
                'kamar_tidur' => $request->get('kamar_tidur'),
                'karpot' => $request->get('karpot'),
                'kamar_mandi' => $request->get('kamar_mandi'),
                'galery_unit' => $galery_unit,
            ]);

            $fasilitas_publik = FasilitasPublik::create([
                'foto_fasilitas_publik' => $foto_fasilitas_publik,
                'judul_fasilitas_publik' => $request->get('judul_fasilitas_publik'),
            ]);

            $buld_fasilitas = BuldFasilitas::create([
                'icon' => $icon,
                'judul_icon' => $request->get('judul_icon'),
                'deskripsi_icon' => $request->get('deskripsi_icon'),
            ]);

            $property = Property::create([
                'judul_project' => $request->get('judul_project'),
                'slider' => json_encode($data),
                'foto_overview' => $foto_overview,
                'judul_overview' => $request->get('judul_overview'),
                'deskripsi_overview' => $request->get('deskripsi_overview'),
                'foto_arsitek' => $foto_arsitek,
                'judul_arsitek' => $request->get('judul_arsitek'),
                'deskripsi_arsitek' => $request->get('deskripsi_arsitek'),
                'foto_fasilitas' => $foto_fasilitas,
                'judul_fasilitas' => $request->get('judul_fasilitas'),
                'deskripsi_fasilitas' => $request->get('deskripsi_fasilitas'),
                'link_fasilitas' => $request->get('link_fasilitas'),
                'foto_masterplan' => $foto_masterplan,
                'judul_masterplan' => $request->get('judul_masterplan'),
                'foto_footer' => $foto_footer,
                'deskripsi_footer' => $request->get('deskripsi_footer'),
                'alamat_footer' => $request->get('alamat_footer'),
                'link_maps' => $request->get('link_maps'),
                'sosmed' => $request->get('sosmed'),
                'link_sosmed' => $request->get('link_sosmed'),
                'unit_id' =>  $request->get('unit_id'),
                'buld_fasilitas_id' =>  $request->get('buld_fasilitas_id'),
                'fasilitas_publik_id' => $request->get('fasilitas_publik_id'),
            ]);
            DB::commit();
            return $this->successResponse([$property, $buld_fasilitas, $fasilitas_publik, $unit], 'Property successfully created');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse('null', 'There is something wrong' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {

        $rules = self::validationUpdate($request->all());
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        $unit = Unit::find($id);
        $fasilitas_publik = FasilitasPublik::find($id);
        $buld_fasilitas = BuldFasilitas::find($id);
        $property = Property::find($id);
        if (!$property) {
            return $this->errorResponse(null, 'Property not found', 404);
        }
        // function upload image multiple
        if ($request->hasfile('slider')) {
            foreach ($request->file('slider') as $file) {
                $name = str_replace(' ', '_', $file->getClientOriginalName());
                $file_path = implode("/", ['slider', date('Ymd/His')]);
                $file->move(storage_path('/app/public/' . $file_path), $name);
                $data[] = "/storage/${file_path}/{$name}";
            }
        }
        Storage::disk('local')->delete('app/public/storage/slider' . basename($property->slider));

        $foto_overview = $this->uploadImage($request, 'overview', 'foto_overview');
        $icon = $this->uploadImage($request, 'icon', 'icon');
        $foto_arsitek = $this->uploadImage($request, 'arsitek', 'foto_arsitek');
        $foto_masterplan = $this->uploadImage($request, 'masterplan', 'foto_masterplan');
        $foto_fasilitas = $this->uploadImage($request, 'fasilitas', 'foto_fasilitas');
        $foto_unit = $this->uploadImage($request, 'unit', 'foto_unit');
        $galery_unit = $this->uploadImage($request, 'galery_unit', 'galery_unit');
        $foto_fasilitas_publik = $this->uploadImage($request, 'foto_fasilitas_publik', 'foto_fasilitas_publik');
        $foto_footer = $this->uploadImage($request, 'footer', 'foto_footer');

        Storage::disk('local')->delete('app/public/storage/overview/' . basename($property->foto_overview));
        Storage::disk('local')->delete('app/public/storage/icon/' . basename($buld_fasilitas->icon));
        Storage::disk('local')->delete('app/public/storage/arsitek/' . basename($property->foto_arsitek));
        Storage::disk('local')->delete('app/public/storage/masterplan/' . basename($property->foto_masterplan));
        Storage::disk('local')->delete('app/public/storage/fasilitas/' . basename($property->foto_fasilitas));
        Storage::disk('local')->delete('app/public/storage/unit/' . basename($unit->foto_unit));
        Storage::disk('local')->delete('app/public/storage/foto_fasilitas_publik/' . basename($fasilitas_publik->foto_fasilitas_publik));
        Storage::disk('local')->delete('app/public/storage/galery_unit/' . basename($unit->galery_unit));
        Storage::disk('local')->delete('app/public/storage/footer/' . basename($property->foto_footer));

        DB::beginTransaction();
        try {

            $unit->update([
                'foto_unit' => $foto_unit,
                'nama_unit' => $request->get('nama_unit'),
                'cluster_id' => $request->get('cluster_id'),
                'deskripsi_unit' => $request->get('deskripsi_unit'),
                'luas_bangunan' => $request->get('luas_bangunan'),
                'luas_tanah' => $request->get('luas_tanah'),
                'kamar_tidur' => $request->get('kamar_tidur'),
                'karpot' => $request->get('karpot'),
                'kamar_mandi' => $request->get('kamar_mandi'),
                'galery_unit' => $galery_unit,
            ]);

            $fasilitas_publik->update([
                'foto_fasilitas_publik' => $foto_fasilitas_publik,
                'judul_fasilitas_publik' => $request->get('judul_fasilitas_publik'),
            ]);

            $buld_fasilitas->update([
                'icon' => $icon,
                'judul_icon' => $request->get('judul_icon'),
                'deskripsi_icon' => $request->get('deskripsi_icon'),
            ]);

            $property->update([
                'judul_project' => $request->get('judul_project'),
                'slider' => json_encode($data),
                'foto_overview' => $foto_overview,
                'judul_overview' => $request->get('judul_overview'),
                'deskripsi_overview' => $request->get('deskripsi_overview'),
                'foto_arsitek' => $foto_arsitek,
                'judul_arsitek' => $request->get('judul_arsitek'),
                'deskripsi_arsitek' => $request->get('deskripsi_arsitek'),
                'foto_fasilitas' => $foto_fasilitas,
                'judul_fasilitas' => $request->get('judul_fasilitas'),
                'deskripsi_fasilitas' => $request->get('deskripsi_fasilitas'),
                'link_fasilitas' => $request->get('link_fasilitas'),
                'foto_masterplan' => $foto_masterplan,
                'judul_masterplan' => $request->get('judul_masterplan'),
                'foto_footer' => $foto_footer,
                'deskripsi_footer' => $request->get('deskripsi_footer'),
                'alamat_footer' => $request->get('alamat_footer'),
                'link_maps' => $request->get('link_maps'),
                'sosmed' => $request->get('sosmed'),
                'link_sosmed' => $request->get('link_sosmed'),
                'unit_id' =>  $request->get('unit_id'),
                'buld_fasilitas_id' =>  $request->get('buld_fasilitas_id'),
                'fasilitas_publik_id' => $request->get('fasilitas_publik_id'),
            ]);
            DB::commit();
            return $this->successResponse([$property, $buld_fasilitas, $fasilitas_publik, $unit], 'Property successfully updated');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse('null', 'There is something wrong' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        $fasilitas_publik = FasilitasPublik::find($id);
        $buld_fasilitas = BuldFasilitas::find($id);
        $property = Property::find($id);
        if (!$property) {
            return $this->errorResponse(null, 'Property not found', 404);
        } else {
            //delete image old
            Storage::delete('public/slider' . $property->slider);
            Storage::disk('local')->delete('app/public/storage/overview/' . basename($property->foto_overview));
            Storage::disk('local')->delete('app/public/storage/icon/' . basename($buld_fasilitas->icon));
            Storage::disk('local')->delete('app/public/storage/arsitek/' . basename($property->foto_arsitek));
            Storage::disk('local')->delete('app/public/storage/masterplan/' . basename($property->foto_masterplan));
            Storage::disk('local')->delete('app/public/storage/fasilitas/' . basename($property->foto_fasilitas));
            Storage::disk('local')->delete('app/public/storage/unit/' . basename($unit->foto_unit));
            Storage::disk('local')->delete('app/public/storage/foto_fasilitas_publik/' . basename($fasilitas_publik->foto_fasilitas_publik));
            Storage::disk('local')->delete('app/public/storage/galery_unit/' . basename($unit->galery_unit));
            Storage::disk('local')->delete('app/public/storage/footer/' . basename($property->foto_footer));
            // delete property
            $property->delete();
            return $this->successResponse(new PropertyResource($property), 'Property successfully deleted');
        }
    }



    protected function validationStore()
    {
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
            'deskripsi_fasilitas' => 'required',
            'link_fasilitas' => 'required',
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
            'sosmed' => 'required',
            'link_sosmed' => 'required'
        ];
    }

    protected function validationUpdate()
    {
        return [
            'judul_project' => 'nullable',
            'slider' => 'nullable',
            'slider.*' => 'image|mimes:jpeg,png,jpg,gif',
            'foto_overview' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'judul_overview' => 'nullable',
            'deskripsi_overview' => 'nullable',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'judul_icon' => 'nullable',
            'deskripsi_icon' => 'nullable',
            'foto_arsitek' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'judul_arsitek' => 'nullable',
            'deskripsi_arsitek' => 'nullable',
            'foto_fasilitas' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'judul_fasilitas' => 'nullable',
            'deskripsi_fasilitas' => 'nullable',
            'link_fasilitas' => 'nullable',
            'foto_masterplan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'judul_masterplan' => 'nullable',
            'foto_unit' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'nama_unit' => 'nullable',
            'cluster_id' => 'nullable',
            'deskripsi_unit' => 'nullable',
            'luas_bangunan' => 'nullable',
            'luas_tanah' => 'nullable',
            'kamar_tidur' => 'nullable|numeric',
            'karpot' => 'nullable',
            'kamar_mandi' => 'nullable|numeric',
            'galery_unit' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto_footer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi_footer' => 'nullable',
            'alamat_footer' => 'nullable',
            'link_maps' => 'nullable',
            'sosmed' => 'nullable|in:facebook,twitter,ig',
            'link_sosmed' => 'nullable'
        ];
    }
}
