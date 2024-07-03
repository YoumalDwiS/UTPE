<?php

namespace App\Models\Table\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbMemoReferenceKanban extends Model
{
    protected $connection = 'mysqlkanban';
    protected $table = 'memo_reference';

    protected $guarded = ['id'];

    public function memo()
    {
        return $this->belongsTo(TbMemoKanban::class, 'id_memo_reference');
    }
}
