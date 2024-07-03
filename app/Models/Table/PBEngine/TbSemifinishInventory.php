<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbSemifinishInventory extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_semifinish_inventory';

    protected $guarded = ['id'];
}
