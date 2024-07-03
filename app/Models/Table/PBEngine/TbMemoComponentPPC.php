<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMemoComponentPPC extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_memo_component_ppc';

    protected $guarded = [''];
}
