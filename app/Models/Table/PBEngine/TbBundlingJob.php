<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbBundlingJob extends Model
{

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'id_bj';
    protected $table = 'tb_bundling_job';

    protected $guarded = ['id_bj'];
    public $timestamps = false;

    use HasFactory;

    function get_key_by_jobid_anp_id($anpid){
        $query=$this->select('bj.bj_bundling_key')
        ->from('tb_bundling_job AS bj')
        ->where('bj.bj_anp_id',$anpid)
        ->where('bj.bj_job_status',0);

        return $query->get()->toArray(); 
    }

    // function get_uniq_code($kodemesin , $forGL = null){
    //     $query=$this->distinct()
    //     ->select('bj.bj_bundling_key')
    //     ->from('tb_bundling_job AS bj')
    //     ->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id' ,'=', 'a.ANP_id')
    //     ->where('a.ANP_mesin_kode_mesin',$kodemesin)
    //     ->whereIn('a.ANP_progres', array('1', '2' , '3' , '0'));          
        
    //     return $query->get()->toArray();
    // }

    function get_uniq_code($kodemesin , $forGL = null){
        $query=$this->distinct()
        ->select('bj.bj_bundling_key')
        ->from('tb_bundling_job AS bj')
        ->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id' ,'=', 'a.ANP_id')
        ->join('tb_mesin AS m', 'a.ANP_mesin_kode_mesin', '=', 'm.mesin_kode_mesin')
        ->where('m.mesin_nama_mesin',$kodemesin)
        ->whereIn('a.ANP_progres', array('1', '2' , '3' , '0'));          
        
        return $query->get()->toArray();
    }

    function check_bundling_job_progres($uniqcode){
        $query= $this->distinct()
        ->select('bj.bj_anp_id' , 'a.ANP_progres')
        ->from('tb_bundling_job AS bj')
        ->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id', '=', 'a.ANP_id')
        ->where('bj.bj_bundling_key',$uniqcode)
        ->whereIn('a.ANP_progres', array('1', '2' , '3' , '0'));
        return $query->get()->toArray();
    }

    //data tabel
    function get_detail_data_uniq_code(){
        $query = $this->distinct()
        ->select('bj.bj_bundling_key' , 'bt.PN' , 'bt.PRONumber' , 'bt.PartNumberComponent' , 'a.ANP_qty' , 'a.ANP_progres' , 'a.ANP_id')
        ->from('tb_bundling_job AS bj')
        ->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id', '=', 'a.ANP_id')
        ->join('tb_base_table as bt', 'bt.mppid', '=', 'a.ANP_key_IMA');
        return $query->get()->toArray();
    }

    
}
