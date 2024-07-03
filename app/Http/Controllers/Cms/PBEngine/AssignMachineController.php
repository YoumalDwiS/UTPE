<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\MoveQTY_model;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbCustomer;
use App\Models\Table\PBEngine\TbMappingPRO;
use App\Models\Table\PBEngine\TbMappingImageComponent;

use App\Models\Table\PBEngine\TbMesin;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Exception;

class AssignMachineController extends Controller
{

    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('assign-machine') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {

        try {
            if ($this->PermissionActionMenu('assign-machine')->r == 1) {

                if($request->ajax()) {
        
                    $cacheKey = 'tb_assign_nesting_programmer';
        
                        // Cek apakah data ada dalam cache
                        if (Cache::has($cacheKey)) {
                            $data = Cache::get($cacheKey);
                        } else {
                            $model = new TbAssign(); // Ganti YourModel dengan nama model yang sesuai
                            $data = $model->get_All_Assign();
        
                            // Simpan data ke dalam cache
                            // Cache::put($cacheKey, $data, 10); // Simpan selama 10 menit
                        }
        
                    return Datatables::of($data)
                        ->addIndexColumn()
                        ->make(true);
                }
                    
                return view('PBEngine.assign.index');
                

            } else {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Access Denied!');
        }
        
    }

    public function test($mppid)
    {

        $data = TbBase::where('mppid', $mppid)->get();

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

            $assign = (new TbAssign())->getAllAssign();
            $progressMachineFound = false;
            foreach ($assign as $asgn) {
                if ($asgn->ANP_key_IMA == $item->mppid) {
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
                            $progres = 'Stoped';
                            break;
                        case 4:
                            $progres = 'Finished';
                            break;
                        default:
                            // code...
                            break;
                    }
                    $item->setAttribute('progressMachine', $asgn->mesin_nama_mesin);
                    $item->setAttribute('progressMachine1', $progres);
                    $progressMachineFound = true;
                }
            }

            // Jika progress machine tidak ditemukan, set nilai progress machine menjadi "Not Set"
            if (!$progressMachineFound) {
                $item->setAttribute('progressMachine', 'Not Set');
                $item->setAttribute('progressMachine1', 'Null');
            }


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
                                if ($arr['nama_over_mesin'] == $m->mesin_kode_mesin) {
                                    $counter = 1;
                                }
                            }
                            if ($counter == 0) {
                                $sisa = $m->sisa * 60;
                                $manhourperqty = $item->MHProcess / $item->qty;
                                $tempmesin = [
                                    'sisa' => $sisa,
                                    'mhperunit' => $manhourperqty,
                                    'nama_mesin' => $m->mesin_kode_mesin,
                                    'pname' => $m->process_name,
                                    'mthick' => 0
                                ];
                                array_push($arrayavailablemesin, $tempmesin);
                            }
                        } else {
                            $counter = 0;
                            foreach ($arrayavailablemesin as $ar) {
                                if ($ar['nama_mesin'] == $m->mesin_kode_mesin) {
                                    $counter = 1;
                                }
                            }
                            foreach ($arrayovermesin as $arr) {
                                if ($arr['nama_over_mesin'] == $m->mesin_kode_mesin) {
                                    $counter = 1;
                                }
                            }

                            if ($counter == 0) {
                                $sisa = $m->sisa * 60;
                                $manhourperqty = $item->MHProcess / $item->qty;
                                $tempmesin = [
                                    'sisa' => $sisa,
                                    'mhperunit' => $manhourperqty,
                                    'nama_mesin' => $m->mesin_kode_mesin,
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
                //     $sisa = $lm['sisa'];
                //     $mhperqty = $lm['mhperunit'];
                //     $tdresult = $lm['nama_mesin'];
                //     $nestingqty = floor($lm['sisa'] / $lm['mhperunit']);
                // // $qtyneeded = $item->qty - $assignedqty;
                //     $qtyneeded = $item->qty - $item->assignqty;

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

            if ($tdresult != '') {
                $item->setAttribute('tdresult', $tdresult);
                $item->setAttribute('nestingqty', $nestingqty);
            } else {
                $item->setAttribute('tdresult', 'No machine can be suggested');
                $item->setAttribute('nestingqty', 'None');
            }


            $temp = $item->qty - $item->finishedqty;
            
            $item->setAttribute('temp', $temp);
        }
    

        $mesin = TbMesin::select('mesin_kode_mesin as kode_mesin', 'mesin_nama_mesin', 'mesin_status')
                ->where('mesin_status', 0)
                ->where('mesin_delete_status', 0)
                ->get();

        return view('PBEngine.assign.modal_assign', compact('data', 'mesin'));
        

        

    }

    // public function test1($mppid)
    // {
    //     $data = TbBase::where('mppid', $mppid)->get();

    //     foreach ($data as $item) {
    //         $anp = TbAssign::where('ANP_key_IMA', $item->mppid)->get();
    //         $item->setAttribute('ANP', $anp->toArray());

    //         $msn = [];
    //         foreach($anp as $assign) {
    //             $mesin = TbMesin::where('mesin_kode_mesin', $assign->ANP_mesin_kode_mesin)
    //                             ->where('mesin_status', 0)
    //                             ->first();
    //             if($mesin) {
    //                 $msn[] = $mesin->toArray();
    //             }
    //         }
    //         $item->setAttribute('MSNs', $msn);

    //         $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
    //             ->where('MIC_PN_product', $item->PN)
    //             ->where('MIC_Status_Aktifasi', 0)
    //             ->get();
    //         $item->setAttribute('MAP', $map->toArray());

    //         // Query tambahan
    //         $additionalData = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) as assignqty, SUM(ANP_qty_finish) as finishedqty')
    //         ->where('ANP_key_IMA', $item->mppid)
    //         ->groupBy('ANP_key_IMA')
    //         ->first();

    //         // Tambahkan data tambahan ke dalam item jika ada
    //         if ($additionalData) {
    //             $item->setAttribute('assignedqty', $additionalData->assignedqty);
    //             $item->setAttribute('finishedqty', $additionalData->finishedqty);
    //         } else {
    //             // Jika tidak ada data tambahan, set nilai default ke null
    //             $item->setAttribute('assignedqty', null);
    //             $item->setAttribute('finishedqty', null);
    //         }

            
            
    //         $assign = (new TbAssign())->getAllAssign();
    //         $progressMachineFound = false;
    //         foreach ($assign as $asgn) {
    //             if ($asgn->ANP_key_IMA == $item->mppid) {
    //                 $progres = null;
    //                 switch ($asgn->ANP_progres) {
    //                     case 0:
    //                         $progres = 'Not Started';
    //                         break;
    //                     case 1:
    //                         $progres = 'Started';
    //                         break;
    //                     case 2:
    //                         $progres = 'Paused';
    //                         break;
    //                     case 3:
    //                         $progres = 'Stoped';
    //                         break;
    //                     case 4:
    //                         $progres = 'Finished';
    //                         break;
    //                     default:
    //                         // code...
    //                         break;
    //                 }
    //                 $item->setAttribute('progressMachine', $asgn->mesin_nama_mesin);
    //                 $item->setAttribute('progressMachine1', $progres);
    //                 $progressMachineFound = true;
    //             }
    //         }

    //         // Jika progress machine tidak ditemukan, set nilai progress machine menjadi "Not Set"
    //         if (!$progressMachineFound) {
    //             $item->setAttribute('progressMachine', 'Not Set');
    //             $item->setAttribute('progressMachine1', 'Null');
    //         }


    //         $mapping = (new TbMappingPRO())->get_all_mapping();
    //         $cust = 'not set'; // Inisialisasi dengan 'not set' sebagai nilai default
    //         foreach ($mapping as $c) {
    //             if ($c->mapping_pro == $item->PRONumber) {
    //                 $cust = $c->customer_name;
    //                 // Jika mapping_pro cocok, langsung keluar dari loop
    //                 break;
    //             }
    //         }
    //         $item->setAttribute('customer', $cust);


    //          // Inisialisasi variabel
    //         $chartmesin = [];
    //         $arrayovermesin = [];
    //         $no = 0;

    //         // Ini ngambil data dari view chart capacity vs actual
    //         $mesinAvail = (new TbMesin())->get_available_mesin();

    //         $sisa = 0;
    //         $mhperqty = 0;
    //         $nestingqty = 0;
    //         $tdresult = '';
    //         $assignedqty = 0;
    //         $finishqty = 0;
    //         $cek = 0;

    //         $no++;
    //         $arrayavailablemesin = [];

    //         if($item->qty <= $item->finishedqty){
    //             $cek = 1;
    //           }else if($item->qty <= $item->assignedqty){
    //             $cek = 1;
    //           }


    //         if ($cek == 0) {
    //             // INI BUAT NGAMBIL DATA DARI PROCESS SEBELUMNYA
    //             foreach ($chartmesin as $cm) {
    //                 if ($cm['pname'] == $item->ProcessName) {
    //                     $cmsisa = $cm['sisa'];
    //                     $cmmanhourperqty = $item->MHProcess / $item->qty;
    //                     if ($cmsisa >= $cmmanhourperqty) {
    //                         $tempmesin = [
    //                             'sisa' => $cmsisa,
    //                             'mhperunit' => $cmmanhourperqty,
    //                             'nama_mesin' => $cm['nama'],
    //                             'pname' => $cm['pname'],
    //                             'mthick' => $cm['tm']
    //                         ];
    //                         array_push($arrayavailablemesin, $tempmesin);
    //                     } else {
    //                         $counter = 0;
    //                         foreach ($arrayovermesin as $arr) {
    //                             if ($arr['nama_over_mesin'] == $cm['nama']) {
    //                                 $counter = 1;
    //                             }
    //                         }
    //                         if ($counter == 0) {
    //                             $over = [
    //                                 'nama_over_mesin' => $cm['nama'],
    //                                 'nama_over_mesinprocess' => $cm['pname']
    //                             ];
    //                             array_push($arrayovermesin, $over);
    //                         }
    //                     }
    //                 }
    //             }

    //             // MENCARI MESIN YANG BELUM ADA DI ARRAY AVAILABLE MESIN
    //             foreach ($mesinAvail as $m) {
    //                 if ($m->process_name == $item->ProcessName) {
    //                     if (count($arrayavailablemesin) == 0) {
    //                         $counter = 0;
    //                         foreach ($arrayovermesin as $arr) {
    //                             if ($arr['nama_over_mesin'] == $m->mesin_kode_mesin) {
    //                                 $counter = 1;
    //                             }
    //                         }
    //                         if ($counter == 0) {
    //                             $sisa = $m->sisa * 60;
    //                             $manhourperqty = $item->MHProcess / $item->qty;
    //                             $tempmesin = [
    //                                 'sisa' => $sisa,
    //                                 'mhperunit' => $manhourperqty,
    //                                 'nama_mesin' => $m->mesin_kode_mesin,
    //                                 'pname' => $m->process_name,
    //                                 'mthick' => 0
    //                             ];
    //                             array_push($arrayavailablemesin, $tempmesin);
    //                         }
    //                     } else {
    //                         $counter = 0;
    //                         foreach ($arrayavailablemesin as $ar) {
    //                             if ($ar['nama_mesin'] == $m->mesin_kode_mesin) {
    //                                 $counter = 1;
    //                             }
    //                         }
    //                         foreach ($arrayovermesin as $arr) {
    //                             if ($arr['nama_over_mesin'] == $m->mesin_kode_mesin) {
    //                                 $counter = 1;
    //                             }
    //                         }

    //                         if ($counter == 0) {
    //                             $sisa = $m->sisa * 60;
    //                             $manhourperqty = $item->MHProcess / $item->qty;
    //                             $tempmesin = [
    //                                 'sisa' => $sisa,
    //                                 'mhperunit' => $manhourperqty,
    //                                 'nama_mesin' => $m->mesin_kode_mesin,
    //                                 'pname' => $m->process_name,
    //                                 'mthick' => 0
    //                             ];
    //                             array_push($arrayavailablemesin, $tempmesin);
    //                         }
    //                     }
    //                 }
    //             }

    //             $temp = array_column($arrayavailablemesin, 'sisa');
    //             array_multisort($temp, SORT_DESC, $arrayavailablemesin);

    //             foreach ($arrayavailablemesin as $lm) {
    //                 $sisa = $lm['sisa'];
    //                 $mhperqty = $lm['mhperunit'];
    //                 $tdresult = $lm['nama_mesin'];
    //                 $nestingqty = floor($lm['sisa'] / $lm['mhperunit']);
    //             // $qtyneeded = $item->qty - $assignedqty;
    //                 $qtyneeded = $item->qty - $item->assignqty;

    //                 if ($nestingqty > $qtyneeded) {
    //                     $nestingqty = $qtyneeded;
    //                     $totalmhneed = $qtyneeded * $lm['mhperunit'];
    //                     $sisamh = $lm['sisa'] - $totalmhneed;
    //                 } else {
    //                     $totalmhneed = $nestingqty * $lm['mhperunit'];
    //                     $sisamh = $lm['sisa'] - $totalmhneed;
    //                 }
    //                 foreach ($chartmesin as $key => $value) {
    //                     if ($value['nama'] == $lm['nama_mesin']) {
    //                         unset($chartmesin[$key]);
    //                     }
    //                 }
    //                 $add = [
    //                     'nama' => $lm['nama_mesin'],
    //                     'sisa' => $sisamh,
    //                     'pname' => $lm['pname'],
    //                     'tm' => $lm['mthick']
    //                 ];
    //                 array_push($chartmesin, $add);
    //                 break;
    //             }
    //         } else {
    //             $tdresult = 'DONE';
    //             $nestingqty = 0;
    //         }

    //         if ($tdresult != '') {
    //             $item->setAttribute('tdresult', $tdresult);
    //             $item->setAttribute('nestingqty', $nestingqty);
    //         } else {
    //             $item->setAttribute('tdresult', 'No machine can be suggested');
    //             $item->setAttribute('nestingqty', 'None');
    //         }


    //         $temp = $item->qty - $item->finishedqty;
            
    //         $item->setAttribute('temp', $temp);
    //     }
    

    //     $mesin = TbMesin::where('mesin_status', 0)->get();


    //     return view('PBEngine.assign.modal_assign', compact('data', 'mesin'));
    // }

    public function save(Request $request)
    {

        $request->validate([
            'mppid' => 'required|string',
            'partNumberProduct' => 'required|string',
            'productName' => 'required|string',
            'PRONumber' => 'required|string',
            'PartNumberComponent' => 'required|string',
            'PartNameComponent' => 'required|string',
            'MaterialName' => 'required|string',
            'Thickness' => 'required|string',
            'Length' => 'required|string',
            'Width' => 'required|string',
            'weight' => 'required|string',
            // 'SubCompoName' => 'required|string',
            'ProcessName' => 'required|string',
            'MHProcess' => 'required|string',
            'PlanStartdate' => 'required|string',
            'PlanEndDate' => 'required|string',
            'mesin' => 'required|string',
            'qtyy' => 'required|integer',
            'Urgency' => 'required|boolean',
           
        ]);
    
        // Menyimpan data ke database
        $data = new TbAssign();
        $data->ANP_mesin_kode_mesin = $request->mesin;
        $data->ANP_data_PRO = $request->PRONumber;
        $data->ANP_progres = 0;
        $data->ANP_key_IMA = $request->mppid;
        $data->ANP_qty = $request->qtyy;
        $data->ANP_data_code = $request->mppid;
        $data->ANP_data_duedate = $request->PlanStartdate;
        $data->ANP_data_mhprosess = $request->MHProcess;
        $data->ANP_created_by = auth()->user()->id;
        $data->ANP_created_at = Carbon::now();
        $data->ANP_modified_by = auth()->user()->id;
        $data->ANP_modified_at = Carbon::now();
        $data->ANP_estimate_startdate = $request->PlanStartdate;
        $data->ANP_estimate_enddate = $request->PlanEndDate;
        $data->ANP_urgency = $request->Urgency;
        $data->ANP_qty_finish = 0;

        $data->save();

         // Memperbarui status_assign di base_table
        $baseData = TbBase::where('mppid', $request->mppid)->first();
        if ($baseData) {
            $baseData->status_assign = 1; // Ganti dengan status yang sesuai
            $baseData->save();
        } else {
            return response()->json(['error' => 'Data tidak ditemukan di base_table'], 404);
        }

        // return response()->json(['success' => 'Data berhasil disimpan dan diperbarui']);
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $data  
        ]);
        // return redirect()->view('PBEngine.dashboard.index')->with('success', 'Data berhasil disimpan dan diperbarui');
        
        
    

    }

    public function moving($mppid){

        $model_mesin = new TbMesin();

        $data = TbBase::where('mppid', $mppid)->get();

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

            
            
            $assign = (new TbAssign())->getAllAssign();
            $progressMachineFound = false;
            foreach ($assign as $asgn) {
                if ($asgn->ANP_key_IMA == $item->mppid) {
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
                            $progres = 'Stoped';
                            break;
                        case 4:
                            $progres = 'Finished';
                            break;
                        default:
                            // code...
                            break;
                    }
                    $item->setAttribute('progressMachine', $asgn->mesin_nama_mesin);
                    $item->setAttribute('progressMachine1', $progres);
                    $progressMachineFound = true;
                }
            }

            // Jika progress machine tidak ditemukan, set nilai progress machine menjadi "Not Set"
            if (!$progressMachineFound) {
                $item->setAttribute('progressMachine', 'Not Set');
                $item->setAttribute('progressMachine1', 'Null');
            }


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
                                if ($arr['nama_over_mesin'] == $m->mesin_kode_mesin) {
                                    $counter = 1;
                                }
                            }
                            if ($counter == 0) {
                                $sisa = $m->sisa * 60;
                                $manhourperqty = $item->MHProcess / $item->qty;
                                $tempmesin = [
                                    'sisa' => $sisa,
                                    'mhperunit' => $manhourperqty,
                                    'nama_mesin' => $m->mesin_kode_mesin,
                                    'pname' => $m->process_name,
                                    'mthick' => 0
                                ];
                                array_push($arrayavailablemesin, $tempmesin);
                            }
                        } else {
                            $counter = 0;
                            foreach ($arrayavailablemesin as $ar) {
                                if ($ar['nama_mesin'] == $m->mesin_kode_mesin) {
                                    $counter = 1;
                                }
                            }
                            foreach ($arrayovermesin as $arr) {
                                if ($arr['nama_over_mesin'] == $m->mesin_kode_mesin) {
                                    $counter = 1;
                                }
                            }

                            if ($counter == 0) {
                                $sisa = $m->sisa * 60;
                                $manhourperqty = $item->MHProcess / $item->qty;
                                $tempmesin = [
                                    'sisa' => $sisa,
                                    'mhperunit' => $manhourperqty,
                                    'nama_mesin' => $m->mesin_kode_mesin,
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
            // $check = (new TbAssign())->checkAssign($mppid);

        }

        $check = (new TbAssign())->checkAssign($mppid);
        // dd($check);
    
        // $data['RelatedMesin'] = $model_mesin->get_mesin_by_processid($check[0]['prosess_id']);
        // $mesin = $model_mesin->get_mesin_by_process_id($check[0]['prosess_id']);
        
        // dd($data);

        $check = (new TbAssign())->checkAssign($mppid);

        $mesin = [];
        // Periksa apakah array $check memiliki setidaknya satu elemen
        if (!empty($check)) {
            // Periksa apakah elemen pertama dari array $check memiliki kunci 'prosess_id'
            if (isset($check[0]['prosess_id'])) {
                $mesin = $model_mesin->get_mesin_by_process_id($check[0]['prosess_id']);
                // Lanjutkan dengan penggunaan $mesin
            } else {
                echo "Error: 'prosess_id' key not found in the first element of the check array.";
            }
        } else {
            // echo "No data found for the given mppid.";
        }

    


        return view('PBEngine.assign.modal_moving_machine', compact('data', 'mesin', 'check'));
       
        

    }

    public function save_moving(Request $request){

       

        $Assign_model = new TbAssign();

        $now = Carbon::now();
        $array_finish_qty = $request->input('finishqty');
        // dd($array_finish_qty);
        $array_moving_machine = $request->input('mesintujuan');
        // dd($array_moving_machine);
        $array_note = $request->input('note');
        // dd($array_note);
        $anp =  $request->input('anpid');
        // dd($anp);
        $indexdata = 0;
        foreach ($anp as $anpid) {
            $datacheck = $Assign_model->get_assign_by_id($anpid);
            // dd($datacheck);
            if($datacheck[0]->ANP_qty_finish == null){
                $qf = 0;
            }else{
                $qf = $datacheck[0]->ANP_qty_finish;
            }
            $last_qty = intval($datacheck[0]->ANP_qty_finish) + intval($array_finish_qty);

            // dd($last_qty);
            $paramanp = array(
                'ANP_qty_finish' => $last_qty,
                'ANP_progres' => 4,
                'ANP_modified_by' => auth()->user()->id,
                'ANP_modified_at' => $now->format('Y-m-d H:i:s'),
                'ANP_note'=> $array_note
            );
            // dd($paramanp);
            $Assign_model->update_assign($anpid,$paramanp);
            $qtynew = $datacheck[0]->ANP_qty-$last_qty;
            // dd($qtynew);
            if($qtynew > 0){
                $params = array(
                    'ANP_mesin_kode_mesin' => $array_moving_machine,
                    'ANP_data_PRO' => $datacheck[0]->ANP_data_PRO,
                    'ANP_progres' => 0,
                    'ANP_key_IMA' => $datacheck[0]->ANP_key_IMA,
                    'ANP_qty' => $qtynew,
                    'ANP_data_code' => $datacheck[0]->ANP_data_code,
                    'ANP_data_duedate' => $datacheck[0]->ANP_data_duedate,
                    'ANP_data_mhprosess' => $datacheck[0]->ANP_data_mhprosess,
                    'ANP_created_by' => auth()->user()->id,
                    'ANP_created_at' => $now->format('Y-m-d H:i:s'),
                    'ANP_modified_by' => auth()->user()->id,
                    'ANP_modified_at' => $now->format('Y-m-d H:i:s'),
                    'ANP_estimate_startdate' => $datacheck[0]->ANP_estimate_startdate,
                    'ANP_estimate_enddate' => $datacheck[0]->ANP_estimate_enddate,
                    'ANP_urgency' => $datacheck[0]->ANP_urgency,
                    'ANP_qty_finish' => 0,
                );
                // dd($params);
                $Assign_model->add_assign($params);
            }
              
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!'
        ]);

        // return redirect('move-quantity/');
    }

    public function m_history_movein($mppid){
        $moveQty = new MoveQTY_model();
        $historyData =$moveQty->get_movein_by_mppid($mppid);
        // dd($historyData);
        $data['list'] = array();
        foreach($historyData as $hd){
            $mvKeydataTarget = $hd->mv_keydata_source;
            // dd($mvKeydataTarget);
            $detailData = $moveQty->get_detail_data($mvKeydataTarget);
            // dd($detailData);
            $params =  array(
                'customer_name' => $detailData[0]->customer_name,
                'PRONumber' => $detailData[0]->PRONumber ,
                'PN' => $detailData[0]->PN,
                'PartNumberComponent' => $detailData[0]->PartNumberComponent,
                'qtyin' => $hd->mv_qty,
            );
            array_push($data['list'] , $params);
            // dd($data['list']);
        }
        return view('PBEngine.assign.modal_list_move', compact('data'));
    }

    public function m_history_moveout($mppid){
        $moveQty = new MoveQTY_model();
        $historyData = $moveQty->get_moveout_by_mppid($mppid);
        // dd($historyData);
        $data['list'] = array();
        foreach($historyData as $hd){
            $detailData = (new MoveQTY_model())->get_detail_data($hd['mv_keydata_target']);
            $params =  array(
                'customer_name' => $detailData['customer_name'],
                'PRONumber' => $detailData['PRONumber'] ,
                'PN' => $detailData['PN'],
                'PartNumberComponent' => $detailData['PartNumberComponent'],
                'qtyin' => $hd['mv_qty'],
            );
            array_push($data['list'] , $params);
        }
        return view('PBEngine.assign.modal_list_move', compact('data'));
    }
    
    
}
