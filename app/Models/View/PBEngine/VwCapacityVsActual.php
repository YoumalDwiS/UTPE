<?php

namespace App\Models\View\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwCapacityVsActual extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'view_chart_capacity_vs_actual';
}
