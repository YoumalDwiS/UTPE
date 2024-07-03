<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TbDetailMesin extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'DM_id';
    protected $table = 'tb_detail_mesin';

    protected $guarded = ['DM_id'];
    public $timestamps = false;

    public function detailUserProcessGroup()
{
    return $this->hasOne(TbDetailUserProcessGroup::class, 'DOPG_Process_id', 'DM_process_id');
}

    // protected $attributes = [
    //     // atur nilai default untuk 'sfc_delete_status' ke 0
    //     'mesin_status'=>0 ];
    
  
}