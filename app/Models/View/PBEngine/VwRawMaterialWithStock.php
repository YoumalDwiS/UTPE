<?php

namespace App\Models\View\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwRawMaterialWithStock extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'vw_raw_material_with_stock';
}
