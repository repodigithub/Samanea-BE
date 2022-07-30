<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cluster extends Model
{
    use HasFactory;

    protected $table = "cluster";
    protected $guarded = [];

    public function unit()
    {
        return $this->hasMany(Unit::class);
    }
}
