<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbOperatorWorking extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'ow_id';
    protected $table = 'tb_operator_working';

    protected $guarded = ['ow_id'];

    public $timestamps = false;
}
