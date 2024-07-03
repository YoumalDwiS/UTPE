<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbJobIssue extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'issue_id';
    protected $table = 'tb_job_issue';

    protected $guarded = ['issue_id'];
    public $timestamps = false;

    public function job_issue_by_anp($anp_id){
        $result = DB::table('tb_job_issue AS a')
        ->select('a.*')
        ->where('a.issue_anp_id', $anp_id)
        ->where('a.issue_delete_status', 0)
        ->get();

        return $result->toArray();

    }
}
