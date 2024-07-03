<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbSemifinishLog extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_semifinish_log';

    protected $guarded = ['id'];
}
