<?php

namespace App\Models\View\CCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwStockRawMaterial extends Model
{
    protected $connection = 'mysqlccr';
    protected $table = 'vw_stock_raw_material';

    protected $guarded = [''];
}
