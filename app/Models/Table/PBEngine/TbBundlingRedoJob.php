<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbBundlingRedoJob extends Model
{
    use HasFactory;

    function get_key_bundling_by_redoid($r_id){
        $this->db->select('bj.brj_bundling_key');
        $this->db->from('tb_bundling_redo_job AS bj');
        $this->db->where('bj.brj_r_id',$r_id);
        $this->db->where('bj.brj_job_status',0);
        return $this->db->get()->toArray(); 
    }


    function get_uniq_code($kodemesin , $forGL = null){
        $query= $this->distinct()
        ->select('bj.brj_bundling_key')
        ->from('tb_bundling_redo_job AS bj')
        ->join('tb_redo AS a' , 'bj.brj_r_id', '=' ,'a.r_id')
        ->join('tb_mesin as m','a.r_kode_mesin', '=', 'm.mesin_kode_mesin')
        ->where('m.mesin_nama_mesin',$kodemesin);
        if($forGL == null){
            $query->whereIn('a.r_progres', array('1', '2' , '3' , '0'));
        }
        
        return $query->get()->toArray();
    }

    //data tabel
    function get_detail_data_uniq_code(){
        $query=$this->distinct()
        ->select('bj.brj_bundling_key' , 'r.r_id', 'r.r_progres' , 'r_category', 'r.r_qty' , 'bt.PN' , 'bt.PRONumber' , 'bt.PartNumberComponent' , 'a.ANP_qty' , 'a.ANP_progres' , 'a.ANP_id')
        ->from('tb_bundling_redo_job AS bj')
        ->join('tb_redo as r' , 'r.r_id', '=' ,'bj.brj_r_id')
        ->join('tb_assign_nesting_programmer AS a' , 'r.r_anp_id', '=' ,'a.ANP_id')
        ->join('tb_base_table as bt', 'bt.mppid' ,'=', 'a.ANP_key_IMA');
        return $query->get()->toArray();
    }
}
