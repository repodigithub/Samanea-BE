<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Traits\Response;
use App\Models\TargetSales;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TargetSalesResource;

class TargetSalesController extends Controller
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
    public function index(Request $request)
    {
        $targetSales = TargetSales::latest()->filter(request(['from', 'to']))->paginate(request('limit') ?: 15, ["*"], "page", request('page') ?: 1);
        return $this->successResponse($targetSales, 'List Target Sales Management');
    }

    public function show($id)
    {
        $targetSales = TargetSales::find($id);
        if (!$targetSales) {
            return $this->errorResponse(null, 'Target Sales not found', 404);
        } else {
            return $this->successResponse(new TargetSalesResource($targetSales), 'Show Target Sales Management');
        }
    }

    public function store(Request $request)
    {

        //Validate data
        $validator = Validator::make($request->all(), [
            'target' => 'required|numeric',
            'tanggal_awal' => 'required|date|before:tanggal_akhir',
            'tanggal_akhir' => 'required|date|after:tanggal_awal',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        // $tanggal_awal  = date('Y-m-d', strtotime(now()));
        $tanggal_awal = Carbon::now()->toDateString();
        $tanggal_akhir = date('Y-m-d', strtotime($request->get('tanggal_akhir')));

        $checkRangeAwal = TargetSales::whereStatus('on_progress')->get();

        foreach ($checkRangeAwal as $check) {
            $awal = $check->tanggal_awal;
            $akhir = $check->tanggal_akhir;
        }

        $startDate = date('Y-m-d', strtotime(isset($awal) ? $awal : ''));
        $endDate = date('Y-m-d', strtotime(isset($akhir) ? $akhir : ''));
        // function check date in db with input date
        if (($tanggal_awal <= $startDate) || ($tanggal_akhir <= $endDate)) {
            return $this->errorResponse(null, 'Target dates cannot be the same', 402);
        } else {
            $targetSales = TargetSales::create([
                'target' => $request->get('target'),
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
            ]);
            return $this->successResponse(new TargetSalesResource($targetSales), 'Target sales created successfully');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|numeric',
            'tanggal_awal' => 'required|date|before:tanggal_akhir',
            'tanggal_akhir' => 'required|date|after:tanggal_awal',
        ]);

        $targetSales = TargetSales::find($id);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->errorResponse('null', $validator->errors(), 422);
        }

        $tanggal_awal  = date('Y-m-d', strtotime($request->get('tanggal_awal')));
        $tanggal_akhir = date('Y-m-d', strtotime($request->get('tanggal_akhir')));

        $checkRangeAwal = TargetSales::whereStatus('on_progress')->get();

        foreach ($checkRangeAwal as $check) {
            $awal = $check->tanggal_awal;
            $akhir = $check->tanggal_akhir;
        }

        $startDate = date('Y-m-d', strtotime(isset($awal) ? $awal : ''));
        $endDate = date('Y-m-d', strtotime(isset($akhir) ? $akhir : ''));

        // - ketika tgl sekarang itu lebih besar dari tgl akhir yang on progress maka itu tidak apa apa 
        // - ketika tgl sekarang itu lebih kecil dari tgl akhir dan lebih besar dari tgl awal maka itu kena validasi bahwa tidak boleh ada yang sama 

        // function check date in db with input date
        if (($tanggal_awal >= $startDate) && ($tanggal_akhir <= $endDate)) {
            return $this->errorResponse(null, 'Target dates cannot be the same', 402);
        } else {
            $targetSales->update([
                'target' => $request->get('target'),
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
            ]);
            return $this->successResponse(new TargetSalesResource($targetSales), 'Target sales created successfully');
        }
    }
}
