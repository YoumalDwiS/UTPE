<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbComponent;
use App\Models\Table\PBEngine\TbCustomer;
use App\Models\Table\PBEngine\TbMappingImageComponent;
use App\Models\Table\PBEngine\TbMesin;
use App\Models\Table\PBEngine\TbMappingPRO;
use App\Models\MstApps;
use App\Models\Table\PBEngine\DashboardModel;
use App\Models\Table\PBEngine\MoveQTY_model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\View\PBEngine\VwAssignMesin;
use App\Models\View\PBEngine\VwCapacityVsActual;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FilteredDataExport;


class DashboardController extends Controller
{

    public function test(Request $request){


        $perPage = 10; // Jumlah data per halaman
    
        $data = TbBase::paginate($perPage);
        $resultArray = [];

        foreach($data as $item) {
             $anp = TbAssign::where('ANP_key_IMA', $item->mppid)
            ->get();
            $item->setAttribute('ANP', $anp->toArray());

            $msn = [];
            foreach($anp as $assign) {
                $mesin = TbMesin::where('mesin_kode_mesin', $assign->ANP_mesin_kode_mesin)
                                ->where('mesin_status', 0)
                                ->first();
                if($mesin) {
                    $msn[] = $mesin->toArray();
                }
            }
            $item->setAttribute('MSNs', $msn);

            $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
                ->where('MIC_PN_product', $item->PN)
                ->where('MIC_Status_Aktifasi', 0)
                ->get();
            $item->setAttribute('MAP', $map->toArray());

            $Actual = VwCapacityVsActual::All();
            //->get();
            $item->setAttribute('ACT', $Actual->toArray());


              // Inisialisasi variabel
                $chartmesin = array();
                $arrayovermesin = array();
                $no= 0;
                
                

                foreach ($anp as $asn) {  
                    $no++; 
                    $arrayavailablemesin = array();  
                    
                    $assignedqty = $asn['ANP_qty']; 
                    $finishedqty = $asn['ANP_qty_finish'];

                    //$result = 0; 
                    $sisa = 0; 
                    $mhperqty = 0; 
                    $nestingqty = 0; 
                    $tdresult = ''; 
                    $cek = 0;

                    if ($item['qty'] <= $finishedqty) {
                        $cek = 1;
                    } else if ($item['qty'] <= $assignedqty) {
                        $cek = 1;
                    }
                    

                    if ($cek == 0) {
                            //INI BUAT NGAMBIL DATA DARI PROCESS SEBELUMNYA
                            foreach($chartmesin as $cm )
                            {
                                if($cm['pname'] == $item['ProcessName']){
                                    $cmsisa = 0;
                                    $cmsisa = $cm['sisa'];
                                    $cmmanhourperqty = $item['MHProcess']/$item['qty'];
                                    
                                    if($cmsisa >= $cmmanhourperqty)
                                    {
                                        $tempmesin = array(
                                        'sisa' => $cmsisa,
                                        'mhperunit' => $cmmanhourperqty,
                                        'nama_mesin' => $cm['nama'],
                                        'pname' => $cm['pname'],
                                        'mthick' => $cm['tm']
                                        );
                                        array_push($arrayavailablemesin, $tempmesin);
                                    }else{
                                        $counter = 0;
                                        foreach ($arrayovermesin as $keyy => $arr){
                                            if ($arr['nama_over_mesin']  == $cm['nama']) {
                                                $counter = 1;
                                            }
                                        }     
                                    if($counter == 0){
                                        $over = array(
                                            'nama_over_mesin' => $cm['nama'],'nama_over_mesinprocess' => $cm['pname']
                                        );
                                        array_push($arrayovermesin,$over);
                                    }
                                }    
                            }  
                    }  

                    //MENCARI MESIN YANG BELOM ADA DI ARRAY AVAILABLE MESIN
                    foreach ($msn as $m) 
                    {
                        if($m['process_name'] == $item['ProcessName']){
                            if(count($arrayavailablemesin) == 0){
                                    $counter = 0;
                                    foreach ($arrayovermesin as $keyy => $arr){
                                        if ($arr['nama_over_mesin']  == $m['mesin_nama_mesin']) {
                                            $counter = 1;
                                        } 
                                    }
                                    if($counter == 0){
                                        $sisa = $m['sisa']*60;
                                        $manhourperqty = $item['MHProcess']/$item['qty'];
                                        $tempmesin = array(
                                            'sisa' => $sisa,
                                            'mhperunit' => $manhourperqty,
                                            'nama_mesin' => $m['mesin_nama_mesin'],
                                            'pname' => $m['process_name'],
                                            'mthick' =>0
                                        );
                                        array_push($arrayavailablemesin, $tempmesin);
                                    }
                            }else
                            {
                                    $counter = 0;
                                    foreach ($arrayavailablemesin as $key => $ar){
                                        if ($ar['nama_mesin']  == $m['mesin_nama_mesin']) {
                                            $counter = 1;
                                        } 
                                    }
                                    foreach ($arrayovermesin as $keyy => $arr){
                                        if ($arr['nama_over_mesin']  == $m['mesin_nama_mesin']) {
                                            $counter = 1;
                                        } 
                                    }

                                    if($counter == 0){
                                        $sisa = $m['sisa']*60;
                                        $manhourperqty = $item['MHProcess']/$item['qty'];
                                        $tempmesin = array(
                                            'sisa' => $sisa,
                                            'mhperunit' => $manhourperqty,
                                            'nama_mesin' => $m['mesin_nama_mesin'],
                                            'pname' => $m['process_name'],
                                            'mthick' =>0
                                        );
                                        array_push($arrayavailablemesin, $tempmesin);
                                    }
                                }                   
                        }
                    }    
                    $temp = array();
                    foreach ($arrayavailablemesin as $key => $row)
                    {
                        $temp[$key]  = $row['sisa'];
                    } 
                    array_multisort($temp, SORT_DESC, $arrayavailablemesin);

                    foreach($arrayavailablemesin as $lm){

                        if($no == 1){
                            $sisa = $lm['sisa'];
                            $mhperqty = $lm['mhperunit'];
                            $tdresult = $lm['nama_mesin'];
                            $nestingqty = floor($lm['sisa']/$lm['mhperunit']);
                            $qtyneeded = $item['qty'] - $assignedqty;

                            if($nestingqty > $qtyneeded){
                                $nestingqty = $qtyneeded;
                                $totalmhneed = $qtyneeded * $lm['mhperunit'];
                                $sisamh = $lm['sisa'] - $totalmhneed; 
                            }else{
                                $totalmhneed = $nestingqty * $lm['mhperunit'];
                                $sisamh = $lm['sisa'] - $totalmhneed; 
                            }
                            foreach ($chartmesin as $key => $value){
                                if ($value['nama'] == $lm['nama_mesin']) {
                                unset($chartmesin[$key]);
                                }
                            }
                            $add = array(
                                'nama' => $lm['nama_mesin'],
                                'sisa' => $sisamh,
                                'pname' => $lm['pname'],
                                'tm' => $lm['mthick'] 
                            );
                            array_push($chartmesin,$add);
                            break;
                        }
                        else{
                            $count_array = count($lm);
                            if($lm['sisa'] < 1){

                            }else
                            {
                                $sisa = $lm['sisa'];
                                $mhperqty = $lm['mhperunit'];
                                $tdresult = $lm['nama_mesin'];
                                $nestingqty = @(floor($lm['sisa']/$lm['mhperunit']));
                                $qtyneeded = $item['qty'] - $assignedqty;

                                if($nestingqty > $qtyneeded){
                                    $nestingqty = $qtyneeded;
                                    $totalmhneed = $qtyneeded * $lm['mhperunit'];
                                    $sisamh = $lm['sisa'] - $totalmhneed; 
                                }else{
                                    $totalmhneed = $nestingqty * $lm['mhperunit'];
                                    $sisamh = $lm['sisa'] - $totalmhneed; 
                                }

                                foreach ($chartmesin as $key => $value){
                                    if ($value['nama']  == $lm['nama_mesin']) {
                                        unset($chartmesin[$key]);
                                    } 
                                }

                                $add = array(
                                'nama' => $lm['nama_mesin'],
                                'sisa' => $sisamh,
                                'pname' => $lm['pname'],
                                'tm' => $lm['mthick']   
                                );
                                array_push($chartmesin,$add);

                                break;
                            }
                        }    

                    }       
            }else{
                $tdresult = 'DONE';
                $nestingqty = 0;
            }
                
          
                if($tdresult != ''){
                    $resultArray[] = [
                        'tdresult' => $tdresult,
                        'nestingqty' => $nestingqty,
                        'assignedqty' => $assignedqty,
                        'finishedqty' => $finishedqty,
                    ];  

                }else{
                    $resultArray[] = [
                        'tdresult' => 'No machine can be suggested',
                        'nestingqty' => 'None',
                        'assignedqty' => 0,
                        'finishedqty' => $finishedqty,
                    ];
                }

                $assignmesin = 0; 
                $finalAssignArray = [];
                foreach ($anp as $asgn) {
                    if ($asgn['ANP_key_IMA'] == $item['mppid']) { 
                        $assignmesin = 1;
                        $progres = null;
                        switch ($asgn['ANP_progres']) {
                            case 0: $progres = 'Not Started';
                                break;
                            case 1: $progres = 'Started';
                                break;  
                            case 2: $progres = 'Paused';
                                break;
                            case 3: $progres = 'Stopped';
                                break;  
                            case 4: $progres = 'Finished';
                                break;   
                            default:
                                // code...
                                break;

                                
                        } $msnprogres = $mesin['mesin_nama_mesin'] . ' : ' . $progres;
                    
                        if ($assignmesin == 0) {
                            echo 'Not Set';
                        }
                    }

            }

            $set = 0;
            foreach ($map as $c) {
                if ($item['PRONumber'] == $c['mapping_pro']) {
                    $customerName = $c['customer_name'];
                    $set = 1;
                    break;
                }

                if($set == 0){
                    $customerName = 'not set';
                }

                // Menambahkan data ke dalam $resultArray
            $resultArray['customer_name'] = $customerName;
            }

            if($tdresult == 'DONE'){
                $result = 'FULL ASSIGNED';
            }else{
                $buttonId = $item['mppid'] . '+' . $tdresult . '+' . $nestingqty;
                $iconId = $item['mppid'] . '/' . $tdresult;
            
                $buttonHtml = '<button data-toggle="modal" data-target="#md-assign" style="height: 30px; width: 35px;" id="' . $buttonId . '" class="btn btn-space btn-danger assign">';
                $buttonHtml .= '<i id="' . $iconId . '" data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i>';
                $buttonHtml .= '</button>';
            
                $result = $buttonHtml;

                $resultArray['result'] = $result;
            }
            


            if($assignmesin > 0){
                $temp = $item['qty'] - $finishedqty;
                if ($temp > 0) {
                    $buttonHtml = '<a data-toggle="modal" data-target="#md-moving" class="btn btn-warning moving" id="' . $item['mppid'] . '">';
                    $buttonHtml .= '<i style="color:white;" class="icon mdi mdi-rotate-ccw moving" id="' . $item['mppid'] . '"></i>';
                    $buttonHtml .= '</a>';
                } else {
                    $buttonHtml = '<a data-toggle="modal" disabled data-target="#md-moving" class="btn btn-warning moving" id="' . $item['mppid'] . '">';
                    $buttonHtml .= '<i style="color:white;" class="icon mdi mdi-rotate-ccw moving" id="' . $item['mppid'] . '"></i>';
                    $buttonHtml .= '</a>';
                }

            }else{
                $buttonHtml = '<a data-toggle="modal" disabled data-target="#md-moving" class="btn btn-warning moving" id="' . $item['mppid'] . '">';
                $buttonHtml .= '<i style="color:white;" class="icon mdi mdi-rotate-ccw moving" id="' . $item['mppid'] . '"></i>';
                $buttonHtml .= '</a>';
            }

               
            }
            $resultArray[] = [
    
                'Suggestion Assign' => $tdresult,
                'Nesting Qty' => $nestingqty,
                'Assigned Qty' => $assignedqty,
                'Finished Qty' => $finishedqty,
                'Machine' => $mesin['mesin_nama_mesin'] . ' : ' . $progres,
              //  'customer_name' => $customerName,
                'result' => $result,
                'Button ' => $buttonHtml,

            ];
            array_push($resultArray);

            dd($resultArray);
   
        }
    }

    public function index(Request $request){
        $ForMachineBreakdown = (new TbMesin())->get_breakdown_mesin_num();
        $ForTomorrow = (new TbBase())->getRowAmountData(1);
        $ForOnProgress = (new TbBase())->getRowAmountData(2);
        $ForPause = (new TbBase())->getRowAmountData(3);
        $ForFinish = (new TbBase())->getRowAmountData(4);

        $chart = (new DashboardModel())->getActualVsCapacity();
        $chartArray = json_decode(json_encode($chart), true);
        $chartmesin = array();

        //Deklarasi
        $ec = [];
        $eac = [];
        $es = [];
        $eca = [];
        $c = [];
        $ac = [];
        $ca = [];
        $s = [];

        foreach ($chartArray as $a) {
            // foreach ($chartmesin as $e) {
            //     if ($a['mesin_nama_mesin'] == $e->nama) {
            //         $ec[] = $e->nama;
            //         $engine_actual = (float)$a['capacity'] - ($e->sisa / 60);
            //         $eac[] = $engine_actual;
            //         $es[] = $e->sisa / 60;
            //         $eca[] = (float)$a['capacity'];
            //     }
            // }
            $c[] = $a['mesin_nama_mesin'];
            $ac[] = (float)$a['actual'];
            $ca[] = (float)$a['capacity'];
            $s[] = (float)$a['sisa'];
        }


      


        if (empty($ec)) {
            $ec = null;
        }
        if (empty($eac)) {
            $eac = null;
        }
        if (empty($es)) {
            $es = null;
        }
        if (empty($eca)) {
            $eca = null;
        }

        $data['baseitem'] = (new TbBase())->getBaseData();
        $datasidebar = (new TbBase())->getAllSidebar();

        $t = array();
        $p = array();
        $m = array();
        $pn = array();
        $date = array();
        $data['item'] = array();

        foreach ($data['baseitem'] as $key) {
            $finishqty = 0;
            $assignqty = 0;
            $cekassign = (new TbAssign())->checkAssign($key['mppid']);
            if($cekassign == null){
                $progress = 'Need Assign';
            }else{
                $getqty = (new DashboardModel())->getQtyAssignAndFinish($key['mppid']);
                $finishqty = $getqty['finishedqty'];
                $assignqty = $getqty['assignqty'];
                foreach($cekassign as $cek){
                    if($cek['ANP_progres'] == 1 || $cek['ANP_progres'] == 2){
                        $progress = 'Started';
                        break;
                    }
                    else{
                        if($cek['ANP_progres'] == 4){
                            $progress = 'Finished';
                        }elseif($cek['ANP_progres'] == 3){
                            $progress = 'Stoped';
                        }elseif($cek['ANP_progres'] == 0){
                            $progress = 'Not Started';
                        }
                    }
                }
            }
            
            $qtyIN = 0;
            $tempqtyIN = (new MoveQTY_model())->getMovingIn($key['mppid']);
            $qtyOUT = 0;
            $tempqtyOUT = (new MoveQTY_model())->getMovingOut($key['mppid']);
            
            if($tempqtyIN != null){
                $qtyIN = $tempqtyIN['qtymove'];
            }

            if($tempqtyOUT != null){
                $qtyOUT = $tempqtyOUT['qtymove'];
            }

            $params = array(
                'progress' => $progress,
                'mppid' => $key['mppid'],
                'PN' => $key['PN'],
                'productname' => $key['productname'],
                'PRONumber' => $key['PRONumber'],
                'PartNumberComponent' => $key['PartNumberComponent'],
                'PartNameComponent' => $key['PartNameComponent'],
                'MaterialName' => $key['MaterialName'],
                'Thickness' => $key['Thickness'],
                'Length' => $key['Length'],
                'Width' => $key['Width'],
                'weight' => $key['weight'],
                'qty' => $key['qty'],
                'finishqty' => $finishqty,
                'assignqty' => $assignqty,
                'supplytoprocess' => $key['supplytoprocess'],
                'ProcessName' => $key['ProcessName'],
                'MHProcess' => $key['MHProcess'],
                'PlanStartdate' => $key['PlanStartdate'],
                'PlanEndDate' => $key['PlanEndDate'],
                'qtymovein' => $qtyIN,
                'qtymoveout' => $qtyOUT,
            );
            array_push($data['item'] , $params);
        }

        foreach ($datasidebar as $key) {
            array_push($t , $key['Thickness']);
            array_push($p , $key['ProcessName']);
            array_push($m , $key['MaterialName']);
            array_push($pn , $key['PN']);
            array_push($date , $key['PlanStartdate']);
        }
        $think = array_unique($t); 
        $Pname = array_unique($p);
        $mn = array_unique($m); 
        $PN = array_unique($pn);
        $dateSTART = array_unique($date);

        //    function date_sort_asc($a, $b) {
        //         return strtotime($a) - strtotime($b);
        //     }
            
        //     function date_sort_desc($a, $b) {
        //         return strtotime($b) - strtotime($a);
        //     }
            
        //     if (!empty($_SESSION['startdate']) && !empty($_SESSION['enddate'])) {
        //         $data['start_date'] = $_SESSION['startdate'];
        //         $data['start_date_end'] = $_SESSION['enddate'];
        //     } else {
        //         if (!empty($dateSTART)) {
        //             // Urutkan array secara ascending
        //             usort($dateSTART, "date_sort_asc");
        //             // Ambil tanggal pertama setelah diurutkan
        //             $data['start_date'] = date('Y-m-d', strtotime($dateSTART[0]));
        //             // Urutkan kembali array secara descending
        //             usort($dateSTART, "date_sort_desc");
        //             // Ambil tanggal pertama setelah diurutkan kembali
        //             $data['start_date_end'] = date('Y-m-d', strtotime($dateSTART[0]));
        //         } else {
        //             // Jika tidak ada tanggal yang tersedia, gunakan tanggal sekarang
        //             $now = Carbon::now();
        //             $data['start_date'] = $now->format('Y-m-d');
        //             $data['start_date_end'] = $now->format('Y-m-d');
        //         }
        //     }
            

        
        // function date_sort_asc($a, $b) {
        //     return strtotime($a) - strtotime($b);
        // }
        // function date_sort_desc($a, $b) {
        //     return strtotime($b) - strtotime($a);
        // }



        // if(!empty($_SESSION['startdate']) && !empty($_SESSION['enddate'])){
        //     $data['start_date'] = $_SESSION['startdate'];
        //     $data['start_date_end'] =$_SESSION['enddate'];
        // }else{
        //     if(!empty($dateSTART)){
        //         usort($dateSTART, "date_sort_asc");
        //         $data['start_date'] = date('Y-m-d', strtotime($dateSTART[0]));
        //         usort($dateSTART, "date_sort_desc");
        //         $data['start_date_end'] = date('Y-m-d', strtotime($dateSTART[0]));
        //     }
        //     else{
        //         $now = Carbon::now();
        //         $data['start_date'] = $now->format('Y-m-d');
        //         $data['start_date_end'] =$now->format('Y-m-d');
        //     }
        // }

        $selectcategoryfilter = array();
        $selectorder = array();
        $selectfield = array();
        

        // Menyiapkan opsi-opsi filter dan pengurutan
        $data['selectcategoryfilter'] = [
            [null , "Choose"],
            ["PN" , "Part Number Product"],
            ["PRONumber","PRO Number"],
            ["productname","Product Name"],
            ["PartNumberComponent","Part Number Component"],
            ["MaterialName","Material Name"],
            ["Thickness","Thickness"],
            ["Length","Length"],
            ["Width","Width"],
            ["weight","weight"],
            ["qty","Quantity"],
            ["supplytoprocess","Supply to Process"],
            ["PlanStartdate","Plan Start date"],
            ["PlanEndDate","Plan End date"],
            ["customer_name" , "Customer"],
            ["ANP_mesin_kode_mesin", "Final Assign Machine"],
        ];
        $selectcategoryfilter = $data['selectcategoryfilter'];


        $data['selectorder'] = [
            [null , "Select sorting type"],
            ["ASC","Ascending"],
            ["DESC","Descending"],
        ];
        $selectorder = $data['selectorder'];

        $data['selectfield'] = [
            [null , "Select category"],
            ["PN" , "Part Number Product"],
            ["PRONumber","PRO Number"],
            ["productname","Product Name"],
            ["PartNumberComponent","Part Number Component"],
            ["MaterialName","Material Name"],
            ["Thickness","Thickness"],
            ["Length","Length"],
            ["Width","Width"],
            ["weight","weight"],
            ["qty","Quantity"],
            ["supplytoprocess","Supply to Process"],
            ["PlanStartdate","Plan Start date"],
            ["PlanEndDate","Plan End date"],
            // ["customer_name" , "Customer"],
            // ["ANP_mesin_kode_mesin", "Final Assign Machine"],
        ];
        $selectfield = $data['selectfield'];

        $amountdata = array("10","20","30","40","50","60","70"," 80","90","100");
      

        // $p = $request->input('pname');

        // dd($p);
        $machineCodes = TbMesin::select('mesin_kode_mesin', 'mesin_nama_mesin')->distinct()->get();

        if ($request->ajax()) {
            

            

            // $perPage = 10;
            // $data = TbBase::paginate($perPage);
            // $perPage = 10;
            $perPage = $request->input('ddamount', 10); 
            $filterMesin = $request->input('filterMesin', null); // Capture the filter input

            

            $no = 0;
    
            // Mulai query dari model TbBase
            $query = TbBase::query();

            if (!empty($filterMesin)) {
                $query->whereHas('anp', function($q) use ($filterMesin) {
                    $q->where('ANP_mesin_kode_mesin', $filterMesin);
                });
            }
            
            // $machineCodes = $request->input('machine_code');

            
    
            if ($request->input('all', 1) == 1) { // Default to 1 if 'all' is not present
                $query->where('status_assign', '=', '0');
            } else {
                // Cek apakah input Part Number Component ada
                if ($request->has('PartNumberComponent')) {
                    $partNumberComponent = $request->input('PartNumberComponent');
                    $query->where('PartNumberComponent', 'LIKE', '%' . $partNumberComponent . '%');
                }
    
                // Cek apakah filter Process Name ada
                if ($request->has('pname')) {
                    $processNames = $request->input('pname');
                    $query->whereIn('ProcessName', $processNames);
                }

                 // Cek apakah filter Material ada
                if ($request->has('m')) {
                    $materials = $request->input('m');
                    $query->whereIn('MaterialName', $materials);
                }

                // Cek apakah filter Thickness ada
                if ($request->has('t')) {
                    $thicknesses = $request->input('t');
                    $query->whereIn('Thickness', $thicknesses);
                }

                // Cek apakah filter Part Number Product ada
                if ($request->has('pnp')) {
                    $partnumber = $request->input('pnp');
                    $query->whereIn('PN', $partnumber);
                }

                // Cek apakah filter PlanStartDate ada
                if ($request->has('startdate') && $request->has('enddate')) {
                    $startDate = $request->input('startdate');
                    $endDate = $request->input('enddate');
                
                    if (strtotime($startDate) && strtotime($endDate)) {
                        $query->whereBetween('PlanStartdate', [$startDate, $endDate]);
                    }
                }

                if ($request->has('ddfiltercategory') && $request->has('cari')) {
                    $category = $request->input('ddfiltercategory');
                    $keyword = $request->input('cari');
                    // \Log::info('Category: ' . $category . ' - Keyword: ' . $keyword);
                
                    switch ($category) {
                        case 'ProcessName':
                            $query->where('ProcessName', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'MaterialName':
                            $query->where('MaterialName', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'Thickness':
                            $query->where('Thickness', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'PN':
                            $query->where('PN', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'PRONumber':
                            $query->where('PRONumber', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'productname':
                            $query->where('productname', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'PartNumberComponent':
                            $query->where('PartNumberComponent', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'Length':
                            $query->where('Length', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'Width':
                            $query->where('Width', 'LIKE', '%' . $keyword . '%');
                            break;   
                        case 'weight':
                            $query->where('weight', 'LIKE', '%' . $keyword . '%');
                            break;  
                        case 'qty':
                            $query->where('qty', 'LIKE', '%' . $keyword . '%');
                            break;  
                        case 'supplytoprocess':
                            $query->where('supplytoprocess', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'PlanStartdate':
                            $query->where('PlanStartdate', 'LIKE', '%' . $keyword . '%');
                            break;  
                        case 'PlanEndDate':
                            $query->where('PlanEndDate', 'LIKE', '%' . $keyword . '%');
                            break;  
                        case 'customer_name':
                            $query->where('customer_name', 'LIKE', '%' . $keyword . '%');
                            break;
                        case 'ANP_mesin_kode_mesin':
                            $query->where('ANP_mesin_kode_mesin', 'LIKE', '%' . $keyword . '%');
                            break;    
                       
                        default:
                            // \Log::info('Unknown category: ' . $category);
                            break;
                    }

                    
                }

                 // Lakukan filter berdasarkan kategori jika dipilih
                if ($request->has('ddcategoryfilter')) {
                    $categoryFilter = $request->input('ddcategoryfilter');
                    
                    // Lakukan sesuai dengan kategori yang dipilih
                    switch ($categoryFilter) {
                        case 'ProcessName':
                            // Lakukan filter berdasarkan nama proses
                            $query->orderBy('ProcessName', $request->input('ddorder', 'asc'));
                            break;
                        case 'MaterialName':
                            // Lakukan filter berdasarkan nama material
                            $query->orderBy('MaterialName', $request->input('ddorder', 'asc'));
                            break;
                        // Tambahkan case lain sesuai kebutuhan

                        case 'Thickness':
                            $query->orderBy('Thickness', $request->input('ddorder', 'asc'));
                            break;
                        case 'PN':
                            $query->orderBy('PN', $request->input('ddorder', 'asc'));
                            break;
                        case 'PRONumber':
                            $query->orderBy('PRONumber', $request->input('ddorder', 'asc'));
                            break;
                        case 'productname':
                            $query->orderBy('productname', $request->input('ddorder', 'asc'));
                            break;
                        case 'PartNumberComponent':
                            $query->orderBy('PartNumberComponent', $request->input('ddorder', 'asc'));
                            break;
                        case 'Length':
                            $query->orderBy('Length', $request->input('ddorder', 'asc'));
                            break;
                        case 'Width':
                            $query->orderBy('Width', $request->input('ddorder', 'asc'));
                            break;   
                        case 'weight':
                            $query->orderBy('weight', $request->input('ddorder', 'asc'));
                            break;  
                        case 'qty':
                            $query->orderBy('qty', $request->input('ddorder', 'asc'));
                            break;  
                        case 'supplytoprocess':
                            $query->orderBy('supplytoprocess', $request->input('ddorder', 'asc'));
                            break;
                        case 'PlanStartdate':
                            $query->orderBy('PlanStartdate', $request->input('ddorder', 'asc'));
                            break;  
                        case 'PlanEndDate':
                            $query->orderBy('PlanEndDate', $request->input('ddorder', 'asc'));
                            break;  
                        
                        default:
                            // Default sorting jika tidak ada kategori terpilih
                            $query->orderBy('id', 'desc');
                            break;
                    }
                } else {
                    // Default sorting jika tidak ada kategori terpilih
                    $query->orderBy('id', 'desc');
                }


                

            }

            // Dapatkan data terfilter dan paginate
            $data = $query->paginate($perPage);

            foreach ($data as $item) {

                
                $no++;


                $anp = TbAssign::where('ANP_key_IMA', $item->mppid)->get();

                
                $item->setAttribute('ANP', $anp->toArray());

                $msn = [];
                foreach($anp as $assign) {
                    $mesin = TbMesin::where('mesin_kode_mesin', $assign->ANP_mesin_kode_mesin)
                                    ->where('mesin_status', 0)
                                    ->first();
                    if($mesin) {
                        $msn[] = $mesin->toArray();
                    }
                }
                $item->setAttribute('MSNs', $msn);

                $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
                    ->where('MIC_PN_product', $item->PN)
                    ->where('MIC_Status_Aktifasi', 0)
                    ->get();
                $item->setAttribute('MAP', $map->toArray());

                // Query tambahan
                $additionalData = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) as assignqty, SUM(ANP_qty_finish) as finishedqty')
                ->where('ANP_key_IMA', $item->mppid)
                ->groupBy('ANP_key_IMA')
                ->first();

                // Tambahkan data tambahan ke dalam item jika ada
                if ($additionalData) {
                    $item->setAttribute('assignedqty', $additionalData->assignedqty);
                    $item->setAttribute('finishedqty', $additionalData->finishedqty);
                } else {
                    // Jika tidak ada data tambahan, set nilai default ke null
                    $item->setAttribute('assignedqty', null);
                    $item->setAttribute('finishedqty', null);
                }

                $qtyIN = 0;
                $tempqtyIN = (new MoveQTY_model())->getMovingIn($item['mppid']);
                $qtyOUT = 0;
                $tempqtyOUT = (new MoveQTY_model())->getMovingOut($item['mppid']);
                
                if ($tempqtyIN != null) {
                    $qtyIN = $tempqtyIN->qtymove;
                }
                
                if ($tempqtyOUT != null) {
                    $qtyOUT = $tempqtyOUT->qtymove;
                }
                
                $item['qtyIN'] = $qtyIN;
                $item['qtyOUT'] = $qtyOUT;

                


                $mapping = (new TbMappingPRO())->get_all_mapping();
                $cust = 'Not Set'; // Inisialisasi dengan 'not set' sebagai nilai default
                foreach ($mapping as $c) {
                    if ($c->mapping_pro == $item->PRONumber) {
                        $cust = $c->customer_name;
                        // Jika mapping_pro cocok, langsung keluar dari loop
                        break;
                    }
                }
                $item->setAttribute('customer', $cust);


                 // Inisialisasi variabel
                $chartmesin = [];
                $arrayovermesin = [];
                // $no = 0;

                // Ini ngambil data dari view chart capacity vs actual
                $mesinAvail = (new TbMesin())->get_available_mesin();
                // dd($mesinAvail);

                $sisa = 0;
                $mhperqty = 0;
                $nestingqty = 0;
                $tdresult = '';
                $assignedqty = 0;
                $finishqty = 0;
                $cek = 0;

                $no++;
                $arrayavailablemesin = [];
                // dd($item->finishedqty);
                // dd($item->qty);

                if($item->qty <= $item->finishedqty){
                    $cek = 1;
                  }else if($item->qty <= $item->assignedqty){
                    $cek = 1;
                  }


                if ($cek == 0) {
                    // INI BUAT NGAMBIL DATA DARI PROCESS SEBELUMNYA
                    foreach ($chartmesin as $cm) {
                        if ($cm['pname'] == $item->ProcessName) {
                            $cmsisa = $cm['sisa'];
                            $cmmanhourperqty = $item->MHProcess / $item->qty;
                            if ($cmsisa >= $cmmanhourperqty) {
                                $tempmesin = [
                                    'sisa' => $cmsisa,
                                    'mhperunit' => $cmmanhourperqty,
                                    'nama_mesin' => $cm['nama'],
                                    'pname' => $cm['pname'],
                                    'mthick' => $cm['tm']
                                ];
                                array_push($arrayavailablemesin, $tempmesin);
                            } else {
                                $counter = 0;
                                foreach ($arrayovermesin as $arr) {
                                    if ($arr['nama_over_mesin'] == $cm['nama']) {
                                        $counter = 1;
                                    }
                                }
                                if ($counter == 0) {
                                    $over = [
                                        'nama_over_mesin' => $cm['nama'],
                                        'nama_over_mesinprocess' => $cm['pname']
                                    ];
                                    array_push($arrayovermesin, $over);
                                }
                            }
                        }
                    }

                    // MENCARI MESIN YANG BELUM ADA DI ARRAY AVAILABLE MESIN
                    foreach ($mesinAvail as $m) {
                        if ($m->process_name == $item->ProcessName) {
                            if (count($arrayavailablemesin) == 0) {
                                $counter = 0;
                                foreach ($arrayovermesin as $arr) {
                                    if ($arr['nama_over_mesin'] == $m->mesin_nama_mesin) {
                                        $counter = 1;
                                    }
                                }
                                if ($counter == 0) {
                                    $sisa = $m->sisa * 60;
                                    $manhourperqty = $item->MHProcess / $item->qty;
                                    $tempmesin = [
                                        'sisa' => $sisa,
                                        'mhperunit' => $manhourperqty,
                                        'nama_mesin' => $m->mesin_nama_mesin,
                                        'pname' => $m->process_name,
                                        'mthick' => 0
                                    ];
                                    array_push($arrayavailablemesin, $tempmesin);
                                }
                            } else {
                                $counter = 0;
                                foreach ($arrayavailablemesin as $ar) {
                                    if ($ar['nama_mesin'] == $m->mesin_nama_mesin) {
                                        $counter = 1;
                                    }
                                }
                                foreach ($arrayovermesin as $arr) {
                                    if ($arr['nama_over_mesin'] == $m->mesin_nama_mesin) {
                                        $counter = 1;
                                    }
                                }

                                if ($counter == 0) {
                                    $sisa = $m->sisa * 60;
                                    $manhourperqty = $item->MHProcess / $item->qty;
                                    $tempmesin = [
                                        'sisa' => $sisa,
                                        'mhperunit' => $manhourperqty,
                                        'nama_mesin' => $m->mesin_nama_mesin,
                                        'pname' => $m->process_name,
                                        'mthick' => 0
                                    ];
                                    array_push($arrayavailablemesin, $tempmesin);
                                }
                            }
                        }
                    }

                    $temp = array_column($arrayavailablemesin, 'sisa');
                    array_multisort($temp, SORT_DESC, $arrayavailablemesin);

                    // dd($arrayavailablemesin);
                    // dd($no);
                    foreach ($arrayavailablemesin as $lm) {
                        //     $sisa = $lm['sisa'];
                        //     $mhperqty = $lm['mhperunit'];
                        //     $tdresult = $lm['nama_mesin'];
                        //     $nestingqty = floor($lm['sisa'] / $lm['mhperunit']);
                        //     // dd($nestingqty);
                        // $qtyneeded = $item->qty - $assignedqty;
                        // // dd($qtyneeded);
                        //     // $qtyneeded = $item->qty - $item->assignqty;

                        //     if ($nestingqty > $qtyneeded) {
                        //         $nestingqty = $qtyneeded;
                        //         $totalmhneed = $qtyneeded * $lm['mhperunit'];
                        //         $sisamh = $lm['sisa'] - $totalmhneed;
                        //     } else {
                        //         $totalmhneed = $nestingqty * $lm['mhperunit'];
                        //         $sisamh = $lm['sisa'] - $totalmhneed;
                        //     }
                        //     foreach ($chartmesin as $key => $value) {
                        //         if ($value['nama'] == $lm['nama_mesin']) {
                        //             unset($chartmesin[$key]);
                        //         }
                        //     }
                        //     $add = [
                        //         'nama' => $lm['nama_mesin'],
                        //         'sisa' => $sisamh,
                        //         'pname' => $lm['pname'],
                        //         'tm' => $lm['mthick']
                        //     ];
                        //     array_push($chartmesin, $add);
                        //     break;
                        // }
                        if($no == 1){
                            $sisa = $lm['sisa'];
                            $mhperqty = $lm['mhperunit'];
                            $tdresult = $lm['nama_mesin'];
                            $nestingqty = floor($lm['sisa']/$lm['mhperunit']);
                            // dd($nestingqty);
                            $qtyneeded = $item->qty - $assignedqty;

                            if($nestingqty > $qtyneeded){
                            $nestingqty = $qtyneeded;
                            $totalmhneed = $qtyneeded * $lm['mhperunit'];
                            $sisamh = $lm['sisa'] - $totalmhneed; 
                            }else{
                            $totalmhneed = $nestingqty * $lm['mhperunit'];
                            $sisamh = $lm['sisa'] - $totalmhneed; 
                            }
                            foreach ($chartmesin as $key => $value){
                            if ($value['nama'] == $lm['nama_mesin']) {
                                unset($chartmesin[$key]);
                            }
                            }
                            $add = array(
                            'nama' => $lm['nama_mesin'],
                            'sisa' => $sisamh,
                            'pname' => $lm['pname'],
                            'tm' => $lm['mthick'] 
                            );
                            array_push($chartmesin,$add);
                            

                            break;
                        } 
                        else{
                            $count_array = count($lm);
                            // dd($lm);
                            //ini buat ngakalin suggestion
                            if($lm['sisa'] < 1){
                                // $sisa = $lm['sisa'];
                                // $mhperqty = $lm['mhperunit'];
                                // $tdresult = $lm['nama_mesin'];
                                // dd('test');
                            }else
                            {
                            $sisa = $lm['sisa'];
                            $mhperqty = $lm['mhperunit'];
                            $tdresult = $lm['nama_mesin'];
                            $nestingqty = @(floor($lm['sisa']/$lm['mhperunit']));
                                //dd($nestingqty);
                            $qtyneeded = $item->qty - $assignedqty;

                            if($nestingqty > $qtyneeded){
                                $nestingqty = $qtyneeded;
                                $totalmhneed = $qtyneeded * $lm['mhperunit'];
                                $sisamh = $lm['sisa'] - $totalmhneed; 
                            }else{
                                $totalmhneed = $nestingqty * $lm['mhperunit'];
                                $sisamh = $lm['sisa'] - $totalmhneed; 
                            }
                            foreach ($chartmesin as $key => $value){
                                if ($value['nama']  == $lm['nama_mesin']) {
                                unset($chartmesin[$key]);
                                } 
                            }


                            $add = array(
                                'nama' => $lm['nama_mesin'],
                                'sisa' => $sisamh,
                                'pname' => $lm['pname'],
                                'tm' => $lm['mthick']   
                            );
                            array_push($chartmesin,$add);
                                //  dd($chartmesin);

                            break;
                            }

                        }
                    }
                } else {
                    $tdresult = 'DONE';
                    $nestingqty = 0;
                }

                // dd($chartmesin);

                if ($tdresult != '') {
                    $item->setAttribute('tdresult', $tdresult);
                    $item->setAttribute('nestingqty', $nestingqty);
                } else {
                    $item->setAttribute('tdresult', 'No machine can be suggested');
                    $item->setAttribute('nestingqty', 'None');
                }


                $temp = $item->qty - $item->finishedqty;
                
                $item->setAttribute('temp', $temp);


                $chart = (new DashboardModel())->getActualVsCapacity();
                $chartArray = json_decode(json_encode($chart), true);
              //  $chartmesin = array();
        
                //Deklarasi
                $ec = [];
                $eac = [];
                $es = [];
                $eca = [];
                $c = [];
                $ac = [];
                $ca = [];
                $s = [];
        
                foreach ($chartArray as $a) {
                    foreach ($chartmesin as $e) {
                        if ($a['mesin_nama_mesin'] == $e['nama']) {
                            $ec[] = $e['nama'];
                            $engine_actual = (float)$a['capacity'] - ($e['sisa'] / 60);
                            $eac[] = $engine_actual;
                            $es[] = $e['sisa'] / 60;
                            $eca[] = (float)$a['capacity'];
                        }
                    }
                    $c[] = $a['mesin_nama_mesin'];
                    $ac[] = (float)$a['actual'];
                    $ca[] = (float)$a['capacity'];
                    $s[] = (float)$a['sisa'];
                }
        
                if (empty($ec)) {
                    $ec = null;
                }
                if (empty($eac)) {
                    $eac = null;
                }
                if (empty($es)) {
                    $es = null;
                }
                if (empty($eca)) {
                    $eca = null;
                }
    
                $item->setAttribute('ec', $ec);
                $item->setAttribute('eac', $eac);
                $item->setAttribute('es', $es);
                $item->setAttribute('eca', $eca);


                
            }

           
            
           

            // Hitung total entri data secara keseluruhan
                $totalData = TbBase::count();

                // dd($data);


                return response()->json([
                    'draw' => $request->input('draw'), // untuk keperluan security bisa diabaikan jika tidak diperlukan
                    'recordsTotal' => $totalData, // Total entri data dari server
                    'recordsFiltered' => $data->total(), // Jumlah data yang terfilter
                    'data' => $data->items()
                ]);

              
               

        }


              
               
                return view('PBEngine.dashboard.index', 
                compact('ForMachineBreakdown', 
                        'ForTomorrow', 
                        'ForOnProgress', 
                        'ForFinish', 
                        'chartArray',
                        'ec',
                        'eac',
                        'es',
                        'eca',
                        'c',
                        'ac',
                        'ca',
                        's',
                        'mn',
                        'PN',
                        'Pname',
                        'think',
                        'selectcategoryfilter',
                        'selectorder',
                        'selectfield',
                        'amountdata',
                        'machineCodes',
                        
                        ));
                      

   
    }



    public function index_backup(Request $request){
        $ForMachineBreakdown = (new TbMesin())->get_breakdown_mesin_num();
        $ForTomorrow = (new TbBase())->getRowAmountData(1);
        $ForOnProgress = (new TbBase())->getRowAmountData(2);
        $ForPause = (new TbBase())->getRowAmountData(3);
        $ForFinish = (new TbBase())->getRowAmountData(4);

        $chart = (new DashboardModel())->getActualVsCapacity();
        $chartArray = json_decode(json_encode($chart), true);
        $chartmesin = array();

        //Deklarasi
        $ec = [];
        $eac = [];
        $es = [];
        $eca = [];
        $c = [];
        $ac = [];
        $ca = [];
        $s = [];

        foreach ($chartArray as $a) {
            // foreach ($chartmesin as $e) {
            //     if ($a['mesin_nama_mesin'] == $e->nama) {
            //         $ec[] = $e->nama;
            //         $engine_actual = (float)$a['capacity'] - ($e->sisa / 60);
            //         $eac[] = $engine_actual;
            //         $es[] = $e->sisa / 60;
            //         $eca[] = (float)$a['capacity'];
            //     }
            // }
            $c[] = $a['mesin_nama_mesin'];
            $ac[] = (float)$a['actual'];
            $ca[] = (float)$a['capacity'];
            $s[] = (float)$a['sisa'];
        }


      


        if (empty($ec)) {
            $ec = null;
        }
        if (empty($eac)) {
            $eac = null;
        }
        if (empty($es)) {
            $es = null;
        }
        if (empty($eca)) {
            $eca = null;
        }

        $data['baseitem'] = (new TbBase())->getBaseData();
        $datasidebar = (new TbBase())->getAllSidebar();

        $t = array();
        $p = array();
        $m = array();
        $pn = array();
        $date = array();
        $data['item'] = array();

        foreach ($data['baseitem'] as $key) {
            $finishqty = 0;
            $assignqty = 0;
            $cekassign = (new TbAssign())->checkAssign($key['mppid']);
            if($cekassign == null){
                $progress = 'Need Assign';
            }else{
                $getqty = (new DashboardModel())->getQtyAssignAndFinish($key['mppid']);
                $finishqty = $getqty['finishedqty'];
                $assignqty = $getqty['assignqty'];
                foreach($cekassign as $cek){
                    if($cek['ANP_progres'] == 1 || $cek['ANP_progres'] == 2){
                        $progress = 'Started';
                        break;
                    }
                    else{
                        if($cek['ANP_progres'] == 4){
                            $progress = 'Finished';
                        }elseif($cek['ANP_progres'] == 3){
                            $progress = 'Stoped';
                        }elseif($cek['ANP_progres'] == 0){
                            $progress = 'Not Started';
                        }
                    }
                }
            }
            
            $qtyIN = 0;
            $tempqtyIN = (new MoveQTY_model())->getMovingIn($key['mppid']);
            $qtyOUT = 0;
            $tempqtyOUT = (new MoveQTY_model())->getMovingOut($key['mppid']);
            
            if($tempqtyIN != null){
                $qtyIN = $tempqtyIN['qtymove'];
            }

            if($tempqtyOUT != null){
                $qtyOUT = $tempqtyOUT['qtymove'];
            }

            $params = array(
                'progress' => $progress,
                'mppid' => $key['mppid'],
                'PN' => $key['PN'],
                'productname' => $key['productname'],
                'PRONumber' => $key['PRONumber'],
                'PartNumberComponent' => $key['PartNumberComponent'],
                'PartNameComponent' => $key['PartNameComponent'],
                'MaterialName' => $key['MaterialName'],
                'Thickness' => $key['Thickness'],
                'Length' => $key['Length'],
                'Width' => $key['Width'],
                'weight' => $key['weight'],
                'qty' => $key['qty'],
                'finishqty' => $finishqty,
                'assignqty' => $assignqty,
                'supplytoprocess' => $key['supplytoprocess'],
                'ProcessName' => $key['ProcessName'],
                'MHProcess' => $key['MHProcess'],
                'PlanStartdate' => $key['PlanStartdate'],
                'PlanEndDate' => $key['PlanEndDate'],
                'qtymovein' => $qtyIN,
                'qtymoveout' => $qtyOUT,
            );
            array_push($data['item'] , $params);
        }

        foreach ($datasidebar as $key) {
            array_push($t , $key['Thickness']);
            array_push($p , $key['ProcessName']);
            array_push($m , $key['MaterialName']);
            array_push($pn , $key['PN']);
            array_push($date , $key['PlanStartdate']);
        }
        $think = array_unique($t); 
        $Pname = array_unique($p);
        $mn = array_unique($m); 
        $PN = array_unique($pn);
        $dateSTART = array_unique($date);

        //    function date_sort_asc($a, $b) {
        //         return strtotime($a) - strtotime($b);
        //     }
            
        //     function date_sort_desc($a, $b) {
        //         return strtotime($b) - strtotime($a);
        //     }
            
        //     if (!empty($_SESSION['startdate']) && !empty($_SESSION['enddate'])) {
        //         $data['start_date'] = $_SESSION['startdate'];
        //         $data['start_date_end'] = $_SESSION['enddate'];
        //     } else {
        //         if (!empty($dateSTART)) {
        //             // Urutkan array secara ascending
        //             usort($dateSTART, "date_sort_asc");
        //             // Ambil tanggal pertama setelah diurutkan
        //             $data['start_date'] = date('Y-m-d', strtotime($dateSTART[0]));
        //             // Urutkan kembali array secara descending
        //             usort($dateSTART, "date_sort_desc");
        //             // Ambil tanggal pertama setelah diurutkan kembali
        //             $data['start_date_end'] = date('Y-m-d', strtotime($dateSTART[0]));
        //         } else {
        //             // Jika tidak ada tanggal yang tersedia, gunakan tanggal sekarang
        //             $now = Carbon::now();
        //             $data['start_date'] = $now->format('Y-m-d');
        //             $data['start_date_end'] = $now->format('Y-m-d');
        //         }
        //     }
            

        
        // function date_sort_asc($a, $b) {
        //     return strtotime($a) - strtotime($b);
        // }
        // function date_sort_desc($a, $b) {
        //     return strtotime($b) - strtotime($a);
        // }



        // if(!empty($_SESSION['startdate']) && !empty($_SESSION['enddate'])){
        //     $data['start_date'] = $_SESSION['startdate'];
        //     $data['start_date_end'] =$_SESSION['enddate'];
        // }else{
        //     if(!empty($dateSTART)){
        //         usort($dateSTART, "date_sort_asc");
        //         $data['start_date'] = date('Y-m-d', strtotime($dateSTART[0]));
        //         usort($dateSTART, "date_sort_desc");
        //         $data['start_date_end'] = date('Y-m-d', strtotime($dateSTART[0]));
        //     }
        //     else{
        //         $now = Carbon::now();
        //         $data['start_date'] = $now->format('Y-m-d');
        //         $data['start_date_end'] =$now->format('Y-m-d');
        //     }
        // }

        $selectcategoryfilter = array();
        $selectorder = array();
        $selectfield = array();
        

        // Menyiapkan opsi-opsi filter dan pengurutan
        $data['selectcategoryfilter'] = [
            [null , "Choose"],
            ["PN" , "Part Number Product"],
            ["PRONumber","PRO Number"],
            ["productname","Product Name"],
            ["PartNumberComponent","Part Number Component"],
            ["MaterialName","Material Name"],
            ["Thickness","Thickness"],
            ["Length","Length"],
            ["Width","Width"],
            ["weight","weight"],
            ["qty","Quantity"],
            ["supplytoprocess","Supply to Process"],
            ["PlanStartdate","Plan Start date"],
            ["PlanEndDate","Plan End date"],
            ["customer_name" , "Customer"],
            ["mesin_nama_mesin", "Final Assign Machine"],
        ];
        $selectcategoryfilter = $data['selectcategoryfilter'];


        $data['selectorder'] = [
            [null , "Select sorting type"],
            ["ASC","Ascending"],
            ["DESC","Descending"],
        ];
        $selectorder = $data['selectorder'];

        $data['selectfield'] = [
            [null , "Select category"],
            ["PN" , "Part Number Product"],
            ["PRONumber","PRO Number"],
            ["productname","Product Name"],
            ["PartNumberComponent","Part Number Component"],
            ["MaterialName","Material Name"],
            ["Thickness","Thickness"],
            ["Length","Length"],
            ["Width","Width"],
            ["weight","weight"],
            ["qty","Quantity"],
            ["supplytoprocess","Supply to Process"],
            ["PlanStartdate","Plan Start date"],
            ["PlanEndDate","Plan End date"],
            ["customer_name" , "Customer"],
            ["mesin_nama_mesin", "Final Assign Machine"],
        ];
        $selectfield = $data['selectfield'];

        $amountdata = array("10","20","30","40","50","60","70"," 80","90","100");
      



        if ($request->ajax()) {
            

            $perPage = 10;
            $data = TbBase::paginate($perPage);
            
            foreach ($data as $item) {
                $anp = TbAssign::where('ANP_key_IMA', $item->mppid)->get();
                $item->setAttribute('ANP', $anp->toArray());

                $msn = [];
                foreach($anp as $assign) {
                    $mesin = TbMesin::where('mesin_kode_mesin', $assign->ANP_mesin_kode_mesin)
                                    ->where('mesin_status', 0)
                                    ->first();
                    if($mesin) {
                        $msn[] = $mesin->toArray();
                    }
                }
                $item->setAttribute('MSNs', $msn);

                $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
                    ->where('MIC_PN_product', $item->PN)
                    ->where('MIC_Status_Aktifasi', 0)
                    ->get();
                $item->setAttribute('MAP', $map->toArray());

                // Query tambahan
                $additionalData = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) as assignqty, SUM(ANP_qty_finish) as finishedqty')
                ->where('ANP_key_IMA', $item->mppid)
                ->groupBy('ANP_key_IMA')
                ->first();

                // Tambahkan data tambahan ke dalam item jika ada
                if ($additionalData) {
                    $item->setAttribute('assignedqty', $additionalData->assignedqty);
                    $item->setAttribute('finishedqty', $additionalData->finishedqty);
                } else {
                    // Jika tidak ada data tambahan, set nilai default ke null
                    $item->setAttribute('assignedqty', null);
                    $item->setAttribute('finishedqty', null);
                }

                $qtyIN = 0;
                $tempqtyIN = (new MoveQTY_model())->getMovingIn($item['mppid']);
                $qtyOUT = 0;
                $tempqtyOUT = (new MoveQTY_model())->getMovingOut($item['mppid']);
                
                if ($tempqtyIN != null) {
                    $qtyIN = $tempqtyIN['qtymove'];
                }
                
                if ($tempqtyOUT != null) {
                    $qtyOUT = $tempqtyOUT['qtymove'];
                }
                
                $item['qtyIN'] = $qtyIN;
                $item['qtyOUT'] = $qtyOUT;

                
                // $assign = (new TbAssign())->getAllAssign();
                // $progressMachineFound = false;
                // foreach ($assign as $asgn) {
                //     if ($asgn->ANP_key_IMA == $item->mppid) {
                //         $progres = null;
                //         switch ($asgn->ANP_progres) {
                //             case 0:
                //                 $progres = 'Not Started';
                //                 break;
                //             case 1:
                //                 $progres = 'Started';
                //                 break;
                //             case 2:
                //                 $progres = 'Paused';
                //                 break;
                //             case 3:
                //                 $progres = 'Stoped';
                //                 break;
                //             case 4:
                //                 $progres = 'Finished';
                //                 break;
                //             default:
                //                 // code...
                //                 break;
                //         }
                //         $item->setAttribute('progressMachine', $asgn->mesin_nama_mesin);
                //         $item->setAttribute('progressMachine1', $progres);
                //         $progressMachineFound = true;
                //     }
                // }

                // // Jika progress machine tidak ditemukan, set nilai progress machine menjadi "Not Set"
                // if (!$progressMachineFound) {
                //     $item->setAttribute('progressMachine', 'Not Set');
                //     $item->setAttribute('progressMachine1', 'Null');
                // }


                $mapping = (new TbMappingPRO())->get_all_mapping();
                $cust = 'not set'; // Inisialisasi dengan 'not set' sebagai nilai default
                foreach ($mapping as $c) {
                    if ($c->mapping_pro == $item->PRONumber) {
                        $cust = $c->customer_name;
                        // Jika mapping_pro cocok, langsung keluar dari loop
                        break;
                    }
                }
                $item->setAttribute('customer', $cust);


                 // Inisialisasi variabel
                $chartmesin = [];
                $arrayovermesin = [];
                $no = 0;

                // Ini ngambil data dari view chart capacity vs actual
                $mesinAvail = (new TbMesin())->get_available_mesin();

                $sisa = 0;
                $mhperqty = 0;
                $nestingqty = 0;
                $tdresult = '';
                $assignedqty = 0;
                $finishqty = 0;
                $cek = 0;

                $no++;
                $arrayavailablemesin = [];

                if($item->qty <= $item->finishedqty){
                    $cek = 1;
                  }else if($item->qty <= $item->assignedqty){
                    $cek = 1;
                  }


                if ($cek == 0) {
                    // INI BUAT NGAMBIL DATA DARI PROCESS SEBELUMNYA
                    foreach ($chartmesin as $cm) {
                        if ($cm['pname'] == $item->ProcessName) {
                            $cmsisa = $cm['sisa'];
                            $cmmanhourperqty = $item->MHProcess / $item->qty;
                            if ($cmsisa >= $cmmanhourperqty) {
                                $tempmesin = [
                                    'sisa' => $cmsisa,
                                    'mhperunit' => $cmmanhourperqty,
                                    'nama_mesin' => $cm['nama'],
                                    'pname' => $cm['pname'],
                                    'mthick' => $cm['tm']
                                ];
                                array_push($arrayavailablemesin, $tempmesin);
                            } else {
                                $counter = 0;
                                foreach ($arrayovermesin as $arr) {
                                    if ($arr['nama_over_mesin'] == $cm['nama']) {
                                        $counter = 1;
                                    }
                                }
                                if ($counter == 0) {
                                    $over = [
                                        'nama_over_mesin' => $cm['nama'],
                                        'nama_over_mesinprocess' => $cm['pname']
                                    ];
                                    array_push($arrayovermesin, $over);
                                }
                            }
                        }
                        


                    }

                    // MENCARI MESIN YANG BELUM ADA DI ARRAY AVAILABLE MESIN
                    foreach ($mesinAvail as $m) {
                        if ($m->process_name == $item->ProcessName) {
                            if (count($arrayavailablemesin) == 0) {
                                $counter = 0;
                                foreach ($arrayovermesin as $arr) {
                                    if ($arr['nama_over_mesin'] == $m->mesin_nama_mesin) {
                                        $counter = 1;
                                    }
                                }
                                if ($counter == 0) {
                                    $sisa = $m->sisa * 60;
                                    $manhourperqty = $item->MHProcess / $item->qty;
                                    $tempmesin = [
                                        'sisa' => $sisa,
                                        'mhperunit' => $manhourperqty,
                                        'nama_mesin' => $m->mesin_nama_mesin,
                                        'pname' => $m->process_name,
                                        'mthick' => 0
                                    ];
                                    array_push($arrayavailablemesin, $tempmesin);
                                }
                            } else {
                                $counter = 0;
                                foreach ($arrayavailablemesin as $ar) {
                                    if ($ar['nama_mesin'] == $m->mesin_nama_mesin) {
                                        $counter = 1;
                                    }
                                }
                                foreach ($arrayovermesin as $arr) {
                                    if ($arr['nama_over_mesin'] == $m->mesin_nama_mesin) {
                                        $counter = 1;
                                    }
                                }

                                if ($counter == 0) {
                                    $sisa = $m->sisa * 60;
                                    $manhourperqty = $item->MHProcess / $item->qty;
                                    $tempmesin = [
                                        'sisa' => $sisa,
                                        'mhperunit' => $manhourperqty,
                                        'nama_mesin' => $m->mesin_nama_mesin,
                                        'pname' => $m->process_name,
                                        'mthick' => 0
                                    ];
                                    array_push($arrayavailablemesin, $tempmesin);
                                }
                            }
                        }
                    }

                    $temp = array_column($arrayavailablemesin, 'sisa');
                    array_multisort($temp, SORT_DESC, $arrayavailablemesin);

                    foreach ($arrayavailablemesin as $lm) {
                        $sisa = $lm['sisa'];
                        $mhperqty = $lm['mhperunit'];
                        $tdresult = $lm['nama_mesin'];
                        $nestingqty = floor($lm['sisa'] / $lm['mhperunit']);
                    // $qtyneeded = $item->qty - $assignedqty;
                        $qtyneeded = $item->qty - $item->assignqty;

                        if ($nestingqty > $qtyneeded) {
                            $nestingqty = $qtyneeded;
                            $totalmhneed = $qtyneeded * $lm['mhperunit'];
                            $sisamh = $lm['sisa'] - $totalmhneed;
                        } else {
                            $totalmhneed = $nestingqty * $lm['mhperunit'];
                            $sisamh = $lm['sisa'] - $totalmhneed;
                        }
                        foreach ($chartmesin as $key => $value) {
                            if ($value['nama'] == $lm['nama_mesin']) {
                                unset($chartmesin[$key]);
                            }
                        }
                        $add = [
                            'nama' => $lm['nama_mesin'],
                            'sisa' => $sisamh,
                            'pname' => $lm['pname'],
                            'tm' => $lm['mthick']
                        ];
                        array_push($chartmesin, $add);
                        break;
                    }
                } else {
                    $tdresult = 'DONE';
                    $nestingqty = 0;
                }

                if ($tdresult != '') {
                    $item->setAttribute('tdresult', $tdresult);
                    $item->setAttribute('nestingqty', $nestingqty);
                } else {
                    $item->setAttribute('tdresult', 'No machine can be suggested');
                    $item->setAttribute('nestingqty', 'None');
                }


                $temp = $item->qty - $item->finishedqty;
                
                $item->setAttribute('temp', $temp);


                $chart = (new DashboardModel())->getActualVsCapacity();
                $chartArray = json_decode(json_encode($chart), true);
              //  $chartmesin = array();
        
                //Deklarasi
                $ec = [];
                $eac = [];
                $es = [];
                $eca = [];
                $c = [];
                $ac = [];
                $ca = [];
                $s = [];
        
                foreach ($chartArray as $a) {
                    foreach ($chartmesin as $e) {
                        if ($a['mesin_nama_mesin'] == $e['nama']) {
                            $ec[] = $e['nama'];
                            $engine_actual = (float)$a['capacity'] - ($e['sisa'] / 60);
                            $eac[] = $engine_actual;
                            $es[] = $e['sisa'] / 60;
                            $eca[] = (float)$a['capacity'];
                        }
                    }
                    $c[] = $a['mesin_nama_mesin'];
                    $ac[] = (float)$a['actual'];
                    $ca[] = (float)$a['capacity'];
                    $s[] = (float)$a['sisa'];
                }
        
                if (empty($ec)) {
                    $ec = null;
                }
                if (empty($eac)) {
                    $eac = null;
                }
                if (empty($es)) {
                    $es = null;
                }
                if (empty($eca)) {
                    $eca = null;
                }
    
                $item->setAttribute('ec', $ec);
                $item->setAttribute('eac', $eac);
                $item->setAttribute('es', $es);
                $item->setAttribute('eca', $eca);


                
            }

            



            // Hitung total entri data secara keseluruhan
                $totalData = TbBase::count();


                return response()->json([
                    'draw' => $request->input('draw'), // untuk keperluan security bisa diabaikan jika tidak diperlukan
                    'recordsTotal' => $totalData, // Total entri data dari server
                    'recordsFiltered' => $data->total(), // Jumlah data yang terfilter
                    'data' => $data->items()
                ]);

        }


              
               
                return view('PBEngine.dashboard.index', 
                compact('ForMachineBreakdown', 
                        'ForTomorrow', 
                        'ForOnProgress', 
                        'ForFinish', 
                        'chartArray',
                        'ec',
                        'eac',
                        'es',
                        'eca',
                        'c',
                        'ac',
                        'ca',
                        's',
                        'mn',
                        'PN',
                        'Pname',
                        'think',
                        'selectcategoryfilter',
                        'selectorder',
                        'selectfield',
                        'amountdata',
                        
                        
                        ));
                      

   
    }

    private function date_sort_asc($a, $b) {
        return strtotime($a) - strtotime($b);
    }

    private function date_sort_desc($a, $b) {
        return strtotime($b) - strtotime($a);
    }

    public function resetFilter()
    {
        session([
            'filterProcessName' => null,
            'filterMaterial' => null,
            'filterThickness' => null,
            'filterPartNumberProduct' => null,
            'ddfiltercategory' => null,
            'ddkeyword' => null,
            'startdate' => null,
            'enddate' => null,
            'categorysorting' => null,
            'orderingsorting' => null,
            'amountdata' => 10
        ]);

        return view('PBEngine.dashboard.index');
    }

    public static function getMachineBreakdown(){
        $totalMesin = ( new TbMesin() )->get_breakdown_mesin_num();
    }

        
    public static function getDataAssign(){

       
        $no = 0;
       
        $perPage = 10;
        $data = TbBase::paginate($perPage);
        foreach($data as $item) {
            $no++;
            $anp = TbAssign::where('ANP_key_IMA', $item->mppid)->get();
            $item->setAttribute('ANP', $anp->toArray());
        
            $msn = [];
            foreach($anp as $assign) {
                $mesin = TbMesin::where('mesin_kode_mesin', $assign->ANP_mesin_kode_mesin)
                                ->where('mesin_status', 0)
                                ->first();
                if($mesin) {
                    $msn[] = $mesin->toArray();
                }
            }
            $item->setAttribute('MSNs', $msn);
        
            $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
                ->where('MIC_PN_product', $item->PN)
                ->where('MIC_Status_Aktifasi', 0)
                ->get();
            $item->setAttribute('MAP', $map->toArray());
        
        //    // Query tambahan
        //         $additionalData = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) as assignqty, SUM(ANP_qty_finish) as finishedqty')
        //         ->where('ANP_key_IMA', $item->mppid)
        //         ->groupBy('ANP_key_IMA')
        //         ->first();

        //     // Tambahkan data tambahan ke dalam item jika ada
        //     if ($additionalData) {
        //         $item->setAttribute('assignqty', $additionalData->assignqty);
        //         $item->setAttribute('finishedqty', $additionalData->finishedqty);
        //     } else {
        //         // Jika tidak ada data tambahan, set nilai default ke null
        //         $item->setAttribute('assignqty', 0);
        //         $item->setAttribute('finishedqty', null);
        //     }

            $qtyIN = 0;
            $tempqtyIN = (new MoveQTY_model())->getMovingIn($item['mppid']);
            $qtyOUT = 0;
            $tempqtyOUT = (new MoveQTY_model())->getMovingOut($item['mppid']);
            
            if ($tempqtyIN != null) {
                $qtyIN = $tempqtyIN->qtymove;
            }
            
            if ($tempqtyOUT != null) {
                $qtyOUT = $tempqtyOUT->qtymove;
            }
            
            $item['qtyIN'] = $qtyIN;
            $item['qtyOUT'] = $qtyOUT;

         

            $mapping = (new TbMappingPRO())->get_all_mapping();
            $cust = ''; // Inisialisasi dengan nilai default
            foreach ($mapping as $c) {
                if ($c->mapping_pro == $item->PRONumber) {
                    $cust = $c->customer_name;
                    // Jika mapping_pro cocok, langsung keluar dari loop
                    break;
                }
                // Jika tidak cocok, biarkan nilai $cust tetap string kosong
            }
            $item->setAttribute('customer', $cust);


             // Inisialisasi variabel
            $chartmesin = [];
            $arrayovermesin = [];
            // $no = 0;

            // Ini ngambil data dari view chart capacity vs actual
            $mesinAvail = (new TbMesin())->get_available_mesin();

            $sisa = 0;
            $mhperqty = 0;
            $nestingqty = 0;
            $tdresult = '';
            $assignedqty = 0;
            $finishqty = 0;
            $cek = 0;

            
            $arrayavailablemesin = [];

            if ($cek == 0) {
                // INI BUAT NGAMBIL DATA DARI PROCESS SEBELUMNYA
                foreach ($chartmesin as $cm) {
                    if ($cm['pname'] == $item->ProcessName) {
                        $cmsisa = $cm['sisa'];
                        $cmmanhourperqty = $item->MHProcess / $item->qty;
                        if ($cmsisa >= $cmmanhourperqty) {
                            $tempmesin = [
                                'sisa' => $cmsisa,
                                'mhperunit' => $cmmanhourperqty,
                                'nama_mesin' => $cm['nama'],
                                'pname' => $cm['pname'],
                                'mthick' => $cm['tm']
                            ];
                            array_push($arrayavailablemesin, $tempmesin);
                        } else {
                            $counter = 0;
                            foreach ($arrayovermesin as $arr) {
                                if ($arr['nama_over_mesin'] == $cm['nama']) {
                                    $counter = 1;
                                }
                            }
                            if ($counter == 0) {
                                $over = [
                                    'nama_over_mesin' => $cm['nama'],
                                    'nama_over_mesinprocess' => $cm['pname']
                                ];
                                array_push($arrayovermesin, $over);
                            }
                        }
                    }
                }
                
                // MENCARI MESIN YANG BELUM ADA DI ARRAY AVAILABLE MESIN
                foreach ($mesinAvail as $m) {
                    if ($m->process_name == $item->ProcessName) {
                        if (count($arrayavailablemesin) == 0) {
                            $counter = 0;
                            foreach ($arrayovermesin as $arr) {
                                if ($arr['nama_over_mesin'] == $m->mesin_nama_mesin) {
                                    $counter = 1;
                                }
                            }
                            
                            if ($counter == 0) {
                                $sisa = $m->sisa * 60;
                                $manhourperqty = $item->MHProcess / $item->qty;
                                $tempmesin = [
                                    'sisa' => $sisa,
                                    'mhperunit' => $manhourperqty,
                                    'nama_mesin' => $m->mesin_nama_mesin,
                                    'pname' => $m->process_name,
                                    'mthick' => 0
                                ];
                                array_push($arrayavailablemesin, $tempmesin);
                                
                            }
                        } else {
                            $counter = 0;
                            foreach ($arrayavailablemesin as $ar) {
                                if ($ar['nama_mesin'] == $m->mesin_nama_mesin) {
                                    $counter = 1;
                                }
                            }
                            foreach ($arrayovermesin as $arr) {
                                if ($arr['nama_over_mesin'] == $m->mesin_nama_mesin) {
                                    $counter = 1;
                                }
                            }

                            if ($counter == 0) {
                                $sisa = $m->sisa * 60;
                                $manhourperqty = $item->MHProcess / $item->qty;
                                $tempmesin = [
                                    'sisa' => $sisa,
                                    'mhperunit' => $manhourperqty,
                                    'nama_mesin' => $m->mesin_nama_mesin,
                                    'pname' => $m->process_name,
                                    'mthick' => 0
                                ];
                                array_push($arrayavailablemesin, $tempmesin);
                            }
                        }
                    }
                }
                


                $temp = array_column($arrayavailablemesin, 'sisa');
                array_multisort($temp, SORT_DESC, $arrayavailablemesin);
                

                foreach ($arrayavailablemesin as $lm) {
                    // $sisa = $lm['sisa'];
                    // $mhperqty = $lm['mhperunit'];
                    // $tdresult = $lm['nama_mesin'];
                    // $nestingqty = floor($lm['sisa'] / $lm['mhperunit']);
                    // $qtyneeded = $item->qty - $item->assignqty;

                    // if ($nestingqty > $qtyneeded) {
                    //     $nestingqty = $qtyneeded;
                    //     $totalmhneed = $qtyneeded * $lm['mhperunit'];
                    //     $sisamh = $lm['sisa'] - $totalmhneed;
                    // } else {
                    //     $totalmhneed = $nestingqty * $lm['mhperunit'];
                    //     $sisamh = $lm['sisa'] - $totalmhneed;
                    // }
                    // foreach ($chartmesin as $key => $value) {
                    //     if ($value['nama'] == $lm['nama_mesin']) {
                    //         unset($chartmesin[$key]);
                    //     }
                    // }
                    // $add = [
                    //     'nama' => $lm['nama_mesin'],
                    //     'sisa' => $sisamh,
                    //     'pname' => $lm['pname'],
                    //     'tm' => $lm['mthick']
                    // ];
                    // array_push($chartmesin, $add);
                    // break;

                    if($no == 1){
                        $sisa = $lm['sisa'];
                        $mhperqty = $lm['mhperunit'];
                        $tdresult = $lm['nama_mesin'];
                        $nestingqty = floor($lm['sisa']/$lm['mhperunit']);
                        $qtyneeded = $item->qty - $assignedqty;

                        if($nestingqty > $qtyneeded){
                          $nestingqty = $qtyneeded;
                          $totalmhneed = $qtyneeded * $lm['mhperunit'];
                          $sisamh = $lm['sisa'] - $totalmhneed; 
                        }else{
                          $totalmhneed = $nestingqty * $lm['mhperunit'];
                          $sisamh = $lm['sisa'] - $totalmhneed; 
                        }
                        foreach ($chartmesin as $key => $value){
                          if ($value['nama'] == $lm['nama_mesin']) {
                            unset($chartmesin[$key]);
                          }
                        }
                        $add = array(
                          'nama' => $lm['nama_mesin'],
                          'sisa' => $sisamh,
                          'pname' => $lm['pname'],
                          'tm' => $lm['mthick'] 
                        );
                        array_push($chartmesin,$add);
                        

                        break;
                      } 
                      else{
                        $count_array = count($lm);
                        if($lm['sisa'] < 1){
                        }else
                        {
                          $sisa = $lm['sisa'];
                          $mhperqty = $lm['mhperunit'];
                          $tdresult = $lm['nama_mesin'];
                          $nestingqty = @(floor($lm['sisa']/$lm['mhperunit']));
                        //   dd($nestingqty);
                          $qtyneeded = $item->qty - $assignedqty;

                          if($nestingqty > $qtyneeded){
                            $nestingqty = $qtyneeded;
                            $totalmhneed = $qtyneeded * $lm['mhperunit'];
                            $sisamh = $lm['sisa'] - $totalmhneed; 
                          }else{
                            $totalmhneed = $nestingqty * $lm['mhperunit'];
                            $sisamh = $lm['sisa'] - $totalmhneed; 
                          }
                          foreach ($chartmesin as $key => $value){
                            if ($value['nama']  == $lm['nama_mesin']) {
                              unset($chartmesin[$key]);
                            } 
                          }


                          $add = array(
                            'nama' => $lm['nama_mesin'],
                            'sisa' => $sisamh,
                            'pname' => $lm['pname'],
                            'tm' => $lm['mthick']   
                          );
                          array_push($chartmesin,$add);
                         

                          break;
                        }

                      }
                    // }
                }
            
            } else {
                $tdresult = 'DONE';
                $nestingqty = 0;
            }

            if ($tdresult != '') {
                $item->setAttribute('tdresult', $tdresult);
                $item->setAttribute('nestingqty', $nestingqty);
                $item->setAttribute('assignqty', $assignedqty);
                $item->setAttribute('finishedqty',$finishqty);
            } else {
                $item->setAttribute('tdresult', 'No machine can be suggested');
                $item->setAttribute('nestingqty', 'None');
                $item->setAttribute('assignqty', 0);
                $item->setAttribute('finishedqty',$finishqty);
            }

            $chart = (new DashboardModel())->getActualVsCapacity();
            $chartArray = json_decode(json_encode($chart), true);
          //  $chartmesin = array();
    
            //Deklarasi
            $ec = [];
            $eac = [];
            $es = [];
            $eca = [];
            $c = [];
            $ac = [];
            $ca = [];
            $s = [];
    
            foreach ($chartArray as $a) {
                foreach ($chartmesin as $e) {
                    if ($a['mesin_nama_mesin'] == $e['nama']) {
                        $ec[] = $e['nama'];
                        $engine_actual = (float)$a['capacity'] - ($e['sisa'] / 60);
                        $eac[] = $engine_actual;
                        $es[] = $e['sisa'] / 60;
                        $eca[] = (float)$a['capacity'];
                    }
                }
                $c[] = $a['mesin_nama_mesin'];
                $ac[] = (float)$a['actual'];
                $ca[] = (float)$a['capacity'];
                $s[] = (float)$a['sisa'];
            }
    
            if (empty($ec)) {
                $ec = null;
            }
            if (empty($eac)) {
                $eac = null;
            }
            if (empty($es)) {
                $es = null;
            }
            if (empty($eca)) {
                $eca = null;
            }

            $item->setAttribute('ec', $ec);
            $item->setAttribute('eac', $eac);
            $item->setAttribute('es', $es);
            $item->setAttribute('eca', $eca);

            $temp = $item->qty - $item->finishedqty;
                
            $item->setAttribute('temp', $temp);

           
           




            
        }
        //dd($data->toArray());
    
  
        return response()->json($data->toArray());

        
    }

    public function test2(){

        $dataArray = [];

        $perPage = 10; // Jumlah data per halaman
    
        $data = TbBase::paginate($perPage);

        foreach($data as $item) {
                $anp = TbAssign::where('ANP_key_IMA', $item->mppid)
                ->get();
                $item->setAttribute('ANP', $anp->toArray());

                $msn = [];
                foreach($anp as $assign) {
                    $mesin = TbMesin::where('mesin_kode_mesin', $assign->ANP_mesin_kode_mesin)
                                    ->where('mesin_status', 0)
                                    ->first();
                    if($mesin) {
                        $msn[] = $mesin->toArray();
                    }
                }
                $item->setAttribute('MSNs', $msn);

                $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
                    ->where('MIC_PN_product', $item->PN)
                    ->where('MIC_Status_Aktifasi', 0)
                    ->get();
                $item->setAttribute('MAP', $map->toArray());


                // Query tambahan
                $additionalData = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) as assignqty, SUM(ANP_qty_finish) as finishedqty')
                ->where('ANP_key_IMA', $item->mppid)
                ->groupBy('ANP_key_IMA')
                ->first();

                // Tambahkan data tambahan ke dalam item jika ada
                if ($additionalData) {
                    $item->setAttribute('assignedqty', $additionalData->assignedqty);
                    $item->setAttribute('finishedqty', $additionalData->finishedqty);
                } else {
                    // Jika tidak ada data tambahan, set nilai default ke null
                    $item->setAttribute('assignedqty', 0);
                    $item->setAttribute('finishedqty', null);
                }



              // Inisialisasi variabel
                $chartmesin = array();
                $arrayovermesin = array();
                $no= 0;

                //ini ngambil data dari view chart capacity vs actual
                $mesinAvail = new TbMesin();

                $assign_ = new TbAssign();
                $Assign1[] = $assign_ ->getAllAssign();

                $mapPRO = new TbMappingPRO();
                $mapping[] = $mapPRO->get_all_mapping();


                $sisa = 0;
                $mhperqty = 0;
                $nestingqty = 0;
                $tdresult = '';
                $cek = 0;

                $no++;
                $arrayavailablemesin = [2];
                    
               
              

                $item = array();
                foreach($item as $a){
                    $assignedqty = $a['assignedqty'];
                    $finishedqty = $a['finishedqty'];
                   
                   

                    if($a['qty'] <= $finishedqty){
                        $cek = 1;
                    }else if($a['qty'] <= $assignedqty){
                        $cek = 1;
                    }

                        if($cek == 0){
                            //INI BUAT NGAMBIL DATA DARI PROCESS SEBELUMNYA

                            foreach($chartmesin as $cm)
                            {
                                // if($cm['pname'] == $a['ProcessName'])
                                // {
                                    $cmsisa = $cm['sisa'];
                                    $cmmanhourperqty = $a['MHProcess']/$a['qty'];
                                    if($cmsisa >= $cmmanhourperqty)
                                    {
                                        $tempmesin = [
                                        'sisa' => $cmsisa,
                                        'mhperunit' => $cmmanhourperqty,
                                        'nama_mesin' => $cm['nama'],
                                        'pname' => $cm['pname'],
                                        'mthick' => $cm['tm']
                                        ];
                                    // $arrayavailablemesin[] = $tempmesin;
                                        array_push($arrayavailablemesin, $tempmesin);

                                    dd($arrayavailablemesin);

                                        
                                    // }
                                    // else{
                                    //     $counter = 0;
                                    //     foreach ($arrayovermesin as $keyy => $arr){
                                    //         if ($arr['nama_over_mesin']  == $cm['nama']) {
                                    //         $counter = 1;
                                    //         } 
                                    //     }
                                    //     if($counter == 0){
                                    //         $over = array(
                                    //         'nama_over_mesin' => $cm['nama'],'nama_over_mesinprocess' => $cm['pname']
                                    //         );
                                    //         array_push($arrayovermesin,$over);
                                    //     }
                                    // }    
                                    
                                }
                            
                            }
                            //;




                            //Mencari Mesin yang blm ada di array available mesin
                            // foreach($msnAvail as $m)
                            foreach($msnAvail as $m)
                            {
                                if($m->process_name == $a['ProcessName']){
                                // if($m['process_name'] == $a->ProcessName){
                                    if(count($arrayavailablemesin)== 0){
                                        $counter = 0;
                                        foreach($arrayovermesin as $arr){
                                            if($arr['nama_over_mesin'] == $m->mesin_nama_mesin){
                                                $counter = 1;
                                            }
                                        }
                                        if($counter == 0 ){
                                            $sisa = $m->sisa*60;
                                            $manhourperqty = $a['MHProcess']/$a['qty'];
                                            $tempmesin = array (
                                                'sisa' => $sisa,
                                                'mhperunit' => $manhourperqty,
                                                'pname' =>$m->process_name,
                                                'mthick' => 0
                                            );
                                            array_push($arrayavailablemesin, $tempmesin);
                                        }
                                    }
                                    else{
                                        $counter = 0 ;
                                        foreach($arrayavailablemesin as $ar){
                                            // if($ar['nama_mesin'] == $m->mesin_nama_mesin){
                                            if($ar['nama_mesin'] == $m['mesin_nama_mesin']){
                                                $counter = 1;
                                            }
                                        }
                                        foreach($arrayovermesin as $arr){
                                            if($arr['nama_over_mesin'] == $m->mesin_nama_mesin){
                                                $counter = 1;
                                            }
                                        }

                                        if($counter == 0){
                                            $sisa = $m->sisa * 60;
                                            $manhourperqty = $a['MHProcess']/$a['qty'];
                                            $tempmesin = array (
                                                'sisa' => $sisa,
                                                'mhperunit' => $manhourperqty,
                                                'pname' =>$m->process_name,
                                                'mthick' => 0
                                            );
                                            array_push($arrayavailablemesin, $tempmesin);
                                        }

                                    }
                                }
                                
                            }

                            $temp = array();
                            foreach ($arrayavailablemesin as $key => $row) {
                                $temp[$key] = $row['sisa'];
                            } 
                            array_multisort($temp, SORT_DESC, $arrayavailablemesin);

                            foreach($arrayavailablemesin as $lm){

                                if($no == 1){
                                    $sisa = $lm['sisa'];
                                    $mhperqty = $lm['mhperunit'];
                                    $tdresult = $lm['nama_mesin'];
                                    $nestingqty = floor($lm['sisa']/$lm['mhperunit']);
                                    $qtyneeded = $a['qty'] - $assignedqty;

                                    if($nestingqty > $qtyneeded){
                                        $nestingqty = $qtyneeded;
                                        $totalmhneed = $qtyneeded * $lm['mhperunit'];
                                        $sisamh = $lm['sisa'] - $totalmhneed; 
                                    }else{
                                        $totalmhneed = $nestingqty * $lm['mhperunit'];
                                        $sisamh = $lm['sisa'] - $totalmhneed; 
                                    }
                                    foreach ($chartmesin as $key => $value){
                                        if ($value['nama'] == $lm['nama_mesin']) {
                                            unset($chartmesin[$key]);
                                        }
                                    }
                                    $add = array(
                                    'nama' => $lm['nama_mesin'],
                                    'sisa' => $sisamh,
                                    'pname' => $lm['pname'],
                                    'tm' => $lm['mthick'] 
                                    );
                                    array_push($chartmesin,$add);

                                    break;
                                }
                                else{

                                    $count_array = count($lm);
                                    if($lm['sisa'] < 1){
                                    }else
                                    {
                                        $sisa = $lm['sisa'];
                                        $mhperqty = $lm['mhperunit'];
                                        $tdresult = $lm['nama_mesin'];
                                        $nestingqty = @(floor($lm['sisa']/$lm['mhperunit']));
                                        $qtyneeded = $a['qty'] - $assignedqty;

                                        if($nestingqty > $qtyneeded){
                                            $nestingqty = $qtyneeded;
                                            $totalmhneed = $qtyneeded * $lm['mhperunit'];
                                            $sisamh = $lm['sisa'] - $totalmhneed; 
                                        }else{
                                            $totalmhneed = $nestingqty * $lm['mhperunit'];
                                            $sisamh = $lm['sisa'] - $totalmhneed; 
                                        }
                                        foreach ($chartmesin as $key => $value){
                                            if ($value['nama']  == $lm['nama_mesin']) {
                                            unset($chartmesin[$key]);
                                            } 
                                        }


                                        $add = array(
                                            'nama' => $lm['nama_mesin'],
                                            'sisa' => $sisamh,
                                            'pname' => $lm['pname'],
                                            'tm' => $lm['mthick']   
                                        );
                                        array_push($chartmesin,$add);

                                        break;

                                    }
                                }
                            }
                        }
                        else{
                            $tdresult = 'DONE';
                            $nestingqty = 0;
                        }

                        if($tdresult != ''){
                            $dataArray[] = [
                                'tdresult' => $tdresult,
                                'nestingqty' => $nestingqty,
                                'assignedqty' => $assignedqty,
                                'finishedqty' => $finishedqty,
                            ];
                        }else{
                            $dataArray[] = [
                                'message' => 'No machine can be suggested',
                                'status' => 'None',
                                'nestingqty' => 0,
                                'finishedqty' => $finishedqty,
                            ];
                        }
                        $html = '<td>';
                        $html .= '<button data-toggle="modal" data-target="#md-movein" type="button" class="btn btn-danger movein" style="margin-top: 5px;" id="' . $a['mppid'] . '">' . 'IN : ' . $a['qtymovein'] . '</button>';
                        $html .= '<button data-toggle="modal" data-target="#md-moveout" type="button" class="btn btn-danger moveout" style="margin-top: 5px;" id="' . $a['mppid'] . '">' . 'OUT : ' . $a['qtymoveout'] . '</button>';
                        $html .= '</td>';


                        //return $html;

                        $assignmesin = 0;
                        // foreach($Assign as $asgn){
                        foreach($anp as $asgn){
                            if($asgn['ANP_key_IMA'] == $a['mppid']){
                                $assignmesin = 1;
                                $progres = null;
                                
                                switch ($asgn->ANP_progres) {
                                    case 0: 
                                        $progres = 'Not Started';
                                        break;
                                    case 1: 
                                        $progres = 'Started';
                                        break;  
                                    case 2: 
                                        $progres = 'Paused';
                                        break;
                                    case 3: 
                                        $progres = 'Stopped';
                                        break;  
                                    case 4: 
                                        $progres = 'Finished';
                                        break;   
                                    default:
                                        // default case
                                        break;
                                }

                                $html1 = '<td>';
                                $html1 .= '<button type="button" class="btn btn-danger" style="margin-top: 5px;">'.$asgn['mesin_nama_mesin'].' : '.$progres.'</button>';
                                $html1 .= '</td>';
                            }
                        }
                        if($assignmesin == 0){
                            $html1 = 'Not Set';
                        }

                        $set = 0;
                        
                        // foreach($mapping as $c){
                        foreach($map as $c){
                            if($a['PRONumber'] == $c['mapping_pro']){
                                $cs = $c['customer_name'];
                                $set = 1;
                                break;
                            }
                            if($set == 0){
                                $cs = 'not set';
                            }

                            if ($tdresult == 'DONE') {
                                echo 'FULL ASSIGNED';
                            } else {
                                echo '<button data-toggle="modal" data-target="#md-assign" style="height: 30px; width: 35px;" id="' . $a['mppid'] . '+' . $tdresult . '+' . $nestingqty . '" class="btn btn-space btn-danger assign"><i id="' . $a['mppid'] . '/' . $tdresult . '" data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
                            }
                        }
                
                    }
        }
        return response()->json($data);
        
    }

        
}

        
    
    
    
    


    
    

   




