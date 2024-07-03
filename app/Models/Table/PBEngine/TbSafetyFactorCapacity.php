<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbSafetyFactorCapacity extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'sfc_id';
    protected $table = 'tb_safety_factor_capacity';

    protected $guarded = ['sfc_id'];

    public $timestamps = false;

    protected $attributes = [
        // atur nilai default untuk 'sfc_delete_status' ke 0
        'sfc_delete_status' => 0
    ];

    public function safetyFactor()
    {
        return $this->hasMany(TbMesin::class, 'sfc_id', 'mesin_safety_factor_capacity_id');
    }

    public function allData(){
        return DB::table('tb_safety_factor_capacity as a')
        ->select('a.sfc_id as kode_sfc', 'a.sfc_value')
        ->where('a.sfc_delete_status', 0)
        ->get()
        ->toArray();
    }
}
