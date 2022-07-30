<?php

namespace App\Models;

use App\Models\Cluster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $table = "unit";
    protected $guarded = [];

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function property()
    {
        return $this->hasMany(Property::class);
    }
}
