<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbAssign extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'ANP_id';
    protected $table = 'tb_assign_nesting_programmer';

    protected $guarded = ['ANP_id'];

    public $timestamps = false;


    public static function countOngoingJobs()
    {
        return self::where('ANP_progres', 1)->count();
    }

    public function getAllAssign()
    {
        return DB::table('tb_assign_nesting_programmer as a')
        ->select('a.*', 'b.*', 'c.customer_name', 'c.customer_id', 'd.*')
        ->join('tb_base_table as d', 'd.mppid', '=', 'a.anp_key_IMA')
        ->join('tb_mesin as b', 'b.mesin_nama_mesin', '=', 'a.ANP_mesin_kode_mesin')
        // ->join('tb_mesin as b', 'b.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
        ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro', '=', 'a.ANP_data_PRO')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
        ->where('a.ANP_progres', '<=', 4)
        ->get()
        ->toArray();

    }

    public function get_All_Assign()
    {
        return DB::table('tb_assign_nesting_programmer as a')
        ->select('a.*', 'b.*', 'c.customer_name')
        // ->join('tb_base_table as d', 'd.mppid', '=', 'a.anp_key_IMA')
        ->join('tb_mesin as b', 'b.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
        ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro', '=', 'a.ANP_data_PRO')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
        ->where('a.ANP_progres', '<=', 4)
        ->get()
        ->toArray();

    }

    public function getAssign(){

        return $this
        //->select('b.*','m.MIC_drawing', 'a.ANP_qty', 'a.ANP_qty_finish')
        ->select('b.*','m.MIC_drawing')
        ->from('tb_base_table as b')
        ->join('tb_mapping_image_component as m', 'm.MIC_PN_component', '=', 'b.PartNumberComponent')
       //W ->leftJoin('tb_assign_nesting_programmer as a', 'a.ANP_data_PRO', '=', 'b.PRONumber')
        ->where('b.status_assign', '=', 2)
        ->get();

    }

    public function checkAssign($ANP_key_IMA)
    {
        $query = $this->select('a.*', 'd.DM_process_id as prosess_id', 'b.mesin_nama_mesin')
            ->from('tb_assign_nesting_programmer AS a')
            ->join('tb_mesin AS b', 'b.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
            ->join('tb_detail_mesin AS d', 'd.DM_mesin_kode_mesin', '=', 'b.mesin_kode_mesin')
            ->where('a.ANP_key_IMA', $ANP_key_IMA)

        // if (!empty($progress_checking)) {
            ->where('a.ANP_progres', '<', 4);
        // }

        return $query->get()->toArray();
    }

    public function add_assign($params){
        return self::create($params);
    }

    // public function get_assign_by_id($anpid){
    //    $result = DB::table('tb_assign_nesting_programmer AS a')
    //         ->leftJoin('tb_base_table AS bt', 'bt.mppid', '=', 'a.ANP_key_IMA')
    //         ->join('tb_mesin AS m', 'm.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
    //         ->join('tb_detail_mesin AS dm', 'dm.DM_mesin_kode_mesin', '=', 'm.mesin_kode_mesin')
    //         ->join('tb_process AS p', 'p.proses_id', '=', 'dm.DM_process_id')
    //         ->select('a.*', 'p.*', 'bt.*', 'm.mesin_nama_mesin')
    //         ->where('a.ANP_id', $anpid)
    //         ->get();

    //     return $result->toArray();
    // }
    
    public function get_assign_by_id($anpid){
        $result = DB::table('tb_assign_nesting_programmer AS a')
            ->leftJoin('tb_base_table AS bt', 'bt.mppid', '=', 'a.ANP_key_IMA')
            ->join('tb_mesin AS m', 'm.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
            ->join('tb_detail_mesin AS dm', 'dm.DM_mesin_kode_mesin', '=', 'm.mesin_kode_mesin')
            ->join('tb_process AS p', 'p.proses_id', '=', 'dm.DM_process_id')
            ->select('a.*', 'p.*', 'bt.*', 'm.mesin_nama_mesin')
            ->where('a.ANP_id', $anpid)
            ->get();

        return $result->toArray();
    }

    public function update_assign($id, $params){
        $assign = self::find($id);
        if ($assign) {
            $assign->fill($params)->save();
            return true;
        }
        return false;
    }

    public function get_assign_by_assign_id($id){
        $result = DB::table('tb_assign_nesting_programmer AS a')
            ->select('a.*', 'b.*', 'bt.*', 'd.process_name', 'c.customer_name')
            ->join('tb_mesin AS b', 'b.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
            ->join('tb_detail_mesin as e' , 'e.DM_mesin_kode_mesin', '=', 'b.mesin_kode_mesin' )
            ->join('tb_process AS d', 'd.proses_id', '=', 'e.DM_process_id')
            ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'a.ANP_data_PRO')
            ->leftJoin('tb_customer AS c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
            ->leftJoin('tb_base_table AS bt', 'bt.mppid', '=', 'a.ANP_key_IMA')
            ->where('a.ANP_id', $id)
            ->get(); // Menggunakan first() untuk mendapatkan satu baris hasil

        return $result->toArray();

    }

   

    public function get_assign_by_mesin($mesin_kode_mesin, $forGL = null, $finish = null)
    {
        $query = DB::table('tb_assign_nesting_programmer AS a')
            ->select('a.*', 'b.*', 'bt.*', 'c.customer_name')
            ->join('tb_mesin AS b', 'b.mesin_kode_mesin', '=', 'a.ANP_mesin_kode_mesin')
            ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'a.ANP_data_PRO')
            ->leftJoin('tb_customer AS c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
            ->leftJoin('tb_base_table AS bt', 'bt.mppid', '=', 'a.ANP_key_IMA')
            ->where('a.ANP_mesin_kode_mesin', $mesin_kode_mesin);

        if ($forGL !== null) {
            if ($finish !== null) {
                $query->where('a.ANP_progres', '4');
            } else {
                $query->where('a.ANP_progres', '1')
                    ->whereNotIn('a.ANP_id', function ($subQuery) {
                        $subQuery->select('bj_anp_id')->from('tb_bundling_job');
                    });
            }
        } else {
            $query->whereIn('a.ANP_progres', ['1', '2', '3', '0']);
        }

        $query->orderBy('a.ANP_urgency', 'desc');

        return $query->get()->toArray();
    }


    

}
