<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCartMaterial extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_cart_material';

    protected $guarded = ['id'];
    public $timestamps = false;
}
