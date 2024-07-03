<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbRedo extends Model
{
    use HasFactory;

    // function get_all_redo_job_not_finish($kodemesin)
    // {
    //     $query=$this->select('a.*' , 'bt.*')
    //     ->from('tb_redo AS a')
    //     ->join('tb_assign_nesting_programmer AS anp', 'anp.ANP_id' ,'=', 'a.r_anp_id') 
    //     ->join('tb_base_table AS bt', 'bt.mppid' ,'=' ,'anp.ANP_key_IMA')
    //     ->where('a.r_kode_mesin', $kodemesin)
    //     ->where('a.r_progres', '< ', '4');
        
    //     return $query->get()->toArray();
    // }

    function get_all_redo_job_not_finish($kodemesin)
    {
        $query=$this->select('a.*' , 'bt.*')
        ->from('tb_redo AS a')
        ->join('tb_assign_nesting_programmer AS anp', 'anp.ANP_id' ,'=', 'a.r_anp_id') 
        ->join('tb_base_table AS bt', 'bt.mppid' ,'=' ,'anp.ANP_key_IMA')
        ->join('tb_mesin AS m', 'anp.ANP_mesin_kode_mesin', '=', 'm.mesin_kode_mesin')
        ->where('m.mesin_nama_mesin', $kodemesin)
        ->where('a.r_progres', '< ', '4');
        
        return $query->get()->toArray();
    }



    function get_job_by_redoid($r_id)
    {
        $query= $this->select('a.Job_id , a.Job_category , redo.r_progres')
        ->from('tb_job AS a')
        ->join('tb_redo AS redo', 'redo.r_id', '=', 'a.job_redo_id') 
        ->where('a.job_redo_id', $r_id)  
        ->orderBy('a.modified_at', 'desc');
        return $this->db->get()->toArray(); 
    }
}
