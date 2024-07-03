<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbComponent extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_component';

    protected $guarded = [''];

    public function matrixProdComp()
    {
        return $this->hasMany(TbMatrixProdComp::class);
    }

    public function matrixCompRaw()
    {
        return $this->hasMany(TbMatrixCompRaw::class);
    }

    public function progressProduct()
    {
        return $this->hasMany(TbProgressProduct::class);
    }
}
