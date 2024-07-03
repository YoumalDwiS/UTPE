<?php

namespace App\Models\View\CCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwCalaculateStock extends Model
{
    protected $connection = 'mysqlccr';
    protected $table = 'vw_calculate_stock';

    protected $guarded = [''];
}
