<?php

namespace App\Models\Table\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMemoComponentKanban extends Model
{
    protected $connection = 'mysqlkanban';
    protected $table = 'memo_component';

    protected $guarded = [''];
}
