<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Model;
use App\Models\Table\PBEngine\TbMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TbRawMaterial extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_raw_material';

    protected $guarded = ['id'];

    public function material()
    {
        return $this->belongsTo(TbMaterial::class);
    }

    public function matrixCompRaw()
    {
        return $this->hasMany(TbMatrixCompRaw::class);
    }
}
