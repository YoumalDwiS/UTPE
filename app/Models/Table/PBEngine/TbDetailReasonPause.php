<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetailReasonPause extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'DRP_id';
    protected $table = 'tb_detail_reason_pause';

    protected $guarded = ['DRP_id'];

    public $timestamps = false;

}
