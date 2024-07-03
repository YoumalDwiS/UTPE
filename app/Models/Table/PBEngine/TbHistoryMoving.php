<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbHistoryMoving extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'mv_id';
    protected $table = 'tb_history_moving';

    protected $guarded = ['mv_id'];
    public $timestamps = false;
}