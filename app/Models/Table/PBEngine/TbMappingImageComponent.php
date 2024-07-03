<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbMappingImageComponent extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $table = 'tb_mapping_image_component';
    protected $primaryKey = 'MIC_id';

    protected $guarded = ['MIC_id'];

    public $timestamps = false;

    protected $attributes = [
        // atur nilai default untuk 'MIC_delete_status' ke 0
        'MIC_status_delete' => 0,
        'MIC_Status_Aktifasi' => 0
    ];

    public function process()
    {
        return $this->belongsTo(TbProcess::class, 'MIC_PN_component', 'pn_component');
    }

    function get_MappingImage_by_PNc_PNp($cPN , $pPN)
    {
        $query = DB::table('tb_mapping_image_component AS a')
        ->select('a.*')
        ->where('a.MIC_PN_component', $cPN)
        ->where('a.MIC_PN_product', $pPN)
        ->get();

        return $query->toArray();
    }


}
