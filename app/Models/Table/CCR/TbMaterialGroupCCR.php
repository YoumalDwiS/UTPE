<?php

namespace App\Models\Table\CCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMaterialGroupCCR extends Model
{
    protected $connection = 'mysqlccr';
    protected $table = 'md_material_group';

    protected $guarded = [''];

    public function inventori()
    {
        return $this->hasMany(TbInventoriCCR::class);
    }
}
