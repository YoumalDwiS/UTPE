<?php

namespace App\Models\Table\CCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMaterialCCR extends Model
{
    protected $connection = 'mysqlccr';
    protected $table = 'md_material';

    protected $guarded = [''];
}
