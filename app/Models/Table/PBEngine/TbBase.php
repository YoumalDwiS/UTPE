<?php

namespace App\Models\Table\PBEngine;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbBase extends Model
{
    protected $connection = 'mysqlpbengine';
    protected $primaryKey = 'id';
    protected $table = 'tb_base_table';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function getRowAmountData($cat)
    {
        $date = now();
        if ($cat == 1) {
            $date->addDay();
        }
        $dateParam = $date->format('Y-m-d');
        if ($cat == 4) {
            $dateParam = $date->format('m');
        }

        switch ($cat) {
            case 1:
                return DB::table('tb_base_table AS a')
                    ->whereDate('PlanStartdate', $dateParam)
                    ->distinct()
                    ->select('a.*')
                    ->count();
                break;
            case 2:
                return DB::table('tb_assign_nesting_programmer AS a')
                    ->where('ANP_progres', 1)
                    ->distinct()
                    ->select('a.*')
                    ->count();
                break;
            case 3:
                return DB::table('tb_assign_nesting_programmer AS a')
                    ->where('ANP_progres', 2)
                    ->distinct()
                    ->select('a.*')
                    ->count();
                break;
            case 4:
                return DB::table('tb_assign_nesting_programmer AS a')
                    ->where('ANP_progres', 4)
                    ->whereMonth('ANP_modified_at', $dateParam)
                    ->distinct()
                    ->select('a.*')
                    ->count();
                break;
            default:
                return 0;
                break;
        }
    }

    public function getAllSidebar()
    {
        return $this->get()->toArray();
    }

    public function getBaseData($perPage = null, $start = null, $category = null, $param = null)
    {
        $date =  new Carbon();
        $date->modify('+1 day');
        $dateParam = $date->format('Y-m-d');

        $query = DB::table('tb_base_table AS a')->distinct();

        if (!empty(session('categorysorting')) && !empty(session('orderingsorting'))) {
            if (in_array(session('categorysorting'), ["ANP_progres", "mesin_nama_mesin", "customer_name"])) {
                $query->leftJoin('tb_assign_nesting_programmer AS anp', 'anp.ANP_key_IMA', '=', 'a.mppid')
                      ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'a.PRONumber')
                      ->leftJoin('tb_customer AS c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
                      ->leftJoin('tb_mesin AS m', 'm.mesin_kode_mesin', '=', 'anp.ANP_mesin_kode_mesin')
                      ->orderBy('a.' . session('categorysorting'), session('orderingsorting'));
            } else {
                $query->orderBy('a.' . session('categorysorting'), session('orderingsorting'));
            }
        }

        if (!empty(session('ddfiltercategory')) && !empty(session('ddkeyword'))) {
            if (in_array(session('ddfiltercategory'), ["ANP_progres", "mesin_nama_mesin", "customer_name"])) {
                $query->leftJoin('tb_assign_nesting_programmer AS anp', 'anp.ANP_key_IMA', '=', 'a.mppid')
                      ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'a.PRONumber')
                      ->leftJoin('tb_customer AS c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
                      ->leftJoin('tb_mesin AS m', 'm.mesin_kode_mesin', '=', 'anp.ANP_mesin_kode_mesin')
                      ->where('a.' . session('ddfiltercategory'), session('ddkeyword'));
            } else {
                $query->where('a.' . session('ddfiltercategory'), session('ddkeyword'));
            }
        }

        if (!empty(session('startdate')) && !empty(session('enddate'))) {
            $query->whereDate('a.PlanStartdate', '>=', session('startdate'))
                  ->whereDate('a.PlanStartdate', '<=', session('enddate'));
        }

        if (!empty(session('filterProcessName'))) {
            $query->whereIn('a.ProcessName', session('filterProcessName'));
        }

        if (!empty(session('filterMaterial'))) {
            $query->whereIn('a.MaterialName', session('filterMaterial'));
        }

        if (!empty(session('filterThickness'))) {
            $query->whereIn('a.Thickness', session('filterThickness'));
        }

        if (!empty(session('filterPartNumberProduct'))) {
            $query->whereIn('a.PN', session('filterPartNumberProduct'));
        }

        switch ($category) {
            case 0:
                if (session('start') == session('end')) {
                    $query->whereDate('a.PlanStartdate', session('start'));
                } else {
                    $query->whereDate('a.PlanStartdate', '>=', session('start'))
                          ->whereDate('a.PlanStartdate', '<=', session('end'));
                }
                break;
            case 1:
                $query->whereDate('a.PlanStartdate', now()->toDateString());
                break;
            case 3:
                switch (session('ddfilter')) {
                    case 1:
                        $query->where('a.PN', 'like', '%' . session('keyword') . '%');
                        break;
                    case 2:
                        $query->where('a.productname', session('keyword'));
                        break;
                    case 3:
                        $query->where('a.PRONumber', 'like', '%' . session('keyword') . '%');
                        break;
                    case 4:
                        $query->where('a.PartNumberComponent', session('keyword'));
                        break;
                }
                break;
            case 99:
                $query->whereDate('a.PlanStartdate', $dateParam);
                break;
        }

        // return $query->limit($perPage)->offset($start)->get()->toArray();
        return $query->limit(10)->get()->toArray();
    }

    public function get_data_for_list99($date){


        return DB::table('tb_base_table as a')
        ->select('a.*')
        ->whereDate('a.PlanStartdate', $date)
        ->distinct()
        ->get();
        // $date = new Carbon();
        // $date->modify('+1 day');
        // $dateParam = $date->format('Y-m-d');

        // return $this->whereDate('PlanStartdate', '=', $dateParam)->distinct()->get();

    }

    public function get_data_for_list1(){
        $data = DB::table('tb_assign_nesting_programmer as a')
                ->leftJoin('tb_base_table as b', 'b.mppid', '=', 'a.ANP_key_IMA')
                ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro', '=', 'b.PRONumber')
                ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
                ->select('a.*', 'b.*', 'c.customer_name')
                ->where('a.ANP_progres', 1)
                ->distinct()
                ->get();

        return $data;
        
    }

    public function get_data_for_list2($date){
       

        $data = DB::table('tb_assign_nesting_programmer as a')
        ->leftJoin('tb_base_table as b', 'b.mppid', '=', 'a.ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro', '=', 'b.PRONumber')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
        ->select('a.*', 'b.*', 'c.customer_name')
        ->where('a.ANP_progres', 4)
        ->whereMonth('ANP_modified_at', '=', $date)
        ->distinct()
        ->get();
    
        return $data;            


    }

    public function get_data_for_list($cat)
    {
        $date = new Carbon();
        if ($cat == 99) {
            $date->modify('+1 day');
        }
        $dateParam = $date->format('Y-m-d');
        if ($cat == 2) {
            $dateParam = $date->format('m');
        }

        switch ($cat) {
            case 99:
                return $this->whereDate('PlanStartdate', '=', $dateParam)->distinct()->get();
                break;
            case 1:
                return $this->leftJoin('tb_base_table AS b', 'b.mppid', '=', 'a.ANP_key_IMA')
                    ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'b.PRONumber')
                    ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
                    ->where('ANP_progres', '=', 1)
                    ->distinct()
                    ->get();
                break;
            case 2:
                return $this->leftJoin('tb_base_table AS b', 'b.mppid', '=', 'a.ANP_key_IMA')
                    ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'b.PRONumber')
                    ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
                    ->where('ANP_progres', '=', 4)
                    ->whereMonth('ANP_modified_at', '=', $dateParam)
                    ->distinct()
                    ->get();
                break;
            default:
                return [];
                break;
        }
    }

    public function get_data_by_mppid($mppid){
        return DB::table('tb_base_table as a')
        ->select('a.*')
        ->where('a.mppid', $mppid)
        ->get()
        ->toArray();
    }

    public function update_base_data($id, $params){
        $baseData = self::find($id);
        if ($baseData) {
            return $baseData->update($params);
        }
        return false;
    }

    

}
