<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbActivityJob;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBundlingJob;
use App\Models\Table\PBEngine\TbDetailReasonPause;
use App\Models\Table\PBEngine\TbJob;
use App\Models\Table\PBEngine\TbMesin;
use App\Models\Table\PBEngine\TbOperatorWorking;
use App\Models\Table\PBEngine\TbReasonPause;
use App\Models\Table\PBEngine\TbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BundlingJobController extends Controller
{
    public function index(Request $request){

        $userId = Auth::user()->id;
        $machines = TbMesin::join('pbengine6.tb_detail_mesin as dm', 'mesin_kode_mesin', '=', 'dm.DM_mesin_kode_mesin')
                ->join('pbengine6.tb_detail_user_process_group as dp', 'dm.DM_process_id', '=', 'dp.DOPG_Process_id')
                ->join('satria3.users as u', 'dp.DOPG_user_id', '=', 'u.id')
                ->select('mesin_kode_mesin as  kode_mesin', 'mesin_nama_mesin')
                ->where('mesin_status', 0)
                ->where('mesin_delete_status', 0)
                ->where('u.id', $userId)

                ->get();
        //get bj_bundling_key
        // $bj_bundling_key = TbBundlingJob::select('bj_bundling_key')
        // ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
        //  ->where('a.ANP_progres', '=', '1')
        // ->distinct()
        // ->get();

        //dd($bj_bundling_key);
      
        //get data detail

        $datadetail = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'ANP_qty', 'ANP_progres', 'ANP_id')
        ->leftJoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_id')
        ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->whereNotNull('bj.bj_bundling_key')
        ->distinct('bj.bj_bundling_key');
        // ->where('ANP_progres', '=', '1');
        // ->where('bj.bj_bundling_key', $);

        

            if($request->all == 1 || $request->all()==null){
                $datadetail->where('ANP_progres','=', '1');
                //$bj_bundling_key->where('a.ANP_progres', '=', '1');
            } else
             // Cek apakah input Status Pekerjaan ada
            if (isset($request->ANPStatus)) {
                $statusPekerjaan = $request->ANPStatus;
                $datadetail->where('ANP_progres', $statusPekerjaan);
                //$bj_bundling_key->where('ANP_progres', $statusPekerjaan);
            }
            // if($request->all == 1 || $request->all()==null){
            //     $datadetail->where('ANP_progres','=', '1');
            //     $bj_bundling_key->where('a.ANP_progres','=', '1');
            // } else 
            if (isset($request->ANPKodeMesin)) {
                $datadetail->where('ANP_mesin_kode_mesin', $request->ANPKodeMesin);
            }
            
        $bj_bundling_key = $datadetail->distinct()->get('bj_bundling_key');
        $results = $datadetail->orderBy('bj.bj_bundling_key')->get();
        // $key = $bj_bundling_key->get();
        $data['results'] = $results;

        $groupedResults = [];
        foreach ($results as $result) {
            $groupedResults[$result->bj_bundling_key][] = $result;
        }
       
        

        // if ($results->isEmpty()) {
        //     $results = collect(); // Make sure $results is an empty collection
        //     $anpid = null;
        // } else {
            $anpid = $results->pluck('ANP_id');
            //dd($anpid);
        // }
      
        //get data operator
        $OP=TbUser::select('tb_user.*', 'bj.bj_bundling_key')
        ->leftJoin('tb_operator_working as op', 'tb_user.user_id', '=', 'op.ow_user_id')
        ->leftJoin('tb_job as j', 'j.Job_id', '=', 'op.ow_job_id')
        ->join('tb_assign_nesting_programmer as anp', 'anp.ANP_id','=', 'j.Job_ANP_id')
        ->join('tb_bundling_job as bj', 'anp.ANP_id', '=', 'bj.bj_anp_id')
        // ->where('anp.ANP_progres', '=', '1')
        
        
        ->whereIn('j.Job_ANP_id', $anpid)
        ->distinct()
        ->get();

        $rp=TbReasonPause::select('tb_reason_pause.*')
        ->where('RP_status', 0)
        ->get();

        $data= array();
        $data['OP'] = $OP;

        

    
        return view('PBEngine.bundling-job.index',
        ['bj_bundling_key' => $bj_bundling_key,
        // 'results' => $results,
        'groupedResults' => $groupedResults,
        'OP'=>$OP,
        'data'=>$data,
        'rp'=>$rp,
        'machines'=>$machines,
        'selectedMachine'=>$request->ANPKodeMesin
        
        // 'dataBundling'=>$dataBundling 
    
        ]);
        
    }


    function bundlingStart(Request $request, $keybundling=null){
        
       
            $dataANP = $request->input('checkbox',[]);
            $dataOP = $request->input('OP',[]);
            


            //dd($dataANP);
            if (!empty($keybundling)) {
                $bundling_key = $keybundling;
            } else {
                $bundling_key = $request->input('kode_bundling');
            }

            // dd($bundling_key);

            
            if (!empty($dataANP) && $dataANP[0] != "") {
            foreach($dataANP as $anpid){
                $datacheck=TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                // ->join('tb_mesin as m', 'm.mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                ->where('ANP_id', $anpid)
                ->get();

                 //dd($datacheck);

                
                //dd($anppro);



                foreach ($datacheck as $data) {

                    $anppro = $data->ANP_progres;
                    if(empty($anppro)){
                       $anppro = 0;
                   }
                //    dd($anppro);
                    if($anppro != '4'){

                    $jobdata=TbJob::select('a.Job_id' , 'a.Job_category')
                    ->from('tb_job AS a')
                    ->where('a.Job_ANP_id',  $anpid)
                    ->whereNull('job_redo_id')
                    ->orderBy('a.modified_at','desc')
                    ->first();

                    $newJob= TbJob::create([
                        'Job_ANP_id' => $anpid,
                        'job_bundling_code' => $bundling_key,
                        'Job_category'  => $request->Job_category,
                        'Job_adding' => $request->Job_adding,
                        'Job_notes' => $request->Job_notes,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                        ]);



                    $jobId = optional($jobdata)->Job_id;
                    // dd($jobId);

                    if(empty($jobId)){
                    $jobId = 0;
                    }

                    $lastAJpause[0] = TbActivityJob::select ('tb_activity_job.*' , 'j.RP_name' , 'op.user_nama')
                    ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                    ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                    ->leftJoin('tb_user as op','op.user_employe_number', '=', 'tb_activity_job.modified_by')
                    ->orderBy('created_at', 'desc')
                    ->where('tb_activity_job.aj_job_id', $jobId)
                    ->first();

                    $ajId = optional($lastAJpause[0])->aj_id;

                    if(empty($ajId)){
                        $ajId = 0;
                    }

                    // if(empty($keybundling)){
                        TbBundlingJob::create([
                            'bj_anp_id' => $anpid, 
                            'bj_bundling_key' => $bundling_key,
                            'bj_job_status' => 0 , 
                            'created_by' => auth()->user()->id,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'modified_by' => auth()->user()->id,
                            'modified_at' => now()->format('Y-m-d H:i:s')
                        ]);
                    // }

                    $newJobId= $newJob->Job_id;

        
                    TbActivityJob::create([
                        'aj_job_id' =>$newJobId,
                        // 'aj_activity_before_id' =>$ajId,
                        'aj_activity' => 0,
                        'aj_qty' => 0,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                    ]);

                    // Handle additional operator partners
                    $OPpartner = $request->input('operators', []);

                    $user_id = auth()->user()->id;

                    TbUser::where('user_id', $user_id)->update(['user_IsAssign' => 1]);
            
                    TbOperatorWorking::create([
                            'ow_user_id' => auth()->user()->id,
                            'ow_job_id' => $newJobId,
                            'created_by' => auth()->user()->id,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'modified_by' => auth()->user()->id,
                            'modified_at' => now()->format('Y-m-d H:i:s')
            
                    ]);

                    if (!empty($OPpartner) && $OPpartner[0] != "") {
                        foreach ($OPpartner as $dataOP) {
                        if($dataOP != $user_id){
                        TbOperatorWorking::create([
                            'ow_user_id' => $dataOP,
                            'ow_job_id' => $newJobId,
                            'created_by' => $user_id,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'modified_by' => $user_id,
                            'modified_at' => now()->format('Y-m-d H:i:s')
                        ]);
            
                        TbUser::where('user_id', $dataOP)->update(['user_IsAssign' => 1]);
                        }
                    }
                    }
            
                    TbAssign::where('ANP_id', $anpid)->update([
                        'ANP_progres' => '1',
                        'ANP_modified_by' => auth()->user()->id,
                        'ANP_modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
                    

                    

                }
            }
            }

                    
        }
        return response()->json(['status' => 'success', 'message' => 'Job berhasil dimulai.', 'redirect_url' => url('start-stop-job-bundling/')]);

            // return redirect('start-stop-job-bundling/');
                
    
    }


    //Page Bundling pas di form
    function pageBundlingStart (Request $request, $keybundling=null){

        $dataANP = array();

        if($keybundling != null){
            $tempanp = TbBundlingJob::where('bj_bundling_key',$keybundling)->get();

            
            foreach($tempanp as $k){
                //$dataANP = $k->bj_anp_id;
                array_push($dataANP , $k['bj_anp_id']);
            }
            $data['key'] = $keybundling;
            }else{

                $dataANP = $request->checkbox;
                $data['key'] = 0;
            }

            // dd($dataANP);

            if (!empty($dataANP)) {
                $datachoosen=TbAssign::select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'tb_assign_nesting_programmer.ANP_qty', 'tb_assign_nesting_programmer.ANP_urgency', 'tb_assign_nesting_programmer.ANP_id', 'c.customer_name as customer')
                ->join('tb_base_table as bt', 'bt.mppid', '=', 'tb_assign_nesting_programmer.ANP_key_IMA')
                ->leftJoin('tb_mapping_pro_customer as b', 'b.mapping_pro', '=', 'tb_assign_nesting_programmer.ANP_data_PRO')
                ->leftJoin('tb_mapping_pro_customer as ba', 'ba.mapping_pn', '=', 'bt.PN')
                ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'b.mapping_customer_id')
                ->whereIn('tb_assign_nesting_programmer.ANP_id', $dataANP)
                ->distinct()
                ->get();
            

                //dd($datachoosen);

                $operators = TbUser::where('user_role','=','1')
                ->get();

                $data['operators']= $operators;
                $data['datachoosen']=$datachoosen;
            
            

                //dd($data);
                // $existingCodes = TbBundlingJob::pluck('bj_bundling_key');

                return view('PBEngine.bundling-job.bundling-start',
            [
            'datachoosen'=>$datachoosen,
            // 'keybundling'=>$keybundling,
            'data'=>$data,
            // 'existingCodes'=>$existingCodes
            
            // 'dataBundling'=>$dataBundling 
        
            ]);
        }

    }


    //Stop Job Bundling Diluar
    function bundlingStop(Request $request, $bundling_key){
        
            $qty = $request->input('isi');
            $anp = $request->input('checkbox', []);
            
            $indexqty = 0;

            $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            ->distinct()
            ->get();

            //anp bundling
            foreach($databundling as $db){
            $bjanp = $db->bj_anp_id;
                    if(empty($bjanp)){
                       $bjanp = 0;
                   }
                }
            

            if (!empty($anp) && $anp[0] != "") {
            foreach($anp as $anpid){
                $datacheck =TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                // ->join('tb_mesin as m', 'm.mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                ->where('ANP_id', $anpid)
                ->get();

                foreach ($datacheck as $data) {

                    $anppro = $data->ANP_progres;
                    if(empty($anppro)){
                       $anppro = 0;
                   }
                   if($anppro != '4'){
                    
                    $jobdata = TbJob::select('a.Job_id' , 'a.Job_category')
                    ->from('tb_job AS a')
                    ->where('a.Job_ANP_id',  $anpid)
                    ->whereNull('job_redo_id')
                    ->orderBy('a.modified_at','desc')
                    ->first();
                   
                   $jobId = optional($jobdata)->Job_id;
                    // dd($jobId);

                    if(empty($jobId)){
                    $jobId = 0;
                    }

                    $lastAJpause[0] = TbActivityJob::select ('tb_activity_job.*' , 'j.RP_name' , 'op.user_nama')
                    ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                    ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                    ->leftJoin('tb_user as op','op.user_employe_number', '=', 'tb_activity_job.modified_by')
                    ->orderBy('created_at', 'desc')
                    ->where('tb_activity_job.aj_job_id', $jobId)
                    ->first();
                    
                    
                    $qi = $qty[$indexqty];

                    // dd($qi);

                    $ajId = optional($lastAJpause[0])->aj_id;

                    if(empty($ajId)){
                        $ajId = 0;
                    }
                    
                    TbActivityJob::create([
                        'aj_job_id' =>$jobId,
                        'aj_activity_before_id' =>$ajId,
                        'aj_activity' => 2,
                        'aj_qty' => $qi,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                    ]);

                    
                    $assign = TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                    ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                    ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                    ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                    ->where('ANP_id', $anpid)
                    ->first();
                     
                    $anpprogres = 0;


                    $qf = TbActivityJob::join('tb_job as j', 'j.Job_id', '=', 'aj_job_id')
                    ->where('j.Job_ANP_id', $bjanp)
                    ->where('aj_activity', '=', '2')
                    ->where('j.Job_category', '=', '1')
                    ->whereNull('j.Job_redo_id')
                    ->sum('aj_qty');    
                
                    
                    // $finishqty = optional($qf)->aj_qty;
                

                    if($jobdata['Job_category'] == 1){
                        if(empty($qf['aj_qty'])){
                            $qf = 0;
                        }else{
                            $qf = $qf['aj_qty'];
                        }

                        $anpfinish = $assign -> ANP_qty_finish;
                        if(empty($anpfinish)){
                            $anpfinish = 0;
                        }

                        $last_qty = intval($anpfinish) + intval($qi);
                        // dd($last_qty);
                        if($assign['ANP_qty'] == $last_qty || $assign['ANP_qty'] < $last_qty ){
                            $anpprogres = 4;
                        }
                        else{
                            $anpprogres = 3;//var_dump($last_qty);die();
                        }
                    }else{
                        $last_qty = 0;
                        $anpprogres = 3;
                        
                    }

                    // dd($anpprogres);

                    TbJob::where('Job_id', $jobId)->update([
                        'Job_finish_remark' => 'Normal Stop',
                        'modified_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id
                    ]);

                   

                     //dd($testupdate);

                    TbAssign::where('ANP_id', $anpid)->update([
                        'ANP_qty_finish' => $last_qty,
                        'ANP_progres' => $anpprogres,
                        'ANP_modified_by' => auth()->user()->id,
                        'ANP_modified_at' => now()->format('Y-m-d H:i:s')
                    ]);

                    // $testupdate = TbAssign::where('ANP_id', $anpid)->get();
                    // dd($testupdate);

                            
                    $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
                    ->leftJoin('tb_user AS o','o.user_id', '=', 'tb_operator_working.ow_user_id')
                    ->where('tb_operator_working.ow_job_id', $jobId)
                    ->get();

                    foreach ($OPpartner as $op) {
                         TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 0]);
                    }
                
                
                }
            }
                $indexqty++;
            }
        }
          
        return response()->json(['status' => 'success', 'message' => 'Job berhasil berhenti.', 'redirect_url' => url('start-stop-job-bundling/')]);

        // return redirect('start-stop-job-bundling/');

     
    }

    function pageBundlingStop(Request $request, $bundling_key){

        // $datachooses = array();
        // $dataOPTemp = array();
        $data =[];
        $anpid= [];
        //$databundling=array();
        
            $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            // ->distinct()
            ->get();

            //dd($databundling);

            // dd($databundling);
            
            

            // Mengumpulkan semua ANP_id
                foreach($databundling as $db) {
                    $anpid[] = $db->bj_anp_id;
                }
                
                //$anpid[] = $db->bj_anp_id;
                // Periksa apakah $anpid adalah array, jika tidak, jadikan array
                // if (!is_array($anpid)) {
                //     $anpid = [$anpid];
                // }
                //     if(empty($anpid)){
                //        $anpid = 0;
                //    }
                // dd($anpid);

                $dataOP = TbUser::select('tb_user.*', 'bj.bj_bundling_key')
                ->leftJoin('tb_operator_working as op', 'user_id', '=', 'op.ow_user_id')
                ->leftJoin('tb_job as job', 'job.Job_id', '=', 'op.ow_job_id')
                ->join('tb_assign_nesting_programmer as anp', 'anp.ANP_id', '=', 'job.Job_ANP_id')
                ->join('tb_bundling_job as bj', 'anp.ANP_id', '=', 'bj.bj_anp_id')
                ->whereIn('job.Job_ANP_id', $anpid)
                ->groupBy('tb_user.user_id')
                // ->distinct()
                ->get();

                // dd($dataOP)

                $data['dataOP'] = $dataOP;
                
                // foreach($dataOP as $o){
                //     $dataaddOP = array(
                //         'user_nama' => $o['user_nama'],
                //         'bj_bundling_key' => $o['bj_bundling_key'],
                //     );
                //     array_push($dataOPTemp, $dataaddOP);
                //     // $dataOPTemp= 
                // }

                

                $qf[] = TbActivityJob::join('tb_job as j', 'j.Job_id', '=', 'aj_job_id')
                ->whereIn('j.Job_ANP_id', $anpid)
                ->where('aj_activity', '=', '2')
                ->where('j.Job_category', '=', '1')
                ->whereNull('j.Job_redo_id')
                ->sum('aj_qty');

                //  dd($qf);

                
                $finishqty = optional($qf)->aj_qty;
                if($finishqty == null){
                    $finishqty = 0;
                }
                $data['finishqty'] = $finishqty;
                // dd($data['finishqty']);

                $datachoosen=TbAssign::select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'tb_assign_nesting_programmer.ANP_qty', 'tb_assign_nesting_programmer.ANP_urgency', 'tb_assign_nesting_programmer.ANP_id', 'c.customer_name as customer')
                ->join('tb_base_table as bt', 'bt.mppid', '=', 'tb_assign_nesting_programmer.ANP_key_IMA')
                ->leftJoin('tb_mapping_pro_customer as b', 'b.mapping_pro', '=', 'tb_assign_nesting_programmer.ANP_data_PRO')
                ->leftJoin('tb_mapping_pro_customer as ba', 'ba.mapping_pn', '=', 'bt.PN')
                ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'b.mapping_customer_id')
                ->whereIn('tb_assign_nesting_programmer.ANP_id', $anpid)
                ->distinct()
                ->get(); 
                
                //dd($datachoosen);

               
                
               
                $data['datachoosen'] = $datachoosen;
                // dd($data['datachoosen']);
                $sisaArray = [];
                foreach($datachoosen as $result) {
                    // $sisa = $result->sum('ANP_qty') - $finishqty;
                    $sisa = $result->ANP_qty- $finishqty;
                    $sisaArray[] = $sisa;
                }
                $data['sisaArray'] = $sisaArray;
                //dd($sisa);

                
                

                
                // $tempchoose = array(
                //     'ANP_urgency' => $datachoose[0]['ANP_urgency'],
                //     'PN' => $datachoose[0]['PN'],
                //     'PRONumber' => $datachoose[0]['PRONumber'],
                //     'PartNumberComponent' => $datachoose[0]['PartNumberComponent'],
                //     'ANP_qty' => $datachoose[0]['ANP_qty'],
                //     'customer' => $datachoose[0]['customer'],
                //     'finishqty' => $qf['aj_qty'],
                //     'ANP_id' =>$datachoose[0]['ANP_id'],
                //     'sisa' => $datachoose[0]['ANP_qty'] - $qf['aj_qty'],
                // );
                // array_push($datachooses, $tempchoose);
            // }
            $data['bundling_key'] = $bundling_key;
            // $data['dataOP'] = $dataOP;
            // $data['datachoosen'] =  $datachoosen;
            // $data['si']
            return view('PBEngine.bundling-job.bundling-stop',
            [
                'datachoosen'=>$datachoosen,
                'data'=>$data

    
            ]);
    }

    //Pause Job diluar
    function bundlingPause(Request $request, $bundling_key) {
        // Log::info('bundling_key: ' . $bundling_key);
        $bjanp = [];
        $bjjobs = [];
    
        $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            ->distinct()
            ->get();

            // dd($databundling);
    
        // Collect all bj_anp_id and bj_job_id
        foreach($databundling as $bundling) {
            // $bjanp[$bundling->bj_anp_id] = $bundling->bj_job_id;
            $bjanp[] = $bundling->bj_anp_id;
            $bjjobs[] = $bundling->bj_job_id;
        }
    
        // Process each bj_anp_id
        foreach($bjanp as $index => $bjanpid) {   
            $datacheck = TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                ->where('ANP_id', $bjanpid)
                ->get();
    
            foreach ($datacheck as $data) {
                $anppro = $data->ANP_progres;
                if(empty($anppro)) {
                    $anppro = 0;
                }
    
                if($anppro != '4') {
                    $bjjob = $bjjobs[$index]; 
                    $lastAJpause = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                        ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                        ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                        ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                        ->orderBy('created_at', 'desc')
                        ->where('tb_activity_job.aj_job_id', $bjjob)
                        ->first();
    
                    $ajId = optional($lastAJpause)->aj_id;
    
                    if(empty($ajId)) {
                        $ajId = 0;
                    }
                    
                    TbActivityJob::create([
                        'aj_job_id' => $bjjob,
                        'aj_activity_before_id' => $ajId,
                        'aj_activity' => 1,
                        'aj_qty' => 0,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
    
                    $lastAJ = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                        ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                        ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                        ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                        ->orderBy('created_at', 'desc')
                        ->where('tb_activity_job.aj_job_id', $bjjob)
                        ->first();
    
                    $lastAJid = optional($lastAJ)->aj_id;
                    $RP = $request->input('rp');
    
                    TbDetailReasonPause::create([
                        'DRP_rp_id' => $RP,
                        'DRP_aj_id' => $lastAJid,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
    
                    TbAssign::where('ANP_id', $bjanpid)->update([
                        'ANP_progres' => '2',
                        'ANP_modified_by' => auth()->user()->id,
                        'ANP_modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
    
                    $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
                        ->leftJoin('tb_user AS o', 'o.user_id', '=', 'tb_operator_working.ow_user_id')
                        ->where('tb_operator_working.ow_job_id', $bjjob)
                        ->get();
    
                    foreach ($OPpartner as $op) {
                        TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 2]);
                    }
                }
            }
        }
    
        return response()->json(['status' => 'success', 'message' => 'Data job berhasil dipause.', 'redirect_url' => url('start-stop-job-bundling/')]);

        // return redirect('start-stop-job-bundling/');
    }

    //Pause job di actual progress
    function bundling_Pause(Request $request, $bundling_key) {
        // Log::info('bundling_key: ' . $bundling_key);
        $bjanp = [];
        $bjjobs = [];
    
        $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            ->distinct()
            ->get();

            // dd($databundling);
    
        // Collect all bj_anp_id and bj_job_id
        foreach($databundling as $bundling) {
            // $bjanp[$bundling->bj_anp_id] = $bundling->bj_job_id;
            $bjanp[] = $bundling->bj_anp_id;
            $bjjobs[] = $bundling->bj_job_id;
        }
    
        // Process each bj_anp_id
        foreach($bjanp as $index => $bjanpid) {   
            $datacheck = TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                ->where('ANP_id', $bjanpid)
                ->get();
    
            foreach ($datacheck as $data) {
                $anppro = $data->ANP_progres;
                if(empty($anppro)) {
                    $anppro = 0;
                }
    
                if($anppro != '4') {
                    $bjjob = $bjjobs[$index]; 
                    $lastAJpause = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                        ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                        ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                        ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                        ->orderBy('created_at', 'desc')
                        ->where('tb_activity_job.aj_job_id', $bjjob)
                        ->first();
    
                    $ajId = optional($lastAJpause)->aj_id;
    
                    if(empty($ajId)) {
                        $ajId = 0;
                    }
                    
                    TbActivityJob::create([
                        'aj_job_id' => $bjjob,
                        'aj_activity_before_id' => $ajId,
                        'aj_activity' => 1,
                        'aj_qty' => 0,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
    
                    $lastAJ = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                        ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                        ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                        ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                        ->orderBy('created_at', 'desc')
                        ->where('tb_activity_job.aj_job_id', $bjjob)
                        ->first();
    
                    $lastAJid = optional($lastAJ)->aj_id;
                    $RP = $request->input('rp');
    
                    TbDetailReasonPause::create([
                        'DRP_rp_id' => $RP,
                        'DRP_aj_id' => $lastAJid,
                        'created_by' => auth()->user()->id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'modified_by' => auth()->user()->id,
                        'modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
    
                    TbAssign::where('ANP_id', $bjanpid)->update([
                        'ANP_progres' => '2',
                        'ANP_modified_by' => auth()->user()->id,
                        'ANP_modified_at' => now()->format('Y-m-d H:i:s')
                    ]);
    
                    $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
                        ->leftJoin('tb_user AS o', 'o.user_id', '=', 'tb_operator_working.ow_user_id')
                        ->where('tb_operator_working.ow_job_id', $bjjob)
                        ->get();
    
                    foreach ($OPpartner as $op) {
                        TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 2]);
                    }
                }
            }
        }
    
        return response()->json(['status' => 'success', 'message' => 'Data job berhasil dipause.', 'redirect_url' => url('start-stop-job-bundling/bundling-actual-progress/'.$bundling_key)]);

        // return response()->json(['status' => 'success', 'message' => 'Data job berhasil dipause.', 'redirect_url' => url('start-stop-job-bundling/bundlingActualProgress')]);

        // return redirect('start-stop-job-bundling/');
    }

    //After Pause Bundling Diluar
    function bundlingStartAfterPause(Request $request, $bundling_key){
        $bjanp = [];
        $bjjobs = [];
    

        $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            ->distinct()
            ->get();

            // dd($databundling);

            foreach($databundling as $bundling) {
                $bjanp[] = $bundling->bj_anp_id;
                $bjjobs[] = $bundling->bj_job_id;
            
            }

            // dd($bjanp);

        
            // Process each bj_anp_id
            foreach($bjanp as $index => $bjanpid) {   
                $datacheck = TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                    ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                    ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                    ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                    ->where('ANP_id', $bjanpid)
                    ->get();

                    // dd($bjanpid);
        
                foreach ($datacheck as $data) {
                    $anppro = $data->ANP_progres;
                    if(empty($anppro)) {
                        $anppro = 0;
                    }
        
                    if($anppro != '4') {
                        $bjjob = $bjjobs[$index]; 
                        $lastAJpause = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                            ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                            ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                            ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                            ->orderBy('created_at', 'desc')
                            ->where('tb_activity_job.aj_job_id', $bjjob)
                            ->first();
        
                        $ajId = optional($lastAJpause)->aj_id;
        
                        if(empty($ajId)) {
                            $ajId = 0;
                        }
                        
                        TbActivityJob::create([
                            'aj_job_id' => $bjjob,
                            'aj_activity_before_id' => $ajId,
                            'aj_activity' => 0,
                            'aj_qty' => 0,
                            'created_by' => auth()->user()->id,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'modified_by' => auth()->user()->id,
                            'modified_at' => now()->format('Y-m-d H:i:s')
                        ]);


                        TbAssign::where('ANP_id', $bjanpid)->update([
                            'ANP_progres' => '1',
                            'ANP_modified_by' => auth()->user()->id,
                            'ANP_modified_at' => now()->format('Y-m-d H:i:s')
                        ]);

                        $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
                        ->leftJoin('tb_user AS o', 'o.user_id', '=', 'tb_operator_working.ow_user_id')
                        ->where('tb_operator_working.ow_job_id', $bjjob)
                        ->get();
    
                        foreach ($OPpartner as $op) {
                            TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 1]);
                        }    


                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Job berhasil dimulai kembali.', 'redirect_url' => url('start-stop-job-bundling/')]);


        // return redirect('start-stop-job-bundling/');
    }

    //After Pause di actual progress
    function bundling_StartAfterPause(Request $request, $bundling_key){
        $bjanp = [];
        $bjjobs = [];
    

        $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            ->distinct()
            ->get();

            // dd($databundling);

            foreach($databundling as $bundling) {
                $bjanp[] = $bundling->bj_anp_id;
                $bjjobs[] = $bundling->bj_job_id;
            
            }

            // dd($bjanp);

        
            // Process each bj_anp_id
            foreach($bjanp as $index => $bjanpid) {   
                $datacheck = TbAssign::select('tb_assign_nesting_programmer.*', 'bt.*', 'p.*')
                    ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                    ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
                    ->join('tb_process as p', 'p.proses_id', '=', 'dm.DM_process_id')
                    ->where('ANP_id', $bjanpid)
                    ->get();

                    // dd($bjanpid);
        
                foreach ($datacheck as $data) {
                    $anppro = $data->ANP_progres;
                    if(empty($anppro)) {
                        $anppro = 0;
                    }
        
                    if($anppro != '4') {
                        $bjjob = $bjjobs[$index]; 
                        $lastAJpause = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                            ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                            ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                            ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                            ->orderBy('created_at', 'desc')
                            ->where('tb_activity_job.aj_job_id', $bjjob)
                            ->first();
        
                        $ajId = optional($lastAJpause)->aj_id;
        
                        if(empty($ajId)) {
                            $ajId = 0;
                        }
                        
                        TbActivityJob::create([
                            'aj_job_id' => $bjjob,
                            'aj_activity_before_id' => $ajId,
                            'aj_activity' => 0,
                            'aj_qty' => 0,
                            'created_by' => auth()->user()->id,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'modified_by' => auth()->user()->id,
                            'modified_at' => now()->format('Y-m-d H:i:s')
                        ]);


                        TbAssign::where('ANP_id', $bjanpid)->update([
                            'ANP_progres' => '1',
                            'ANP_modified_by' => auth()->user()->id,
                            'ANP_modified_at' => now()->format('Y-m-d H:i:s')
                        ]);

                        $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
                        ->leftJoin('tb_user AS o', 'o.user_id', '=', 'tb_operator_working.ow_user_id')
                        ->where('tb_operator_working.ow_job_id', $bjjob)
                        ->get();
    
                        foreach ($OPpartner as $op) {
                            TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 1]);
                        }    


                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Data job berhasil dimulai kembali.', 'redirect_url' => url('start-stop-job-bundling/bundling-actual-progress/'.$bundling_key)]);

            // return response()->json(['status' => 'success', 'message' => 'Job berhasil dimulai kembali.', 'redirect_url' => url('start-stop-job-bundling/')]);


        // return redirect('start-stop-job-bundling/');
    }

    //Call Actual Progress
    function bundlingActualProgress(Request $request, $bundling_key){

        $data =[];
        $anpid= [];
        //$databundling=array();
        
            $databundling = TbBundlingJob::select('bj_anp_id', 'j.Job_id as bj_job_id', 'a.ANP_progres')
            ->join('tb_assign_nesting_programmer AS a', 'bj_anp_id', '=', 'a.ANP_id')
            ->join('tb_job as j', function($join) {
                $join->on('j.Job_ANP_id', '=', 'a.ANP_id')
                     ->on('j.job_bundling_code', '=', 'bj_bundling_key');
            })
            ->where('bj_bundling_key', $bundling_key)
            // ->distinct()
            ->get();

            //dd($databundling);

            // dd($databundling);
            
            

            // Mengumpulkan semua ANP_id
                foreach($databundling as $db) {
                    $anpid[] = $db->bj_anp_id;
                }
                
                //$anpid[] = $db->bj_anp_id;
                // Periksa apakah $anpid adalah array, jika tidak, jadikan array
                // if (!is_array($anpid)) {
                //     $anpid = [$anpid];
                // }
                //     if(empty($anpid)){
                //        $anpid = 0;
                //    }
                // dd($anpid);

                $dataOP = TbUser::select('tb_user.*', 'bj.bj_bundling_key')
                ->leftJoin('tb_operator_working as op', 'user_id', '=', 'op.ow_user_id')
                ->leftJoin('tb_job as job', 'job.Job_id', '=', 'op.ow_job_id')
                ->join('tb_assign_nesting_programmer as anp', 'anp.ANP_id', '=', 'job.Job_ANP_id')
                ->join('tb_bundling_job as bj', 'anp.ANP_id', '=', 'bj.bj_anp_id')
                ->whereIn('job.Job_ANP_id', $anpid)
                ->groupBy('tb_user.user_id')
                // ->distinct()
                ->get();

            //  dd($dataOP);

                $data['dataOP'] = $dataOP;
                // dd($data['dataOP']);
                
                // foreach($dataOP as $o){
                //     $dataaddOP = array(
                //         'user_nama' => $o['user_nama'],
                //         'bj_bundling_key' => $o['bj_bundling_key'],
                //     );
                //     array_push($dataOPTemp, $dataaddOP);
                //     // $dataOPTemp= 
                // }

                

                $qf[] = TbActivityJob::join('tb_job as j', 'j.Job_id', '=', 'aj_job_id')
                ->whereIn('j.Job_ANP_id', $anpid)
                ->where('aj_activity', '=', '2')
                ->where('j.Job_category', '=', '1')
                ->whereNull('j.Job_redo_id')
                ->sum('aj_qty');

                //  dd($qf);

                
                $finishqty = optional($qf)->aj_qty;
                if($finishqty == null){
                    $finishqty = 0;
                }
                $data['finishqty'] = $finishqty;
                // dd($data['finishqty']);

                $datachoosen=TbAssign::select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'ANP_qty', 'ANP_urgency', 'ANP_id','ANP_progres', 'c.customer_name as customer')
                ->join('tb_base_table as bt', 'bt.mppid', '=', 'tb_assign_nesting_programmer.ANP_key_IMA')
                ->leftJoin('tb_mapping_pro_customer as b', 'b.mapping_pro', '=', 'tb_assign_nesting_programmer.ANP_data_PRO')
                ->leftJoin('tb_mapping_pro_customer as ba', 'ba.mapping_pn', '=', 'bt.PN')
                ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'b.mapping_customer_id')
                ->whereIn('tb_assign_nesting_programmer.ANP_id', $anpid)
                ->distinct()
                ->get(); 
                
                // dd($datachoosen);

               
                
               
                $data['datachoosen'] = $datachoosen;
                // dd($data['datachoosen']);
                $sisaArray = [];
                foreach($datachoosen as $result) {
                    // $sisa = $result->sum('ANP_qty') - $finishqty;
                    $sisa = $result->ANP_qty- $finishqty;
                    $sisaArray[] = $sisa;
                }
                $data['sisaArray'] = $sisaArray;
                //dd($sisa);

        $data['bundling_key'] = $bundling_key;
        // dd($data['bundling_key']);
        // dd($bundling_key);
        

        

        // $operators = TbUser::where('user_role','=','1')
        // ->get();

        $rp=TbReasonPause::select('tb_reason_pause.*')
        ->where('RP_status', 0)
        ->get();
        
    

        // $data['operators']= $operators;
        

        return view('PBEngine.bundling-job.bundling-actual-progres', 
        ['datachoosen'=>$datachoosen,
        'data'=>$data,
        'bundling_key'=>$bundling_key,
        'dataOP'=>$dataOP,
        'rp'=>$rp]);
    }

    public function checkKodeBundling($bundling_key)
    {
        $exists = TbBundlingJob::where('bj_bundling_key', $bundling_key)->exists();
        // dd($exists);
        return response()->json(['exists' => $exists]);
    }

}