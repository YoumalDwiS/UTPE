<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbJob extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'Job_id';
    protected $table = 'tb_job';

    protected $guarded = ['Job_id'];

    public $timestamps = false;

    // public function get_job_by_anp($anpid) {
    //     return DB::table('tb_job AS a')
    //         ->select('a.Job_id', 'a.Job_category')
    //         ->where('a.Job_ANP_id', $anpid)
    //         ->whereNull('a.job_redo_id')
    //         ->orderBy('a.modified_at', 'desc')
    //         ->first(); // Menggunakan first() karena mengharapkan satu hasil
    // }

    public function get_job_by_anp($anpid) {
        $result = DB::table('tb_job AS a')
            ->select('a.Job_id', 'a.Job_category')
            ->where('a.Job_ANP_id', $anpid)
            ->whereNull('a.job_redo_id')
            ->orderBy('a.modified_at', 'desc')
            ->first(); // Menggunakan first() karena mengharapkan satu hasil
    
        if ($result) {
            return json_decode(json_encode($result), true);
        }
    
        return [];
    }

    // function get_job_by_anp($anpid)
    // {
    //     $query=$this->select('a.Job_id' , 'a.Job_category')
    //     ->from('tb_job AS a')
    //     ->where('a.Job_ANP_id', $anpid)
    //     ->where('a.job_redo_id', 'is', 'null') 
    //     ->orderBy('a.modified_at', 'desc')
    //     ->first();
    //     return $query; 
    // }
}
