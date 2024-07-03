<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbUnit extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_unit';

    protected $guarded = ['id'];

    public function progressProduct()
    {
        return $this->hasMany(TbProgressProduct::class);
    }
}
