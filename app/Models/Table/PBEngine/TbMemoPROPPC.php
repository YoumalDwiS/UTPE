<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Model;
use App\Models\Table\PBEngine\TbMemoPPC;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TbMemoPROPPC extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_memo_pro_ppc';

    protected $guarded = [''];

    public function memo()
    {
        return $this->belongsTo(TbMemoPPC::class);
    }

    
}
