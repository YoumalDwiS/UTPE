<?php

namespace App\Models\Procedure\PBEngine;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpGetRequirementRawMaterial extends Model
{
    protected $connection = 'mysqlpbengine';

    public static function get($pro){
        $arr = DB::select("call pbengine.sp_get_requirement_raw_material(".$pro.")" );
        return $arr;
    }
}
