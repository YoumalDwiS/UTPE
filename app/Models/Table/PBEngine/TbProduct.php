<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbProduct extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_product';

    protected $guarded = [''];

    public function matrixProdComp()
    {
        return $this->hasMany(TbMatrixProdComp::class);
    }
}
