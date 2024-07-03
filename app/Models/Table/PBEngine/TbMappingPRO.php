<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbMappingPRO extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'mapping_id';
    protected $table = 'tb_mapping_pro_customer';

    protected $guarded = ['mapping_id'];

    public $timestamps = false;

    public function get_all_mapping(){
        return DB::table('tb_mapping_pro_customer AS a')
        ->select('a.*', 'b.customer_name')
        ->join('tb_customer AS b', 'b.customer_id', '=', 'a.mapping_customer_id')
        ->where('a.mapping_delete_status', 0)
        ->get()
        ->toArray();

    }
    

}
