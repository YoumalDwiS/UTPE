<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Model;
use App\Models\Table\PBEngine\TbRawMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TbMaterial extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_material';

    protected $guarded = [''];

    public function component()
    {
        return $this->hasMany(Component::class);
    }

    public function rawMaterial()
    {
        return $this->hasMany(TbRawMaterial::class);
    }
}
