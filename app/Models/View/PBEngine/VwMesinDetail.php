<?php

namespace App\Models\View\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwMesinDetail extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'vw_mesin_detail';
}
