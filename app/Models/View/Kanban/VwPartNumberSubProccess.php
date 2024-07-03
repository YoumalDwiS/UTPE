<?php

namespace App\Models\View\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwPartNumberSubProccess extends Model
{
    protected $connection = 'mysqlkanban';
    protected $table = 'vw_part_number_subproses';
}
