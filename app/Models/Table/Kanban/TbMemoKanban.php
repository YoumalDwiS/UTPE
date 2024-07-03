<?php

namespace App\Models\Table\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMemoKanban extends Model
{
    protected $connection = 'mysqlkanban';
    protected $table = 'memo';

    protected $guarded = ['id'];

    public function memoReference()
    {
        return $this->hasOne(TbMemoReferenceKanban::class, 'id_memo');
    }
}
