<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbProcess extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'process_id';
    protected $table = 'tb_process';

    protected $guarded = ['process_id'];

    public $timestamps = false;

    public function detailUserProcessGroups()
    {
        return $this->hasMany(TbDetailUserProcessGroup::class, 'DOPG_Process_id', 'proses_id');
    }

}
