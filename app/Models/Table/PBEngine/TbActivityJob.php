<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbActivityJob extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'aj_id';
    protected $table = 'tb_activity_job';

    protected $guarded = ['aj_id'];

    public $timestamps = false;


    function get_job_by_anp($anpid)
    {
        $query=$this->select('a.Job_id' , 'a.Job_category')
        ->from('tb_job AS a')
        ->where('a.Job_ANP_id', $anpid)
        ->where('a.job_redo_id', 'is', 'null') 
        ->orderBy('a.modified_at', 'desc');
        return $query->get()->toArray(); 
    }

    
    function getActivityJobByJobidTop1($job_id)
    {
        $query = DB::table('tb_activity_job AS a')
        ->select('a.*', 'j.RP_name', 'a.modified_at', 'a.created_at', 'o.user_nama')
        ->leftJoin('tb_detail_reason_pause AS rp', 'rp.DRP_aj_id', '=', 'a.aj_id')
        ->leftJoin('tb_reason_pause AS j', 'j.RP_id', '=', 'rp.DRP_rp_id')
        ->leftJoin('tb_job AS job', 'job.Job_id', '=', 'a.aj_job_id')
        ->leftJoin('tb_operator_working AS ow', 'ow.ow_job_id', '=', 'job.Job_id')
        ->leftJoin('tb_user AS o', 'o.user_id', '=', 'ow.ow_user_id')
        ->where('a.aj_job_id', $job_id)
        ->orderBy('a.created_at', 'desc')
        ->get();
        
    return $query->toArray();
    }

    public function get_activity_job_by_jobid($jobid) {
        $query = $this->select('tb_activity_job.*', 'j.RP_name', 'tb_activity_job.modified_at', 'tb_activity_job.created_at', 'o.user_nama')
            ->leftJoin('tb_detail_reason_pause AS rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
            ->leftJoin('tb_reason_pause AS j', 'j.RP_id', '=', 'rp.DRP_rp_id')
            ->leftJoin('tb_user AS o', 'o.user_employe_number', '=', 'tb_activity_job.modified_by')
            ->where('tb_activity_job.aj_job_id', $jobid)
            ->orderBy('tb_activity_job.created_at', 'desc')
            ->get();

        return $query->toArray();
    }

    function add_activity_job($params){
        return self::create($params);
    }


    function get_user_working_by_jobid($jobid, $opid = null)
    {
        $query = $this->select('o.*')
        ->from('tb_operator_working AS op')
        ->join('tb_user as o' , 'o.user_id', '=', 'op.ow_user_id')
        ->where('op.ow_job_id', $jobid);
        if($opid != null){
            $query->where('o.user_employe_number', $opid);
        } 
        return $query->get()->toArray(); 
    }

    function get_user_working_by_anpid($anpid , $cat = null)
    {
        if($cat == 'bundling'){
            $query= $this->select('o.*' ,  'bj.bj_bundling_key');
        }else{
            $query= $this->select('o.*' ,  'op.ow_job_id');
        }
        $query->from('tb_operator_working AS op')
        ->join('tb_user as o' , 'o.user_id', '=', 'op.ow_user_id' , 'left')
        ->join('tb_job AS job', 'job.Job_id' ,'=', 'op.ow_job_id', 'left');
        if($cat == 'bundling'){
            $query->join('tb_assign_nesting_programmer as anp' , 'anp.ANP_id', '=', 'job.Job_ANP_id')
            ->join('tb_bundling_job as bj' , 'anp.ANP_id', '=', 'bj.bj_anp_id');
        }
        $query->where('job.Job_ANP_id', $anpid); 
        return $query->get()->toArray(); 
    }

    
    function get_user_working_by_r_id($r_id , $cat = null)
    {
        if($cat == 'bundling'){
            $query=$this->select('o.*' ,  'bjr.brj_bundling_key');
        }else{
            $query=$this->select('o.*' ,  'op.ow_job_id');
        }
        $query->from('tb_operator_working AS op')
        ->join('tb_user as o' , 'o.user_id', '=', 'op.ow_user_id' , 'left')
        ->join('tb_job AS job', 'job.Job_id', '=', 'op.ow_job_id', 'left');
        if($cat == 'bundling'){
            $query->join('tb_redo as redo' , 'redo.r_id' ,'=', 'job.job_redo_id')
            ->join('tb_bundling_redo_job as bjr' , 'redo.r_id' ,'=', 'bjr.brj_r_id');
        }
        $query->where('job.job_redo_id', $r_id); 
        return $query->get()->toArray(); 
    }

    function getFinishQtyByAnpid($anpid)
    {
        $result = DB::table('tb_activity_job AS a')
        ->select(DB::raw('SUM(a.aj_qty) as aj_qty_sum'))
        ->join('tb_job AS j', 'j.Job_id', '=', 'a.aj_job_id')
        ->where('j.Job_ANP_id', $anpid)
        ->where('a.aj_activity', '=', '2')
        ->where('j.Job_category', '=', '1')
        ->whereNull('j.job_redo_id')
        ->get();

        return $result->toArray();
    }

    function getJobByAnpArray($anpid)
    {
        $result = DB::table('tb_job AS a')
            ->select('a.Job_id')
            ->where('a.Job_ANP_id', $anpid)
            ->whereNull('a.job_redo_id')
            ->orderBy('a.modified_at', 'desc')
            ->get()
            ->toArray();

        return $result; 
    }


    function getDataHour($anpid){
        $query = $this->select('a.*')
        ->from('view_data_working_hour_by_anpid AS a')
        ->where('a.ANP_id', $anpid)
        ->first(); // Menggunakan first() untuk mengambil satu objek

        return $query; // Mengembalikan objek atau null jika tidak ditemukan
    }

    function update_job($id, $params){
        return TbJob::where('Job_id', $id)->update($params);
    }

    public function get_finished_activity($anpid){
        return DB::table('tb_job AS a')
            ->select('a.*', 'aj.aj_qty')
            ->leftJoin('tb_activity_job AS aj', 'aj.aj_job_id', '=', 'a.Job_id')
            ->where('aj.aj_activity', 2)
            ->where('a.Job_ANP_id', $anpid)
            ->whereNull('a.job_redo_id')
            ->get()
        ->toArray();
    }

    public function get_manhour_by_jobid($jobid){
        return DB::table('view_data_working_hour_by_jobid AS a')
            ->select('a.ActualManHourTotal', 'a.jc')
            ->where('a.Job_id', $jobid)
            ->first();
    }

  




}
