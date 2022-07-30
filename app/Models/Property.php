<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\BuldFasilitas;
use App\Models\FasilitasPublik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $table = "property";
    protected $guarded = ['id'];

    public $appends = ['link'];

    public function getLinkAttribute()
    {
        return url($this->slider);
    }
    public function buld_fasilitas()
    {
        return $this->belongsTo(BuldFasilitas::class);
    }

    public function fasilitas_publik()
    {
        return $this->belongsTo(FasilitasPublik::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
