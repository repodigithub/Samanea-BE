<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BuldFasilitas extends Model
{
    use HasFactory;

    protected $table = "buld_fasilitas";
    protected $guarded = ['id'];

    public function property()
    {
        return $this->hasMany(Property::class);
    }
}
