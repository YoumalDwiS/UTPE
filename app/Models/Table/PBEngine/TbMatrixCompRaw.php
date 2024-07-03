<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMatrixCompRaw extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_matrix_comp_raw';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function component()
    {
        return $this->belongsTo(TbComponent::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(TbRawMaterial::class);
    }
}
