<?php

namespace App\Models\Table\Kanban;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbTicketDeliveryComponentKanban extends Model
{
    protected $connection = 'mysqlkanban';
    protected $table = 'ticket_delivery_component';

    protected $guarded = ['id'];
}
