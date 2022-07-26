<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Cluster;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            "slider" => $this->slider,
            "judul_project" => $this->judul_project,
            "foto_overview" => $this->foto_overview,
            "judul_overview" => $this->judul_overview,
            "deskripsi_overview" => $this->deskripsi_overview,
            "icon" => $this->icon,
            "judul_icon" => $this->judul_icon,
            "deskripsi_icon" => $this->deskripsi_icon,
            "foto_arsitek" => $this->foto_arsitek,
            "judul_arsitek" => $this->judul_arsitek,
            "deskripsi_arsitek" => $this->deskripsi_arsitek,
            "judul_fasilitas" => $this->judul_fasilitas,
            "deskiprsi_fasilitas" => $this->deskiprsi_fasilitas,
            "link" => $this->link,
            "foto_masterplan" => $this->foto_masterplan,
            "judul_masterplan" => $this->judul_masterplan,
            "foto_unit" => $this->foto_unit,
            "nama_unit" => $this->nama_unit,
            'cluster_id' => $this->cluster_id,
            "deskripsi_unit" => $this->deskripsi_unit,
            "luas_bangunan" => $this->luas_bangunan,
            "luas_tanah" => $this->luas_tanah,
            "kamar_tidur" => $this->kamar_tidur,
            "karpot" => $this->karpot,
            "kamar_mandi" => $this->kamar_mandi,
            "galery_unit" => $this->galery_unit,
            "foto_footer" => $this->foto_footer,
            "deskripsi_footer" => $this->deskripsi_footer,
            "alamat_footer" => $this->alamat_footer,
            "link_maps" => $this->link_maps,
            "sosmed" => $this->sosmed,
            "link_sosmed" => $this->link_sosmed,
            "created_at"  => Carbon::parse($this->created_at)->format('Y-m-d'),
            "updated_at" => Carbon::parse($this->updated_at)->format('Y-m-d'),
        ];
    }
}