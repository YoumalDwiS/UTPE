<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbBundlingStartStop extends Model
{
    use HasFactory;

    // protected $connection = 'mysqlpbengine';
    // protected $primaryKey = 'id_bj';
    // protected $table = 'tb_bundling_job';

    // protected $guarded = ['id_bj'];
    // public $timestamps = false;

    function get_anpid_by_keybundling($keybundling){
        $this->db->select('bj.bj_anp_id');
        $this->db->from('tb_bundling_job AS bj');
        $this->db->where('bj.bj_bundling_key',$keybundling);
        return $this->db->get()->result_array();
    }

    function get_key_by_jobid_anp_id($anpid){
        $this->db->select('bj.bj_bundling_key');
        $this->db->from('tb_bundling_job AS bj');
        $this->db->where('bj.bj_anp_id',$anpid);
        $this->db->where('bj.bj_job_status',0);
        return $this->db->get()->row_array(); 
    }

    function get_bundling_by_uniqcode($uniqcode){
        $this->db->distinct();
        $this->db->select('bj.bj_anp_id ,  j.Job_id as bj_job_id , a.ANP_progres');
        $this->db->from('tb_bundling_job AS bj');
        $this->db->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id = a.ANP_id');
        $this->db->join('tb_job as j' , 'j.Job_ANP_id = a.ANP_id and j.job_bundling_code = bj.bj_bundling_key');
        $this->db->where('bj.bj_bundling_key',$uniqcode);
        return $this->db->get()->result_array();
    }

    function check_bundling_job_progres($uniqcode){
        $this->db->distinct();
        $this->db->select('bj.bj_anp_id , a.ANP_progres');
        $this->db->from('tb_bundling_job AS bj');
        $this->db->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id = a.ANP_id');
        $this->db->where('bj.bj_bundling_key',$uniqcode);
        $this->db->where_in('a.ANP_progres', array('1', '2' , '3' , '0'));
        return $this->db->get()->result_array();
    }

    public function get_choosen_ANP($dataANP)
    {

       
        return DB::table('tb_assign_nesting_programmer AS a')
        ->distinct()
        ->select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'a.ANP_qty', 'a.ANP_urgency', 'a.ANP_id', 'c.customer_name as customer')
        ->join('tb_base_table as bt', 'bt.mppid', '=', 'a.ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer as b', 'b.mapping_pro', '=', 'a.ANP_data_PRO')
        ->leftJoin('tb_mapping_pro_customer as ba', 'ba.mapping_pn', '=', 'bt.PN')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'b.mapping_customer_id')
        ->where('a.ANP_id', $dataANP)
        ->get()
        ->toArray();
    }

    function get_uniq_code($kodemesin , $forGL = null){
        $this->db->distinct();
        $this->db->select('bj.bj_bundling_key');
        $this->db->from('tb_bundling_job AS bj');
        $this->db->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id = a.ANP_id');
        $this->db->where('a.ANP_mesin_kode_mesin',$kodemesin);
        $this->db->where_in('a.ANP_progres', array('1', '2' , '3' , '0'));          
        
        return $this->db->get()->result_array();
    }

    function get_detail_data_uniq_code(){
        $this->db->distinct();
        $this->db->select('bj.bj_bundling_key ,  bt.PN , bt.PRONumber , bt.PartNumberComponent , a.ANP_qty , a.ANP_progres , a.ANP_id ');
        $this->db->from('tb_bundling_job AS bj');
        $this->db->join('tb_assign_nesting_programmer AS a' , 'bj.bj_anp_id = a.ANP_id');
        $this->db->join('tb_base_table as bt', 'bt.mppid = a.ANP_key_IMA');
        return $this->db->get()->result_array();
    }

    function add_bundling($params)
    {
        $this->db->insert('tb_bundling_job',$params);
        return $this->db->insert_id();
    }
}
