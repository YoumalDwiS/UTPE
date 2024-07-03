<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMemoPPC extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_memo_ppc';

    protected $guarded = [''];

}
