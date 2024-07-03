<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbNotification extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_notification';

    protected $guarded = [''];

    public function reader()
    {
        return $this->hasMany(TbNotificationReader::class, 'notification_id');
    }
}
