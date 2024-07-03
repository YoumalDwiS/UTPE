<?php

namespace App\Models\Table\PBEngine;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbNotificationReader extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_notification_reader';

    protected $guarded = [''];
    public $timestamps = false;

    public function notification()
    {
        return $this->belongsTo(TbNotification::class, 'notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'read_by');
    }
}
