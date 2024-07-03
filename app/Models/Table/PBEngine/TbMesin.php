<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TbMesin extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'mesin_kode_mesin';
    protected $table = 'tb_mesin';

    //protected $guarded = ['mesin_kode_mesin'];
    protected $fillable = [
        'mesin_kode_mesin', // tambahkan atribut ini ke dalam fillable
        'mesin_id_gm',
        'mesin_nama_mesin',
        'mesin_rating',
        'mesin_safety_factor_capacity_id',
        'mesin_thickness_min',
        'mesin_thickness_max',
        'min_requirement',
        'max_requirement',
        'mesin_priority',
        'mesin_quantity',
        'mesin_status',
        'mesin_created_by',
        'mesin_created_at',
        'mesin_modified_by',
        'mesin_modified_at'
    ];
    public $timestamps = false;

    protected $attributes = [
        // atur nilai default untuk 'sfc_delete_status' ke 0
        'mesin_status'=>0 ];


    public function safety()
        {
            return $this->belongsTo(TbSafetyFactorCapacity::class, 'sfc_id', 'mesin_safety_factor_capacity_id');
    }

    function get_breakdown_mesin_list(){

        return DB::table('tb_mesin AS a')
        ->select('a.*', 'b.sfc_value', 'c.process_name')
        ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'a.mesin_kode_mesin')
        ->join('tb_safety_factor_capacity AS b', 'b.sfc_id', '=', 'a.mesin_safety_factor_capacity_id')
        ->join('tb_process AS c', 'c.proses_id', '=', 'd.DM_process_id')
        ->where('a.mesin_delete_status', 0)
        ->where('a.mesin_status', 1)
        ->groupBy('a.mesin_kode_mesin') // Menambahkan group by
        ->get();
    
      
    }

    public static function countMachines()
    {
        return self::where('mesin_status', 0)
                    ->where('mesin_delete_status', 0)
                    ->count();
    }

    public function get_breakdown_mesin_num()
    {
        $totalMesin = DB::table('tb_mesin as a')
                        ->selectRaw('COUNT(a.mesin_kode_mesin) as total_mesin')
                        ->where('a.mesin_status', 1)
                        ->first();

        return $totalMesin->total_mesin;
    }

    public function get_available_mesin(){
        return DB::table('view_chart_capacity_vs_actual_1 AS a')
                    ->select('a.*')
                    ->get()
                    ->toArray();
    }

    public function get_mesin_by_nama_mesin($nama){
        return DB::table('tb_mesin AS a')
        ->select('a.*', 'b.sfc_value', 'c.process_name', 'c.proses_id')
        ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'a.mesin_kode_mesin')
        ->join('tb_safety_factor_capacity AS b', 'b.sfc_id', '=', 'a.mesin_safety_factor_capacity_id')
        ->join('tb_process AS c', 'c.proses_id', '=', 'd.DM_process_id')
        ->where('a.mesin_nama_mesin', $nama)
        ->toArray()
        ->get();
    }

    public function get_mesin_by_process_name($procesname){
        return DB::table('tb_mesin AS a')
        ->select('a.*', 'b.sfc_value', 'c.process_name', 'c.proses_id')
        ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'a.mesin_kode_mesin')
        ->join('tb_safety_factor_capacity AS b', 'b.sfc_id', '=', 'a.mesin_safety_factor_capacity_id')
        ->join('tb_process AS c', 'c.proses_id', '=', 'd.DM_process_id')
        ->like('c.process_name', $procesname)
        ->toArray()
        ->get();
        
    }

    public function get_mesin_by_process_id($processId){
        return DB::table('tb_mesin AS a')
        ->select('a.*', 'b.sfc_value','c.proses_id', 'c.process_name', 'c.proses_id')
        ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'a.mesin_kode_mesin')
        ->join('tb_safety_factor_capacity AS b', 'b.sfc_id', '=', 'a.mesin_safety_factor_capacity_id')
        ->join('tb_process AS c', 'c.proses_id', '=', 'd.DM_process_id')
        ->where('d.DM_process_id', $processId)
        ->where('a.mesin_status', 0)
        ->get();
        // ->toArray();
        
    }


    public function getMesinByProcessId($processId, $not_in = null){
        $query = DB::table('tb_mesin AS a')
            ->select('a.*', 'b.sfc_value', 'c.proses_id', 'c.process_name')
            ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'a.mesin_kode_mesin')
            ->join('tb_safety_factor_capacity AS b', 'b.sfc_id', '=', 'a.mesin_safety_factor_capacity_id')
            ->join('tb_process AS c', 'c.proses_id', '=', 'd.DM_process_id')
            ->where('d.DM_process_id', $processId)
            ->where('a.mesin_status', 0);
    
        if($not_in !== null){
            $query->whereNotIn('a.mesin_kode_mesin', $not_in);
        }
    
        return $query->get();
    }

    public function get_data_mesin_by_kodemesin($mesin_kode_mesin){
        $result = DB::table('tb_mesin AS a')
        ->select('a.*', 'b.sfc_value', 'c.process_name', 'c.proses_id')
        ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'a.mesin_kode_mesin')
        ->join('tb_safety_factor_capacity AS b', 'b.sfc_id', '=', 'a.mesin_safety_factor_capacity_id')
        ->join('tb_process AS c', 'c.proses_id', '=', 'd.DM_process_id')
        ->where('a.mesin_kode_mesin', $mesin_kode_mesin)
        ->get();

        return $result->toArray();

    }

    public function update_mesin($id, $params)
    {
        return self::where('mesin_kode_mesin', $id)
        ->update($params);
    }
    
        
}