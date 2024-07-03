<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbHistoryMoving;
use App\Models\Table\PBEngine\TbMappingImageComponent;
use Illuminate\Http\Request;

class MoveQuantityController extends Controller
{
    public function allAvailableMove(){
        $finishData= TbAssign::select('tb_assign_nesting_programmer.*', 'c.customer_name','bt.mppid', 'bt.PartNumberComponent', 'bt.PN')
        ->leftJoin('tb_base_table as bt' , 'bt.mppid', '=', 'ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'bt.PRONumber')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
        ->where('ANP_progres', 4)
        ->where('ANP_qty_finish','>', 'bt.qty')
        ->get();

        // dd($FinishData);

        return view('PBEngine.move-quantity.index',
        ['finishData'=> $finishData]);
    }

    public function getListComponentByPartnumbercomponent($anp_id,$target_id, Request $request){


        
        $source = TbAssign::select('tb_assign_nesting_programmer.*', 'c.customer_name', 'bt.PartNumberComponent', 'bt.PN','bt.qty')
        ->leftJoin('tb_base_table as bt', 'bt.mppid','=','ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro','=','bt.PRONumber')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
        ->where('ANP_id', $anp_id)
        ->first();

        //  //dd($source);

        $mppid = $source->ANP_key_IMA;

        $PNComponent = $source->PartNumberComponent;

        // Mengambil data target
        $targetData = TbBase::whereRaw('PartNumberComponent = ? AND mppid != ?', [$PNComponent, $mppid])->get();
    
        // Inisialisasi variabel
        $key = [];
        $progress = 'Need Assign';
        $finishqty = 0;
        $assignqty = 0;
    
        // Memproses setiap item di targetData
        foreach ($targetData as $keyData) {
            $mppid2 = $keyData->mppid;
            
            // Mengambil data assignment
            $cekassign = TbAssign::select('tb_assign_nesting_programmer.*', 'dm.DM_process_id', 'b.mesin_nama_mesin')
                ->join('tb_mesin AS b', 'b.mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'b.mesin_kode_mesin')
                ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                ->where('ANP_key_IMA', $mppid2)
                ->get();
            
            // Jika tidak ada data assignment, set progress ke 'Need Assign'
            if ($cekassign->isEmpty()) {
                $progress = 'Need Assign';
            } else {
                // Mengambil data quantity
                $getqty = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) AS assignqty, SUM(ANP_qty_finish) AS finishedqty')
                    ->where('ANP_key_IMA', $mppid2)
                    ->groupBy('ANP_key_IMA')
                    ->first();
                
                if ($getqty) {
                    $finishqty = $getqty['finishedqty'];
                    $assignqty = $getqty['assignqty'];
                }
    
                // Memperbarui progress berdasarkan data assignment
                foreach ($cekassign as $cek) {
                    if ($cek['ANP_progres'] == 1 || $cek['ANP_progres'] == 2) {
                        $progress = 'Started';
                        break;
                    } else {
                        if ($cek['ANP_progres'] == 4) {
                            $progress = 'Finished';
                        } elseif ($cek['ANP_progres'] == 3) {
                            $progress = 'Stopped';
                        } elseif ($cek['ANP_progres'] == 0) {
                            $progress = 'Not Started';
                        }
                    }
                }
            }
    
            // Menambahkan keyData saat ini ke result set
            $key[] = $keyData;
        }
    
        // Mengambil data mapping image
        $MImage = TbMappingImageComponent::where('MIC_status_delete', 0)
            ->where('MIC_Status_Aktifasi', 0)
            ->get();    

        //data modal
        // $data=array();
        
            
            $target = TbBase::where('mppid',$target_id)->first();

            $data['anpid_target'] = $target_id;
            $data['anpid_source'] = $anp_id;
            $data['kalkukasi_anp_source'] = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) AS assignqty, SUM(ANP_qty_finish) AS finishedqty')
            ->where('ANP_key_IMA',$source['ANP_key_IMA'])
            ->groupBy('ANP_key_IMA')
            ->first();

            $data['kalkukasi_anp_target'] = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) AS assignqty, SUM(ANP_qty_finish) AS finishedqty')
            ->where('ANP_key_IMA',$target->mppid)
            ->groupBy('ANP_key_IMA')
            ->first();

            if(empty(($data['kalkukasi_anp_target']))){
                $data['kalkukasi_anp_target']['finishqty'] = 0;
                $data['kalkukasi_anp_target']['totalqtyanp'] = 0;
            }

        
        

        return view('PBEngine.move-quantity.target-moving',
        ['source'=>$source,
        'progress'=>$progress,
        'key'=>$key,
        'finishqty'=>$finishqty,
        'assignqty'=>$assignqty,
        'MImage'=>$MImage,
        'anp_id'=>$anp_id,
        'target'=>$target,
        'data'=>$data]);
    }


    public function moveQty($target_id, $anp_id, Request $request){
        // if(isset($_POST) && count($_POST) > 0)     
        // {
        $targetBaseData = TbBase::where('mppid', $target_id)->first();
        $sourceBaseData = TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*', 'm.mesin_nama_mesin')
        ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->join('tb_mesin as m', 'm.mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
        ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
        ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
        ->where('ANP_id', $anp_id)
        ->first();

        //   dd($targetBaseData);
        $mhperunit = $targetBaseData['MHProcess']/60/$targetBaseData['qty'];

        $qtyInput = $request->input('qty');

        $mh = $mhperunit * $qtyInput;

        TbAssign::create([
                'ANP_data_PRO' => $targetBaseData['PRONumber'],
                'ANP_progres' => 4,
                'ANP_key_IMA' => $targetBaseData['mppid'],
                'ANP_qty' => 0,
                'ANP_data_code' => $targetBaseData['mppid'],
                'ANP_data_duedate' => $targetBaseData['PlanStartdate'],
                'ANP_data_mhprosess' => $mh,
                'ANP_created_by' => auth()->user()->id,
                'ANP_created_at' => now()->format('Y-m-d H:i:s'),
                'ANP_modified_by' => auth()->user()->id,
                'ANP_modified_at' => now()->format('Y-m-d H:i:s'),
                'ANP_estimate_startdate' => now()->format('Y-m-d H:i:s'),
                'ANP_estimate_enddate' => now()->format('Y-m-d H:i:s'),
                'ANP_qty_finish' => $request->input('qty')
        ]);

        TbBase::where('id', $targetBaseData['id'])->update([
            'status_assign' => 1
        ]);

        TbHistoryMoving::create([
                'mv_keydata_source' => $sourceBaseData['ANP_key_IMA'],
                'mv_keydata_target' => $targetBaseData['mppid'],
                'mv_qty' => $request->input('qty'),
                'mv_created_at' => now()->format('Y-m-d H:i:s'),
                'mv_created_by' => auth()->user()->id, 
        ]);
  
            $sourcedata = TbBase::where('mppid',$sourceBaseData['ANP_key_IMA'])->first();
            $qtysourcenew = intval($sourceBaseData['ANP_qty_finish']) - intval($request->input('qty'));
            if($sourcedata['qty'] == $qtysourcenew || $sourcedata['qty'] < $qtysourcenew ){
                $anpprogres = 4;
            }
            else{
                $anpprogres = 3;
            }

            TbAssign::where('ANP_id', $sourceBaseData['ANP_id'])->update([
                'ANP_qty_finish' => $qtysourcenew,
                'ANP_progres' => $anpprogres,
            ]);
              return redirect('move-quantity/all-available-move');
        }
             
        public function showModalMoveQty($target_id, $anp_id){

            $data['source'] = TbAssign::select('tb_assign_nesting_programmer.*', 'c.customer_name', 'bt.PartNumberComponent', 'bt.PN','bt.qty')
            ->leftJoin('tb_base_table as bt', 'bt.mppid','=','ANP_key_IMA')
            ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro','=','bt.PRONumber')
            ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
            ->where('ANP_id', $anp_id)
            ->first();

            dd($data['source']);
            
            $data['target'] = TbBase::where('mppid',$target_id);
            $data['anpid_target'] = $target_id;
            $data['anpid_source'] = $anp_id;
            $data['kalkukasi_anp_source'] = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) AS assignqty, SUM(ANP_qty_finish) AS finishedqty')
            ->where('ANP_key_IMA',$data['source']['ANP_key_IMA'])
            ->groupBy('ANP_key_IMA')
            ->first();

            $data['kalkukasi_anp_target'] = TbAssign::selectRaw('SUM(IF(ANP_note IS NULL, ANP_qty, ANP_qty_finish)) AS assignqty, SUM(ANP_qty_finish) AS finishedqty')
            ->where('ANP_key_IMA',$data['target']['mppid'])
            ->groupBy('ANP_key_IMA')
            ->first();

            if(empty(($data['kalkukasi_anp_target']))){
                $data['kalkukasi_anp_target']['finishqty'] = 0;
                $data['kalkukasi_anp_target']['totalqtyanp'] = 0;
            }
            
            return view('PBEngine/move-quantity/modal-move-quantity',$data);
        }

           

    // }


}