<?php

namespace App\Models;

use App\Models\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cluster extends Model 
{
    use HasFactory;
    
    protected $table = "cluster";
    protected $guarded = ["id"];

    public function property() {
        return $this->hasMany(Property::class);
    }
}
