<?php


namespace App\Http\Controllers\Cms\PBEngine;

use App\Models\Table\PBEngine\TbAssign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbActivityJob;
use App\Models\Table\PBEngine\TbDetailReasonPause;
use App\Models\Table\PBEngine\TbJob;
use App\Models\Table\PBEngine\TbOperatorWorking;
use App\Models\Table\PBEngine\TbReasonPause;
use App\Models\Table\PBEngine\TbUser;

class ActivityJobController extends Controller
{
    public function actualProgress($anp_id, Request $request){
        //$mesin_nama_mesin = $request->input('mesin_nama_mesin');
   

        $query = TbAssign::select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent','bt.MaterialName','bt.qty', 'ANP_qty','ANP_qty_finish', 'ANP_progres', 'ANP_id', 'c.customer_name')
        ->leftjoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'ANP_data_PRO')
        ->leftJoin('tb_customer AS c', 'c.customer_id' ,'=', 'mpc.mapping_customer_id')
        ->where('ANP_id', $anp_id);
        $item = $query->get()->first();

        $rnq = ($item->ANP_qty) - ($item->ANP_qty_finish);
        // $rtq = ($item->qty) - ($item->ANP_qty_finish);
        //dd($RNQ);



        

        $job= TbJob::select('Job_id' , 'Job_category')
       
            ->where('Job_ANP_id',  $anp_id)
            ->whereNull('job_redo_id')
            ->orderBy('modified_at','desc')
            ->first();
        $activity_job = collect();
        $OPpartner = collect();
        //dd($job);
      
       

        if (!empty($job)) {
            $jobId = $job->Job_id;
            //dd($jobId);
        

       

        $activity_job = TbActivityJob::select ('tb_activity_job.*' , 'j.RP_name' , 'op.user_nama')
        ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
        ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
        ->leftJoin('tb_user as op','op.user_employe_number', '=', 'tb_activity_job.modified_by')
        ->orderBy('tb_activity_job.created_at', 'desc')
        ->where('tb_activity_job.aj_job_id', $jobId)
        ->get();
        // ->first();

         //dd($activity_job);
        
        //$activity_job = $activity->get();

        $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
        ->leftJoin('tb_user AS o','o.user_id', '=', 'tb_operator_working.ow_user_id')
        ->where('tb_operator_working.ow_job_id', $jobId)
        ->get();
        //dd($OPpartner);
        //$OPpartner= $operator->get();
        //harusnya ditambah where employe_number jika ada
            // }else{
            //     $activity_job = collect(); // Mengembalikan koleksi kosong jika tidak ada job
            //     $OPpartner = collect();
        // }else {
            // $activity_job = collect(); // Mengembalikan koleksi kosong jika tidak ada job
            // $OPpartner = collect();
        }

        $data= array();

        $operators = TbUser::where('user_role','=','1')
        ->get();

        $rp=TbReasonPause::select('tb_reason_pause.*')
        ->where('RP_status', 0)
        ->get();
        
    

        $data['operators']= $operators;
        $data['activity_job']= $activity_job;

        // if()
        


        


        return view('PBEngine.job.actual-progress',
        // ,compact('item','job', 'activity_job', 'OPpartner')
        ['item' => $item,
        'job' => $job,
        'activity_job' => $activity_job,
        'OPpartner'=> $OPpartner,
        'data'=>$data,
        'rp'=>$rp,
        'rnq'=>$rnq
        ]
            
        );


      


    }

    //Start job di actual progress
    public function startJob($anp_id, Request $request){

        $dataJob= TbJob::select('a.Job_id' , 'a.Job_category')
        ->from('tb_job AS a')
        ->where('a.Job_ANP_id',  $anp_id)
        ->whereNull('job_redo_id')
        ->orderBy('a.modified_at','desc')
        ->get()
        ->first();

        // Check if a similar job already exists to prevent double save
$existingJob = TbJob::where('Job_ANP_id', $anp_id)
->where('Job_category', $request->Job_category)
->where('created_by', auth()->user()->id)
->where('created_at', '>=', now()->subMinute()) // Optionally, check for recent jobs created in the last minute
->first();

if ($existingJob) {
return redirect()->back();
}

       
        
        $newJob= TbJob::create([
        'Job_ANP_id' => $anp_id,
        'Job_category'  => $request->Job_category,
        'Job_adding' => $request->Job_adding,
        'Job_notes' => $request->Job_notes,
        'created_by' => auth()->user()->id,
        'created_at' => now()->format('Y-m-d H:i:s'),
        'modified_by' => auth()->user()->id,
        'modified_at' => now()->format('Y-m-d H:i:s')
        ]);

           
        $jobId = optional($dataJob)->Job_id;

        
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

        $newJobId= $newJob->Job_id;

        

        TbActivityJob::create([
            'aj_job_id' =>$newJobId,
            'aj_activity_before_id' =>$ajId,
            'aj_activity' => 0,
            'aj_qty' => 0,
            'created_by' => auth()->user()->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'modified_by' => auth()->user()->id,
            'modified_at' => now()->format('Y-m-d H:i:s')
        ]);

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
        // Handle additional operator partners
        $OPpartner = $request->input('operators', []);

        if (!empty($OPpartner) && $OPpartner[0] != "") {
            foreach ($OPpartner as $dataOP) {
            TbOperatorWorking::create([
                'ow_user_id' => $dataOP,
                'ow_job_id' => $newJobId,
                'created_by' => $user_id,
                'created_at' => now(),
                'modified_by' => $user_id,
                'modified_at' => now()
            ]);

            TbUser::where('user_id', $dataOP)->update(['user_IsAssign' => 1]);
            }
        }

        TbAssign::where('ANP_id', $anp_id)->update([
            'ANP_progres' => '1',
            'ANP_modified_by' => auth()->user()->id,
            'ANP_modified_at' => now()->format('Y-m-d H:i:s')
        ]);
    
        unset($dataJob, $lastAJpause, $jobId, $ajId, $newJobId, $OPpartner, $user_id);
        // unset($_POST);
        

        //ganti jadi redirect url actual-progress

        return response()->json(['status' => 'success', 'message' => 'Data job berhasil dimulai.', 'redirect_url' => url('start-stop-job/actual-progress/'.$anp_id)]);


        // return redirect('start-stop-job/actual-progress/'.$anp_id);

    }

    //Pause job di actual progress    
    public function pauseJob($anp_id, Request $request){
        try {
            // Retrieve job data
            $dataJob = TbJob::select('a.Job_id', 'a.Job_category')
                ->from('tb_job AS a')
                ->where('a.Job_ANP_id', $anp_id)
                ->whereNull('job_redo_id')
                ->orderBy('a.modified_at', 'desc')
                ->first();



    
            if (is_null($dataJob)) {
                return response()->json(['status' => 'error', 'message' => 'Job not found.'], 404);
            }
    
            $jobId = $dataJob->Job_id;
    
            // Retrieve last activity job pause
            $lastAJpause = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                ->where('tb_activity_job.aj_job_id', $jobId)
                ->orderBy('created_at', 'desc')
                ->first();
    
            $ajId = optional($lastAJpause)->aj_id ?? 0;

            $existingPause = TbActivityJob::where('aj_job_id', $jobId)
         ->where('aj_activity', 1)
         ->where('created_at', '>', now()->subSeconds(5)) // Adjust the time frame as needed
         ->exists();

         
    if ($existingPause) {
        return redirect()->back();
    }
    
            // Create new activity job
            TbActivityJob::create([
                'aj_job_id' => $jobId,
                'aj_activity_before_id' => $ajId,
                'aj_activity' => 1,
                'aj_qty' => 0,
                'created_by' => auth()->user()->id,
                'created_at' => now(),
                'modified_by' => auth()->user()->id,
                'modified_at' => now()
            ]);
    
            $lastAJ = TbActivityJob::select('tb_activity_job.*', 'j.RP_name', 'op.user_nama')
                ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
                ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
                ->leftJoin('tb_user as op', 'op.user_employe_number', '=', 'tb_activity_job.modified_by')
                ->where('tb_activity_job.aj_job_id', $jobId)
                ->orderBy('created_at', 'desc')
                ->first();
    
            $lastAJid = optional($lastAJ)->aj_id;
    
            $RP = $request->input('rp');
    
            TbDetailReasonPause::create([
                'DRP_rp_id' => $RP,
                'DRP_aj_id' => $lastAJid,
                'created_by' => auth()->user()->id,
                'created_at' => now(),
                'modified_by' => auth()->user()->id,
                'modified_at' => now()
            ]);
    
            TbAssign::where('ANP_id', $anp_id)->update([
                'ANP_progres' => 2,
                'ANP_modified_by' => auth()->user()->id,
                'ANP_modified_at' => now()
            ]);
    
            $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
                ->leftJoin('tb_user AS o', 'o.user_id', '=', 'tb_operator_working.ow_user_id')
                ->where('tb_operator_working.ow_job_id', $jobId)
                ->get();
    
            foreach ($OPpartner as $op) {
                TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 2]);
            }

            unset($dataJob, $lastAJpause, $jobId, $ajId, $lastAJ, $lastAJid, $RP, $OPpartner, $user_id);
    
            return response()->json(['status' => 'success', 'message' => 'Data job berhasil dipause.', 'redirect_url' => url('start-stop-job/actual-progress/'.$anp_id)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while pausing the job.'], 500);
        }
    }
    
    //Start after pause job di actual progress
    public function startafterpauseJob($anp_id, Request $request){
        $newjobdata= TbJob::select('a.Job_id' , 'a.Job_category')
        ->from('tb_job AS a')
        ->where('a.Job_ANP_id',  $anp_id)
        ->whereNull('job_redo_id')
        ->orderBy('a.modified_at','desc')
        ->get()
        ->first();

        $newJobId= $newjobdata->Job_id;
        
        

        $lastAJpause[0] = TbActivityJob::select ('tb_activity_job.*' , 'j.RP_name' , 'op.user_nama')
        ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
        ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
        ->leftJoin('tb_user as op','op.user_employe_number', '=', 'tb_activity_job.modified_by')
        ->orderBy('created_at', 'desc')
        ->where('tb_activity_job.aj_job_id', $newJobId)
        ->first();

        $ajId = optional($lastAJpause[0])->aj_id;

        // Check if there's already a pause activity with the same reason
        $existingPause = TbActivityJob::where('aj_job_id', $newJobId)
        ->where('aj_activity', 0)
        ->where('created_at', '>', now()->subSeconds(5)) // Adjust the time frame as needed
        ->exists();

        if ($existingPause) {
            return redirect()->back();
        }

        if(empty($ajId)){
            $ajId = 0;
        }

        

        

        TbActivityJob::create([
            'aj_job_id' =>$newJobId,
            'aj_activity_before_id' =>$ajId,
            'aj_activity' => 0,
            'aj_qty' => 0,
            'created_by' => auth()->user()->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'modified_by' => auth()->user()->id,
            'modified_at' => now()->format('Y-m-d H:i:s')
        ]);

        TbAssign::where('ANP_id', $anp_id)->update([
            'ANP_progres' => '1',
            'ANP_modified_by' => auth()->user()->id,
            'ANP_modified_at' => now()->format('Y-m-d H:i:s')
        ]);

        $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
        ->leftJoin('tb_user AS o','o.user_id', '=', 'tb_operator_working.ow_user_id')
        ->where('tb_operator_working.ow_job_id', $newJobId)
        ->get();

        foreach ($OPpartner as $op) {
            TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 1]);
        }


        unset($newjobdata, $newJobId, $lastAJpause,  $ajId, $OPpartner, $user_id);
        //unset($_POST);


        //ganti jadi redirect url actual-progress
        return response()->json(['status' => 'success', 'message' => 'Job berhasil dimulai kembali.', 'redirect_url' => url('start-stop-job/actual-progress/'.$anp_id)]);


        // return redirect('start-stop-job/actual-progress/'.$anp_id);
    }

    //Stop Job di actual progress
    public function stopJob($anp_id, Request $request){
        $dataJob= TbJob::select('a.Job_id' , 'a.Job_category')
        ->from('tb_job AS a')
        ->where('a.Job_ANP_id',  $anp_id)
        ->whereNull('job_redo_id')
        ->orderBy('a.modified_at','desc')
        ->get()
        ->first();

        $jobId = ($dataJob)->Job_id;
        //dd($dataJob);

        
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

        //dd($lastAJpause);

        $ajId = optional($lastAJpause[0])->aj_id;

        if(empty($ajId)){
            $ajId = 0;
        }

        $existingStop = TbActivityJob::where('aj_job_id', $jobId)
            ->where('aj_activity', 2)
            ->where('created_at', '>', now()->subSeconds(5)) // Adjust the time frame as needed
            ->exists();

            if ($existingStop) {
                return redirect()->back();
            }

        $qi = $request->input('qty');

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

        $query = TbAssign::select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent','bt.MaterialName','bt.qty', 'ANP_qty','ANP_qty_finish', 'ANP_progres', 'ANP_id','ANP_mesin_kode_mesin', 'c.customer_name')
        ->leftjoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'ANP_data_PRO')
        ->leftJoin('tb_customer AS c', 'c.customer_id' ,'=', 'mpc.mapping_customer_id')
        ->where('ANP_id', $anp_id);
        $assign = $query->get()->first();

        if($dataJob['Job_category'] == 2){
            $last_qty = 0;
            $anpprogres = 3;

        }else{
            $last_qty = intval($assign['ANP_qty_finish']) + intval($qi);
            if($assign['ANP_qty'] == $last_qty || $assign['ANP_qty'] < $last_qty ){
                $anpprogres = 4;
            }
            else{
                $anpprogres = 3;
            }
        }


        TbAssign::where('ANP_id', $anp_id)->update([
            'ANP_qty_finish' => $last_qty,
            'ANP_progres' => $anpprogres,
            'ANP_modified_by' => auth()->user()->id,
            'ANP_modified_at' => now()->format('Y-m-d H:i:s')
        ]);

        $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
        ->leftJoin('tb_user AS o','o.user_id', '=', 'tb_operator_working.ow_user_id')
        ->where('tb_operator_working.ow_job_id', $jobId)
        ->get();

        foreach ($OPpartner as $op) {
            TbUser::where('user_id', $op->user_id)->update(['user_IsAssign' => 0]);
        }

        TbJob::where('Job_id', $jobId)->update([
            'Job_finish_remark' => 'Normal Stop',
            'modified_at' => now()->format('Y-m-d H:i:s'),
            'modified_by' => auth()->user()->id
        ]);
        unset($dataJob, $jobId, $lastAJpause,  $ajId, $qi, $assign,$last_qty, $anpprogres, $OPpartner, $user_id);        

        return response()->json(['status' => 'success', 'message' => 'Job berhasil berhenti.', 'redirect_url' => url('start-stop-job/')]);

        // return redirect('start-stop-job/');
    }

}