<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbCustomer extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'customer_id';
    protected $table = 'tb_customer';

    protected $guarded = ['customer_id'];

    public $timestamps = false;

    protected $attributes = [
        // atur nilai default untuk 'sfc_delete_status' ke 0
        'customer_delete_status' => 0
    ];
}
