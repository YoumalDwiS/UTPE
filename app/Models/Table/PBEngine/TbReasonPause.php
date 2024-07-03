<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TbReasonPause extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'RP_id';
    protected $table = 'tb_reason_pause';

    protected $guarded = ['RP_id'];
    public $timestamps = false;
    
    protected $attributes = [
        // atur nilai default untuk 'sfc_delete_status' ke 0
        'RP_status'=>0 ];
}
