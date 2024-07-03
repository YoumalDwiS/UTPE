<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Model;

class VwPermissionGroupMenu extends Model
{

  protected $connection = 'mysql';
  protected $table = 'vw_permission_group_menu';

  // protected $fillable = [
  //   'id', 'user', 'app', 'menu', 'access', 'c', 'r', 'u', 'd', 'v', 'created_at', 'updated_at', 'app_name', 'link', 'logo', 'status', 'app_menu', 'menu_link', 'flag'
  // ];
}
