<?php

namespace App\Models;

use App\Models\Cluster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model 
{
    use HasFactory;
    
    protected $table = "property";
    protected $guarded = ['id'];

    public function cluster() {
        return $this->belongsTo(Cluster::class);
    }
}
