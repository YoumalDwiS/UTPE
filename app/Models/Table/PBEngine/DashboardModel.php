<?php

namespace App\Models\Table\PBEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardModel extends Model
{
    protected $connection = 'mysqlpbengine';


    public function getActualVsCapacity()
    {
        return DB::table('view_chart_capacity_vs_actual AS a')
            ->select('a.*')
            ->get()
            ->toArray();
    }

    public function getQtyAssignAndFinish($mppid = null)
    {
        return DB::table('tb_assign_nesting_programmer AS a')
            ->selectRaw('SUM(IF(a.ANP_note IS NULL, a.ANP_qty, a.ANP_qty_finish)) as assignqty, SUM(a.ANP_qty_finish) as finishedqty')
            ->where('a.ANP_key_IMA', $mppid)
            ->groupBy('a.ANP_key_IMA')
            ->first();
    }
    

  
}
