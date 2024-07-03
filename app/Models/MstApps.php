<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstApps extends Model
{

  protected $table = 'apps';

  protected $fillable = [
    'id', 'app_name', 'link', 'logo', 'status'
  ];
}
