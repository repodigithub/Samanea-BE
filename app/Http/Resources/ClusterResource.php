<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ClusterResource extends JsonResource
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
            "name" => $this->name,
            "created_at"  => Carbon::parse($this->created_at)->format('Y-m-d'),
            "updated_at" => Carbon::parse($this->updated_at)->format('Y-m-d'),
        ];
    }
}