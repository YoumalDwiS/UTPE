<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMappingProCustomer extends Model
{
   

    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_mapping_pro_customer';
    protected $primaryKey = 'mapping_id';

    protected $guarded = ['mapping_id'];
}
