<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FasilitasPublik extends Model
{
    use HasFactory;

    protected $table = "fasilitas_publik";
    protected $guarded = ['id'];

    public function property()
    {
        return $this->hasMany(Property::class);
    }
}
