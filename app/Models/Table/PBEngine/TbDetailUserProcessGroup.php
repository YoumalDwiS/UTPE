<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetailUserProcessGroup extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'DOPG_id';
    protected $table = 'tb_detail_user_process_group';

    protected $guarded = ['DOPG_id'];

    public $timestamps = false;

    public function process()
    {
        return $this->belongsTo(TbProcess::class, 'DOPG_Process_id', 'proses_id');
    }

    public function user()
{
    return $this->belongsTo(Users::class, 'DOPG_user_id', 'id');
}

    function get_OPG_by_userID($Oid)
    {
        $query=$this->select('a.*' , 'c.process_name')
        ->from('tb_detail_user_process_group AS a')
        ->join('tb_process AS c', 'c.proses_id', '=', 'a.DOPG_Process_id')
        ->where('DOPG_user_id',$Oid);
        return $query->get()->toArray();
    }
}
