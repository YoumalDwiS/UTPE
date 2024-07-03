<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMatrixProdComp extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_matrix_prod_comp';

    protected $guarded = [''];

    public function product()
    {
        return $this->belongsTo(TbProduct::class);
    }

    public function component()
    {
        return $this->belongsTo(TbComponent::class);
    }
}
