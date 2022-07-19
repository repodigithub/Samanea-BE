<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            "fullname" => $this->fullname,
            "email"  => $this->email,
            "level"  => $this->level,
            'status' => $this->status,
            'team_leader' => $this->team_leader,
            'supervisor' => $this->supervisor,
            "created_at"  => Carbon::parse($this->created_at)->format('Y-m-d'),
            "updated_at" => Carbon::parse($this->updated_at)->format('Y-m-d'),
        ];
    }
}