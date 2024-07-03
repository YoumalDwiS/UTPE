<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbProgressProduct extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_progress_product';

    protected $guarded = ['id'];

    public function component()
    {
        return $this->belongsTo(TbComponent::class);
    }

    public function unit()
    {
        return $this->belongsTo(TbUnit::class);
    }
}
