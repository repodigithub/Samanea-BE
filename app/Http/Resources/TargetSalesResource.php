<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TargetSalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "target" => $this->target,
            "tanggal_awal"  =>  Carbon::parse($this->tanggal_awal)->toDateString(),
            "tanggal_akhir"  => Carbon::parse($this->tanggal_akhir)->toDateString(),
            "pencapaian" => $this->pencapaian,
            "status" => $this->status,
            "created_at"  => Carbon::parse($this->created_at)->format('Y-m-d'),
            "updated_at" => Carbon::parse($this->updated_at)->format('Y-m-d'),
        ];
    }
}