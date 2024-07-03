<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MoveQTY_model extends Model
{
    protected $connection = 'mysqlpbengine';


    public function getMovingIn($mppid)
    {
        return DB::table('tb_history_moving AS a')
            ->selectRaw('SUM(a.mv_qty) as qtymove')
            ->where('a.mv_keydata_target', $mppid)
            ->groupBy('a.mv_keydata_target')
            ->first();
    }

    public function getMovingOut($mppid)
    {
        return DB::table('tb_history_moving AS a')
            ->selectRaw('SUM(a.mv_qty) as qtymove')
            ->where('a.mv_keydata_source', $mppid)
            ->groupBy('a.mv_keydata_source')
            ->first();
    }


    function get_movein_by_mppid($mppid){
        return DB::table('tb_history_moving AS a')
            ->select('a.mv_keydata_source', 'a.mv_qty')
            ->where('a.mv_keydata_target', $mppid)
            ->get()
            ->toArray();
    }

    function get_moveout_by_mppid($mppid){
        return DB::table('tb_history_moving AS a')
            ->select('a.mv_keydata_source', 'a.mv_qty')
            ->where('a.mv_keydata_target', $mppid)
            ->get()
            ->toArray();
    }

    function get_detail_data($mppid) {
        return DB::table('tb_base_table AS tb')
                ->select('c.customer_name', 'tb.*')
                ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro', '=', 'tb.PRONumber')
                ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
                ->where('tb.mppid', $mppid)
                ->get()
                ->toArray();
    }
    

    // function get_detail_data($mppid){
    //     return DB::table('tb_base_table AS tb')
    //             ->select('c.customer_name', 'tb.*')
    //             ->join('tb_mapping_pro_customer as mpc' ,'mpc.mapping_pro' ,'=' ,'tb.PRONumber', 'left')
    //             ->join('tb_customer as c', 'c.customer_id = mpc.mapping_customer_id','left')
    //             ->where('mppid', $mppid)
    //             ->get()
    //             ->toArray();
    // }
    

  
}
