<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbBundlingStartStopRedo extends Model
{
    use HasFactory;

    function get_redoid_by_keybundling($keybundling){
        $this->db->select('bj.brj_r_id');
        $this->db->from('tb_bundling_redo_job AS bj');
        $this->db->where('bj.brj_bundling_key',$keybundling);
        return $this->db->get()->result_array();
    }

    function get_key_bundling_by_redoid($r_id){
        $this->db->select('bj.brj_bundling_key');
        $this->db->from('tb_bundling_redo_job AS bj');
        $this->db->where('bj.brj_r_id',$r_id);
        $this->db->where('bj.brj_job_status',0);
        return $this->db->get()->row_array(); 
    }

    function get_uniq_code($kodemesin , $forGL = null){
        $this->db->distinct();
        $this->db->select('bj.brj_bundling_key');
        $this->db->from('tb_bundling_redo_job AS bj');
        $this->db->join('tb_redo AS a' , 'bj.brj_r_id = a.r_id');
        $this->db->where('a.r_kode_mesin',$kodemesin);
        if($forGL == null){
            $this->db->where_in('a.r_progres', array('1', '2' , '3' , '0'));
        }
        
        return $this->db->get()->result_array();
    }

    function get_bundling_by_uniqcode_redo($uniqcode){
        $this->db->distinct();
        $this->db->select('bj.brj_r_id ,  j.Job_id as bj_job_id , redo.r_progres');
        $this->db->from('tb_bundling_redo_job AS bj');
        $this->db->join('tb_redo AS redo' , 'bj.brj_r_id = redo.r_id');
        $this->db->join('tb_job as j' , 'j.job_redo_id = redo.r_id and j.job_bundling_code = bj.brj_bundling_key');
        $this->db->where('bj.brj_bundling_key',$uniqcode);
        return $this->db->get()->result_array();
    }

    function get_detail_data_uniq_code(){
        $this->db->distinct();
        $this->db->select('bj.brj_bundling_key , r.r_id, r.r_progres , r_category, r.r_qty , bt.PN , bt.PRONumber , bt.PartNumberComponent , a.ANP_qty , a.ANP_progres , a.ANP_id ');
        $this->db->from('tb_bundling_redo_job AS bj');
        $this->db->join('tb_redo as r' , 'r.r_id = bj.brj_r_id');
        $this->db->join('tb_assign_nesting_programmer AS a' , 'r.r_anp_id = a.ANP_id');
        $this->db->join('tb_base_table as bt', 'bt.mppid = a.ANP_key_IMA');
        return $this->db->get()->result_array();
    }

    function get_choosen_redo($dataREDO)
    {
        $this->db->distinct();
        $this->db->select('redo.r_id , redo.r_category , bt.PN , bt.PRONumber , bt.PartNumberComponent , redo.r_qty , redo.r_qty_finish , c.customer_name as customer');
        $this->db->from('tb_redo AS redo');
        $this->db->join('tb_assign_nesting_programmer as a' , 'a.ANP_id = redo.r_anp_id' );
        $this->db->join('tb_base_table as bt', 'bt.mppid = a.ANP_key_IMA');
        $this->db->join('tb_mapping_pro_customer as b', 'b.mapping_pro = a.ANP_data_PRO' , 'left');
        $this->db->join('tb_mapping_pro_customer as ba', 'ba.mapping_pn = bt.PN', 'left');
        $this->db->join('tb_customer as c', 'c.customer_id = b.mapping_customer_id', 'left');

        $this->db->where_in('redo.r_id',$dataREDO);

        return $this->db->get()->result_array();
    }
    

    function add_bundling_redo($params)
    {
        $this->db->insert('tb_bundling_redo_job',$params);
        return $this->db->insert_id();
    }
}
