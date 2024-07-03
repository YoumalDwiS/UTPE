<?php

namespace App\Models\View\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwUserProcessGroup extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'vw_user_process_group';

    
}
