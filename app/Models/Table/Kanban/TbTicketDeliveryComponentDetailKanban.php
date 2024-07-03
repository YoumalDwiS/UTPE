<?php

namespace App\Models\Table\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbTicketDeliveryComponentDetailKanban extends Model
{
    protected $connection = 'mysqlkanban';
    protected $table = 'ticket_delivery_component_detail';

    protected $guarded = ['id'];

    public function memoComponent()
    {
        return $this->belongsTo(TbMemoComponentKanban::class, 'id_memo_component');
    }
}
