<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetSales extends Model
{
    use HasFactory;

    protected $table = "target_sales";

    const STATUS_ON_PROGRESS  = 'on_progress';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'target', 'tanggal_awal', 'tanggal_akhir', 'pencapaian', 'status',
    ];

    public $appends = ['status'];

    public $hidden  = ['status'];

    public $dates = ['tanggal_awal', 'tanggal_akhir'];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['from'] ?? false) {
            $query->where('tanggal_awal', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
        }
        if ($filters['to'] ?? false) {
            $query->where('tanggal_akhir', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
        }
    }

    public function getStatusClaimAttribute()
    {
        return !empty($this->pencapaian) ? self::STATUS_ON_PROGRESS : self::STATUS_SUCCESS;
    }
}
