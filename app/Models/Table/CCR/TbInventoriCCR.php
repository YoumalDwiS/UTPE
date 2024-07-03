<?php

namespace App\Models\Table\CCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbInventoriCCR extends Model
{
    protected $connection = 'mysqlccr';
    protected $table = 'md_inventory';

    protected $guarded = [''];

    public function materialGroup()
    {
        return $this->belongsTo(TbMaterialGroupCCR::class, 'material_group', 'material_group');
    }
}
