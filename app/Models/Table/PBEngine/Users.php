<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $connection = 'mysql';
    protected $table = 'users';

    public function detailUserProcess()
    {
        return $this->hasOne(TbDetailUserProcessGroup::class, 'DOPG_user_id', 'id');
    }
}