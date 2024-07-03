<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbUser extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'user_id';
    protected $table = 'tb_user';

    protected $guarded = ['user_id'];

    public $timestamps = false;

    protected $attributes = [
        // atur nilai default untuk 'sfc_delete_status' ke 0
        'user_IsActive' => 0
    ];

    function get_user_by_employeenumber($EN)
    {
        $query = $this->select('a.*')
        ->from('tb_user AS a')
        ->where('a.user_employe_number', $EN);
        //  $this->db->where('a.user_IsActive', 0); 
        return $query->get()->toArray();
    }

    function update_user($id, $params)
    {
        return self::where('user_id', $id)
        ->update($params);
    }

//     public function processes()
// {
//     return $this->belongsToMany(TbProcess::class, 'user_process', 'user_id', 'process_id');
// }
}
