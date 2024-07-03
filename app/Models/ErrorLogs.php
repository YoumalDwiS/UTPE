<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLogs extends Model
{

  protected $table = 'error_logs';

  protected $fillable = [
    'id', 'remote_addr', 'action', 'code', 'message', 'ex_string', 'apps', 'created_by'
  ];
}
