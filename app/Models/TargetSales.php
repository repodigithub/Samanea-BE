<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetSales extends Model 
{
    use HasFactory;
    
    protected $table = "target_sales";
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'target', 'tanggal_awal', 'tanggal_akhir', 'pencapaian', 'status',
    ];

    public $dates = ['tanggal_awal', 'tanggal_akhir'];

    public function scopeFilter($query, array $filters) {
        if($filters['from'] ?? false) {
            $query->where('tanggal_awal', '>=' , Carbon::parse(request('from') )->format('Y-m-d'));
        }
        if($filters['to'] ?? false) {
            $query->where('tanggal_akhir', '<=' , Carbon::parse(request('to') )->format('Y-m-d'));
        }
        // $query->when($filters['from'] ?? false, function($query, $from){
        //     $query->where('tanggal_awal', '>=' , Carbon::parse($from)->format('Y-m-d'));
        // });
        // $query->when($filters['to'] ?? false, function($query, $to){
        //     $query->where('tanggal_akhir', '<=' , Carbon::parse($to)->format('Y-m-d'));
        // });
    }
}
