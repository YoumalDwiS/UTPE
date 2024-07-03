<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbActivityJob;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbBundlingJob;
use App\Models\Table\PBEngine\TbBundlingRedoJob;
use App\Models\Table\PBEngine\TbJob;
use App\Models\Table\PBEngine\TbMappingImageComponent;
use App\Models\Table\PBEngine\TbMesin;
use App\Models\Table\PBEngine\TbReasonPause;
use App\Models\Table\PBEngine\TbDetailReasonPause;
use App\Models\Table\PBEngine\TbOperatorWorking;
use App\Models\Table\PBEngine\TbRedo;
use App\Models\Table\PBEngine\TbUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{


    public function index2(Request $request){
        
        $mesin_nama_mesin = request()->query('mesin_nama_mesin');

        $assign = (New TbAssign())->getAssignByMesin($mesin_nama_mesin);
      
        $nama_mesin = (New TbMesin())->getByKodeMesin($mesin_nama_mesin);
    
        $data['item'] = [];
        $pn = [];
        $pnc = [];
        $pro = [];
        $ps = [];
    
        foreach ($assign as $key) {
            $aj = null;
            $bundling = null;
            $ajname = null;
    
            $jd = (New TbActivityJob()) ->get_job_by_anp($key['ANP_id']);
            $cekoperator = 0;
    
            if (!empty($jd)) {
                $ajj = (New TbActivityJob()) ->getActivityJobByJobidTop1($jd['Job_id']);
                $jobbundling = (New TbBundlingJob()) ->get_key_by_jobid_anp_id($key['ANP_id']);
    
                $cek = (New TbActivityJob()) ->get_user_working_by_jobid($jd['Job_id'], session('nrp'));
    
                if (!empty($cek)) {
                    $cekoperator = 1;
                } else {
                    $cekoperator = 2;
                }
    
                if (empty($ajj)) {
                    $aj = null;
                } else {
                    $aj = $ajj['aj_activity'];
                }
    
                if (empty($jobbundling)) {
                    $bundling = null;
                } else {
                    $bundling = $jobbundling['bj_bundling_key'];
                }
            }
    
            switch ($aj) {
                case '0':
                    $ajname = 'Sedang Dikerjakan';
                    break;
                case '1':
                    $ajname = 'Jeda ' . $ajj['RP_name'];
                    break;
                case '2':
                    $ajname = 'Berhenti';
                    $cekoperator = 1;
                    break;
                default:
                    $ajname = 'Belum Mulai';
                    break;
            }
    
            $image = (New TbMappingImageComponent()) ->getMappingImageByPncPnp($key['PartNumberComponent'], $key['PN']);
            $imagestatus = empty($image) ? 1 : 2;
    
            $add1 = [
                'ANP_urgency' => $key['ANP_urgency'],
                'RP_name' => $ajname,
                'ANP_id' => $key['ANP_id'],
                'mappingImage' => $imagestatus,
                'keybundling' => $bundling,
                'aj_activity' => $aj,
                'mppid' => $key['ANP_key_IMA'],
                'PN_product' => $key['PN'],
                'PRO' => $key['ANP_data_PRO'],
                'PN_component' => $key['PartNumberComponent'],
                'qty_nesting' => $key['ANP_qty'],
                'customer' => $key['customer_name'],
                'Process_status' => $key['ANP_progres'],
                'ANP_modified_at' => $key['ANP_modified_at'],
                'qty_ima' => $key['qty'],
                'hakakses' => $cekoperator
            ];
    
            $data['item'][] = $add1;
            $pn[] = $key['PN'];
            $pnc[] = $key['PartNumberComponent'];
            $pro[] = $key['ANP_data_PRO'];
            $ps[] = $ajname;
        }
    
        $data['bundling_key'] = [];
    
        $bundlingkey = (New TbBundlingJob()) ->get_uniq_code($mesin_nama_mesin);
    
        foreach ($bundlingkey as $b) {
            $datacek = (New TbBundlingJob()) ->check_bundling_job_progres($b['bj_bundling_key']);
    
            if ($datacek != null) {
                $param = ['bj_bundling_key' => $b['bj_bundling_key']];
                $data['bundling_key'][] = $param;
            }
        }
    
        $datadetail = (New TbBundlingJob()) ->get_detail_data_uniq_code();
        $data['data_detail'] = [];
        $dataOPTemp = [];
    
        foreach ($datadetail as $dd) {
            $dataadd = [
                'bj_bundling_key' => $dd['bj_bundling_key'],
                'PN' => $dd['PN'],
                'PRONumber' => $dd['PRONumber'],
                'PartNumberComponent' => $dd['PartNumberComponent'],
                'ANP_qty' => $dd['ANP_qty'],
                'lastprocess' => $dd['ANP_progres']
            ];
    
            $data['data_detail'][] = $dataadd;
            $cat = 'bundling';
            $OP = (New TbActivityJob()) ->get_user_working_by_anpid($dd['ANP_id'], $cat);
    
            foreach ($OP as $o) {
                $dataaddOP = [
                    'user_nama' => $o['user_nama'],
                    'bj_bundling_key' => $o['bj_bundling_key']
                ];
    
                $dataOPTemp[] = $dataaddOP;
            }
        }

        $data['OP'] = array_unique($dataOPTemp, SORT_REGULAR);
        $data['ProcessStatus'] = array_unique($ps);
        $data['pro'] = array_unique($pro);
        $data['PN_component'] = array_unique($pnc);
        $data['PN_product'] = array_unique($pn);
        
        $data['job'] = 0;
        //$data['mesin_nama'] = $nama_mesin['mesin_nama_mesin'];
        $data['mesin_nama'] = $mesin_nama_mesin;
        sort($data['pro']);
    
        $nama_mesin = (New TbMesin()) ->getByKodeMesin($mesin_nama_mesin);
        $jobredo = (New TbRedo()) ->get_all_redo_job_not_finish($mesin_nama_mesin);
        $data['jobredo'] = [];
    
        foreach ($jobredo as $jr) {
            $cekoperator = 0;
            $key = (New TbBundlingRedoJob()) ->get_key_bundling_by_redoid($jr['r_id']);
            $jobredoid = (New TbRedo()) ->get_job_by_redoid($jr['r_id']);
    
            if (empty($jobredoid['Job_id'])) {
                $jobredoid['Job_id'] = 99;
            }
    
            $cek = (New TbActivityJob()) ->get_user_working_by_jobid($jobredoid['Job_id'], session('nrp'));
    
            if (!empty($cek)) {
                $cekoperator = 1;
            } else {
                $cekoperator = 2;
            }
    
            if ($key == null) {
                $key['brj_bundling_key'] = 'kosong';
            }
    
            $paramjr = [
                'keybundling' => $key['brj_bundling_key'],
                'PRONumber' => $jr['PRONumber'],
                'PN' => $jr['PN'],
                'PartNumberComponent' => $jr['PartNumberComponent'],
                'PartNameComponent' => $jr['PartNameComponent'],
                'MaterialName' => $jr['MaterialName'],
                'productname' => $jr['productname'],
                'r_category' => $jr['r_category'],
                'r_progres' => $jr['r_progres'],
                'r_qty' => $jr['r_qty'],
                'r_note' => $jr['r_note'],
                'r_id' => $jr['r_id'],
                'hakakses' => $cekoperator,
            ];
    
            $data['jobredo'][] = $paramjr;
        }
    
        // Bundling redo
        $data['bundling_key_redo'] = (New TbBundlingRedoJob()) ->get_uniq_code($mesin_nama_mesin);
        $datadetail = (New TbBundlingRedoJob()) ->get_detail_data_uniq_code();
        $data['data_detail_redo'] = [];
        $dataOPTempredo = [];
    
        foreach ($datadetail as $dd) {
            $dataadd = [
                'brj_bundling_key' => $dd['brj_bundling_key'],
                'PN' => $dd['PN'],
                'PRONumber' => $dd['PRONumber'],
                'PartNumberComponent' => $dd['PartNumberComponent'],
                'r_qty' => $dd['r_qty'],
                'lastprocess' => $dd['r_progres']
            ];
    
            $data['data_detail_redo'][] = $dataadd;
            $cat = 'bundling';
            $OP = (New TbActivityJob()) ->get_user_working_by_r_id($dd['r_id'], $cat);
    
            foreach ($OP as $o) {
                $dataaddOP = [
                    'user_nama' => $o['user_nama'],
                    'brj_bundling_key' => $o['brj_bundling_key']
                ];
    
                $dataOPTempredo[] = $dataaddOP;
            }
        }
    
        $data['OPRedo'] = array_unique($dataOPTempredo, SORT_REGULAR);
        $data['mesin_nama'] = $mesin_nama_mesin;
        $data['_view'] = 'PBEngine.job.index';


            if ($request->ajax()) {
                $perPage = 10;
            $data = TbAssign::paginate($perPage);

        
            foreach ($data as $item) {
                $mesin = TbMesin::where('mesin_kode_mesin', $item->ANP_mesin_kode_mesin)->get();
                $jobs = [];
                foreach($mesin as $msn) {
                    $job = TbAssign::select('bj.bj_bundling_key' , 'bt.PN' , 'bt.PRONumber' , 'bt.PartNumberComponent' , 'a.ANP_qty' , 'a.ANP_progres' , 'a.ANP_id')
                    ->join('tb_bundling_job AS bj' ,'bj.bj_anp_id', '=', 'ANP_key_IMA')
                    ->join('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                    ->where('a.ANP_mesin_kode_mesin', $mesin_nama_mesin)
                    ->first();
                    if($job) {
                        $jobs[] = $job->toArray();
                    }
                }
                $item->setAttribute('JOBs', $jobs);
            }
        }
    
        return view('PBEngine.job.index', 
        compact('dataOPTemp',
                'ps',
                'pro',
                'pnc',
                'pn',
                'dataOPTempredo',
        ));
      
    }

    public function chooseMachine(Request $request){
        dd($request->all());
        return redirect('start-stop-job/'.$request->machine);
    }

    public function index( Request $request){
            // $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber','bt.qty', 'bt.PartNumberComponent', 'ANP_qty','ANP_qty_finish', 'ANP_progres', 'ANP_id', 'm.mesin_nama_mesin')
            // ->leftJoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_id')
            // ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
            // ->join('tb_mesin as m', 'ANP_mesin_kode_mesin','=','m.mesin_kode_mesin');
            $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber','bt.qty', 'bt.PartNumberComponent', 'ANP_qty','ANP_qty_finish', 'ANP_progres', 'ANP_id', 'ANP_urgency','m.mesin_nama_mesin', 'c.customer_name')
            ->leftJoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_id')
            ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
            ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro', '=', 'ANP_data_PRO')
            ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
            ->join('tb_mesin as m', 'ANP_mesin_kode_mesin','=','m.mesin_kode_mesin')
            ->orderBy('ANP_urgency', 'desc');

            if($request->all == 1 || $request->all()==null){
                $query->where('ANP_progres','=', '1');
            } else {
            // Filter range PRO
            if (isset($request->startPRO) && isset($request->endPRO)) {
                $startPRO = $request->startPRO;
                $endPRO = $request->endPRO;
                $query->whereBetween('bt.PRONumber', [$startPRO, $endPRO]);
            }
            
            if (isset($request->PN)) {
                $partNumberProduct = $request->PN;
                $query->where('bt.PN', 'LIKE', '%' . $partNumberProduct . '%');
            }
        
            // Cek apakah input Part Number Component ada
            if (isset($request->PartNumberComponent)) {
                $partNumberComponent = $request->PartNumberComponent;
                $query->where('bt.PartNumberComponent', 'LIKE', '%' . $partNumberComponent . '%');
            }
        
            // Cek apakah input Status Pekerjaan ada
            if (isset($request->ANPStatus)) {
                $statusPekerjaan = $request->ANPStatus;
                $query->where('ANP_progres', $statusPekerjaan);
            }

            if (isset($request->ANPKodeMesin)) {
                // $kodemesin = $request->ANPKodeMesin;
                $query->where('ANP_mesin_kode_mesin', $request->ANPKodeMesin);
            }

        }
        

        $results = $query->get();

        //dd($results);
        $userId = Auth::user()->id;
        $machines = TbMesin::join('pbengine6.tb_detail_mesin as dm', 'mesin_kode_mesin', '=', 'dm.DM_mesin_kode_mesin')
            ->join('pbengine6.tb_detail_user_process_group as dp', 'dm.DM_process_id', '=', 'dp.DOPG_Process_id')
            ->join('satria3.users as u', 'dp.DOPG_user_id', '=', 'u.id')
            ->select('mesin_kode_mesin as  kode_mesin', 'mesin_nama_mesin')
            ->where('mesin_status', 0)
            ->where('mesin_delete_status', 0)
            ->where('u.id', $userId)
            ->get();    

        $operators = TbUser::where('user_role','=','1')
        ->get();

        $rp=TbReasonPause::select('tb_reason_pause.*')
        ->where('RP_status', 0)
        ->get();
            
        $data['operators']= $operators;

        return view('PBEngine.job.index',
        ['results' => $results,
            'mesin_nama_mesin' => $results,
            'machines'=>$machines,
            'rp'=>$rp,
            'data'=>$data,
            'selectedMachine'=>$request->ANPKodeMesin,
        ]
        );
    }


     //Start job di index
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

        return response()->json(['status' => 'success', 'message' => 'Data job berhasil dimulai.', 'redirect_url' => url('start-stop-job/')]);


        // return redirect('start-stop-job/actual-progress/'.$anp_id);

    }


    //Pause Job Di index
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

    
            return response()->json(['status' => 'success', 'message' => 'Data job berhasil dipause.', 'redirect_url' => url('start-stop-job/')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while pausing the job.'], 500);
        }
    }

    //Start After Pause di index
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

        if(empty($ajId)){
            $ajId = 0;
        }

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
        return response()->json(['status' => 'success', 'message' => 'Job berhasil dimulai kembali.', 'redirect_url' => url('start-stop-job/')]);

    }

    //Stop Job di Index
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

    public function allData($mesin_nama_mesin, Request $request){

        $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'ANP_qty', 'ANP_progres', 'ANP_id')
        ->leftjoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_key_IMA')
        ->leftjoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->where('ANP_progres', '=', '1')
        ->where('ANP_mesin_kode_mesin', $mesin_nama_mesin);
        $results = $query->paginate(10);

        return view('PBEngine.job.index'
        ,
        ['results' => $results,
        'mesin_nama_mesin' => $mesin_nama_mesin]
            
        );


    }

    public static function getDataJob(){
        $mesin_nama_mesin = request()->query('mesin_nama_mesin');


        $perPage = 10;
        $data = TbAssign::paginate($perPage);
    
        foreach ($data as $item) {
            $mesin = TbMesin::where('mesin_kode_mesin', $item->ANP_mesin_kode_mesin)->get();
            //$item->setAttribute('MSNs', $mesin->toArray());
    
            $jobs = [];
            foreach($mesin as $msn) {
                $job = TbBundlingJob::select('bj_bundling_key' , 'bt.PN' , 'bt.PRONumber' , 'bt.PartNumberComponent' , 'a.ANP_qty' , 'a.ANP_progres' , 'a.ANP_id')
                ->join('tb_assign_nesting_programmer AS a' ,'bj_anp_id', '=', 'a.ANP_key_IMA')
                ->join('tb_base_table as bt', 'bt.mppid', '=', 'a.ANP_key_IMA')
                ->where('a.ANP_mesin_kode_mesin', $msn->mesin_kode_mesin)
                ->first();
                if($job) {
                    $jobs[] = $job->toArray();
                }
            }
            $item->setAttribute('JOBs', $jobs);
    
            // $map = TbMappingImageComponent::where('MIC_PN_component', $item->PartNumberComponent)
            //     ->where('MIC_PN_product', $item->PN)
            //     ->where('MIC_Status_Aktifasi', 0)
            //     ->get();
            // $item->setAttribute('MAP', $map->toArray());
        }
    
  
        return response()->json($data->toArray());

        
    }

    public function test(Request $request){
       
        $mesin_nama_mesin = request()->query('mesin_nama_mesin');

        $assign = (New TbAssign())->getAssignByMesin($mesin_nama_mesin);
      
        $nama_mesin = (New TbMesin())->getByKodeMesin($mesin_nama_mesin);
    
        $data['item'] = [];
        $pn = [];
        $pnc = [];
        $pro = [];
        $pstatus = [];
    
        foreach ($assign as $key) {
            $aj = null;
            $bundling = null;
            $ajname = null;
    
            $jd = (New TbActivityJob()) ->get_job_by_anp($key['ANP_id']);
            $cekoperator = 0;
    
            if (!empty($jd)) {
                $ajj = (New TbActivityJob()) ->getActivityJobByJobidTop1($jd['Job_id']);
                $jobbundling = (New TbBundlingJob()) ->get_key_by_jobid_anp_id($key['ANP_id']);
    
                $cek = (New TbActivityJob()) ->get_user_working_by_jobid($jd['Job_id'], session('nrp'));
    
                if (!empty($cek)) {
                    $cekoperator = 1;
                } else {
                    $cekoperator = 2;
                }
    
                if (empty($ajj)) {
                    $aj = null;
                } else {
                    $aj = $ajj['aj_activity'];
                }
    
                if (empty($jobbundling)) {
                    $bundling = null;
                } else {
                    $bundling = $jobbundling['bj_bundling_key'];
                }
            }
    
            switch ($aj) {
                case '0':
                    $ajname = 'Sedang Dikerjakan';
                    break;
                case '1':
                    $ajname = 'Jeda ' . $ajj['RP_name'];
                    break;
                case '2':
                    $ajname = 'Berhenti';
                    $cekoperator = 1;
                    break;
                default:
                    $ajname = 'Belum Mulai';
                    break;
            }
    
            $image = (New TbMappingImageComponent()) ->getMappingImageByPncPnp($key['PartNumberComponent'], $key['PN']);
            $imagestatus = empty($image) ? 1 : 2;
    
            $add1 = [
                'ANP_urgency' => $key['ANP_urgency'],
                'RP_name' => $ajname,
                'ANP_id' => $key['ANP_id'],
                'mappingImage' => $imagestatus,
                'keybundling' => $bundling,
                'aj_activity' => $aj,
                'mppid' => $key['ANP_key_IMA'],
                'PN_product' => $key['PN'],
                'PRO' => $key['ANP_data_PRO'],
                'PN_component' => $key['PartNumberComponent'],
                'qty_nesting' => $key['ANP_qty'],
                'customer' => $key['customer_name'],
                'Process_status' => $key['ANP_progres'],
                'ANP_modified_at' => $key['ANP_modified_at'],
                'qty_ima' => $key['qty'],
                'hakakses' => $cekoperator
            ];
    
            $data['item'][] = $add1;
            $pn[] = $key['PN'];
            $pnc[] = $key['PartNumberComponent'];
            $pro[] = $key['ANP_data_PRO'];
            $pstatus[] = $ajname;
        }
    
        $data['bundling_key'] = [];
    
        $bundlingkey = (New TbBundlingJob()) ->get_uniq_code($mesin_nama_mesin);
    
        foreach ($bundlingkey as $b) {
            $datacek = (New TbBundlingJob()) ->check_bundling_job_progres($b['bj_bundling_key']);
            
    
            if ($datacek != null) {
                $param = ['bj_bundling_key' => $b['bj_bundling_key']];
                $data['bundling_key'][] = $param;
                
            }
        }
    
        $datadetail = (New TbBundlingJob()) ->get_detail_data_uniq_code();
        $data['data_detail'] = [];
        $dataOPTemp = [];
    
        foreach ($datadetail as $item) {

            $dataadd = [
                'bj_bundling_key' => $item['bj_bundling_key'],
                'PN' => $item['PN'],
                'PRONumber' => $item['PRONumber'],
                'PartNumberComponent' => $item['PartNumberComponent'],
                'ANP_qty' => $item['ANP_qty'],
                'lastprocess' => $item['ANP_progres']
            ];
    
            $data['data_detail'][] = $dataadd;
            // $item->setAttribute('keybundling',  $data['data_detail']);
            // $item->setAttribute('PN_product', $data['data_detail']);
            // $item->setAttribute('PRO', $data['data_detail']);
            // $item->setAttribute('PN_component', $data['data_detail']);
            // $item->setAttribute('qty_nesting', $data['data_detail']);
            

            $cat = 'bundling';
            $OP = (New TbActivityJob()) ->get_user_working_by_anpid($item['ANP_id'], $cat);
            // $item->setAttribute('aj_aktivity', $OP);
            
            foreach ($OP as $o) {
                $dataaddOP = [
                    'user_nama' => $o['user_nama'],
                    'bj_bundling_key' => $o['bj_bundling_key']
                ];
    
                $dataOPTemp[] = $dataaddOP;
            }
        }
    
        $data['OP'] = array_unique($dataOPTemp, SORT_REGULAR);
        $data['ProcessStatus'] = array_unique($pstatus);
        $data['pro'] = array_unique($pro);
        $data['PN_component'] = array_unique($pnc);
        $data['PN_product'] = array_unique($pn);
        $data['job'] = 0;
        $data['mesin_nama'] = $mesin_nama_mesin;
        sort($data['pro']);
    
        $nama_mesin = (New TbMesin()) ->getByKodeMesin($mesin_nama_mesin);
        $jobredo = (New TbRedo()) ->get_all_redo_job_not_finish($mesin_nama_mesin);
        $data['jobredo'] = [];
    
        foreach ($jobredo as $jr) {
            $cekoperator = 0;
            $key = (New TbBundlingRedoJob()) ->get_key_bundling_by_redoid($jr['r_id']);
            $jobredoid = (New TbRedo()) ->get_job_by_redoid($jr['r_id']);
    
            if (empty($jobredoid['Job_id'])) {
                $jobredoid['Job_id'] = 99;
            }
    
            $cek = (New TbActivityJob()) ->get_user_working_by_jobid($jobredoid['Job_id'], session('nrp'));
    
            if (!empty($cek)) {
                $cekoperator = 1;
            } else {
                $cekoperator = 2;
            }
    
            if ($key == null) {
                $key['brj_bundling_key'] = 'kosong';
            }
    
            $paramjr = [
                'keybundling' => $key['brj_bundling_key'],
                'PRONumber' => $jr['PRONumber'],
                'PN' => $jr['PN'],
                'PartNumberComponent' => $jr['PartNumberComponent'],
                'PartNameComponent' => $jr['PartNameComponent'],
                'MaterialName' => $jr['MaterialName'],
                'productname' => $jr['productname'],
                'r_category' => $jr['r_category'],
                'r_progres' => $jr['r_progres'],
                'r_qty' => $jr['r_qty'],
                'r_note' => $jr['r_note'],
                'r_id' => $jr['r_id'],
                'hakakses' => $cekoperator,
            ];
    
            $data['jobredo'][] = $paramjr;
        }
    
        // Bundling redo
        $data['bundling_key_redo'] = (New TbBundlingRedoJob()) ->get_uniq_code($mesin_nama_mesin);
        $datadetail = (New TbBundlingRedoJob()) ->get_detail_data_uniq_code();
        $data['data_detail_redo'] = [];
        $dataOPTempredo = [];
    
        foreach ($datadetail as $dd) {
            $dataadd = [
                'brj_bundling_key' => $dd['brj_bundling_key'],
                'PN' => $dd['PN'],
                'PRONumber' => $dd['PRONumber'],
                'PartNumberComponent' => $dd['PartNumberComponent'],
                'r_qty' => $dd['r_qty'],
                'lastprocess' => $dd['r_progres']
            ];
    
            $data['data_detail_redo'][] = $dataadd;
            $cat = 'bundling';
            $OP = (New TbActivityJob()) ->get_user_working_by_r_id($dd['r_id'], $cat);
    
            foreach ($OP as $o) {
                $dataaddOP = [
                    'user_nama' => $o['user_nama'],
                    'brj_bundling_key' => $o['brj_bundling_key']
                ];
    
                $dataOPTempredo[] = $dataaddOP;
            }
        }
    
        $data['OPRedo'] = array_unique($dataOPTempredo, SORT_REGULAR);
        $data['mesin_nama'] = $mesin_nama_mesin;
        $data['_view'] = 'PBEngine.job.index';
    
        return response()->json($datadetail);
        
    }

    public function mesinBreakdown($kodemesin = null)
    {
        if(empty($kodemesin)){
            $kodemesin = session('active_job');
        }
        else{
            session(['active_job' => $kodemesin]);
        }

        $assignModel = new TbAssign();
        $assign = $assignModel->getAssignByMesin($kodemesin);

        $mesinModel = new TbMesin();
        $nama_mesin = $mesinModel->getByKodeMesin($kodemesin);
        // Sisipkan kode untuk memproses data $assign dan $nama_mesin ke dalam format yang sesuai dengan kebutuhan tampilan

        return view('job.mesin_breakdown', [
            'assign' => $assign,
            'mesin_nama' => $nama_mesin->mesin_nama_mesin
        ]);
    }

    public function mcd($anp_id)
    {
        $data['anp_id'] = $anp_id;

        $data['ANP'] = TbAssign::select('tb_assign_nesting_programmer.*', 'b.*', 'd.process_name', 'c.customer_name')
        ->join('tb_mesin as b', 'b.mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
        ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin','=','b.mesin_kode_mesin')
        ->join('tb_process as d', 'd.proses_id', '=', 'dm.DM_process_id')
        ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro','=','ANP_data_PRO')
        ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
        ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->where('ANP_id', $anp_id)
        ->first();

        if ($data['ANP'] == null) {
            $keyima = $anp_id;
        } else {
            $keyima = $data['ANP']->ANP_key_IMA;
        }

        $dataIMA = TbBase::where('mppid', $keyima)
        ->first();

        $PnComp = $dataIMA->PartNumberComponent;

        $PN  = $dataIMA->PN;

        $image = TbMappingImageComponent::where('MIC_PN_component',$PnComp)
        ->where('MIC_PN_product', $PN)
        ->first();

        // dd($image);

        return view('PBEngine.job.modal-component-drawing',
        ['data' => $data,
         'dataIMA' => $dataIMA,
         'image'=>$image,
         'anp_id'=>$anp_id
        
        ]);

    }

    public function mcsi($anp_id)
    {
        $assignModel = new TbAssign();
        $baseModel = new TbBase();
        $mappingImageModel = new TbMappingImageComponent();
        $activityJob = new TbActivityJob();

        $dataCSI['anp_id'] = $anp_id;
        $dataCSI['ANP'] = $assignModel->get_assign_by_assign_id($anp_id);
        
        $qf = $activityJob->getFinishQtyByAnpid($anp_id);
       
        $dataCSI['qtyfinish'] = empty($qf) ? 0 : (int)$qf[0]->aj_qty_sum;

        

        $jobdata = $activityJob->getJobByAnpArray($anp_id);
        $lenght = count($jobdata) - 1;


        $temphour = $activityJob->getDataHour($anp_id);

        
        if ($temphour) {
            $dataCSI['actuanMH'] = $temphour->ActualManHourTotal;
        } else {
            $dataCSI['actuanMH'] = null; // Atau nilai default lain jika diperlukan
        }


        $dataCSI['item_csi'] = [];
        $dataCSI['activity_job'] = [];

        if($lenght >= 0) {
            $dataCSI['activity_job'] = $activityJob->getActivityJobByJobidTop1($jobdata[$lenght]->Job_id);
        }
        

        $dataIMA = $baseModel->get_data_by_mppid($dataCSI['ANP'][0]->ANP_key_IMA);
        $image = $mappingImageModel->get_MappingImage_by_PNc_PNp($dataIMA[0]->PartNumberComponent, $dataIMA[0]->PN);

        $imagestatus = empty($image) ? 1 : 2;

        $add4 = [
            'mappingImage' => $imagestatus,
            'PN_product' => $dataIMA[0]->PN,
            'Name_product' => $dataIMA[0]->productname,
            'pro' => $dataIMA[0]->PRONumber,
            'lenght' => $dataIMA[0]->Length,
            'width' => $dataIMA[0]->Width,
            'weight' => $dataIMA[0]->weight,
            'thickness' => $dataIMA[0]->Thickness,
            'process_name' => $dataCSI['ANP'][0]->process_name,
            'PN_component' => $dataIMA[0]->PartNumberComponent,
            'Name_component' => $dataIMA[0]->PartNameComponent,
            'Name_material' => $dataIMA[0]->MaterialName,
            'MH' => $dataIMA[0]->MHProcess,
            'PlanStartDate' => $dataIMA[0]->PlanStartdate,
            'PlanEndDate' => $dataIMA[0]->PlanEndDate,
            'qty' => $dataIMA[0]->qty,
            'mesin' => $dataCSI['ANP'][0]->mesin_nama_mesin,
            'qty_nesting' => 0,
            'Process_status' => 0,
            'LastModifiedOP' => 0,
            'LastModifiedAT' => 0,
            'MH_actual' => 0
        ];

        $dataCSI['item_csi'][] = $add4;

        // dd($dataCSI);

        return view('PBEngine.job.csi', $dataCSI);
    }

    public function schedule_progress_history($anp_id){

        $Activity_job_model = new TbActivityJob();
        $Assign_model = new TbAssign();
        $Base_model = new TbBase();

        
        $data['anp_id'] = $anp_id;
        $jd = $Activity_job_model->getJobByAnpArray($anp_id);
        // dd($jd);
        
        $lastprogres = null;
        if(!empty($jd)){
            $aj = $Activity_job_model->get_activity_job_by_jobid($jd[0]->Job_id);//var_dump($jd['Job_id']);die();
            if(!empty($aj)){
                $lastprogres = $aj[0]['aj_activity'];
            }
        }
        // dd($aj);
        $data['op'] = $Activity_job_model->get_user_working_by_anpid($anp_id);
        // dd($data['op']);
        $data['lastprogres'] = $lastprogres;
        $data['history'] = array();
        $history = $Activity_job_model->get_finished_activity($anp_id);
        // dd($history);
        //var_dump($history);die();
        if(empty($history)){
            $data['history'] = null;
        }else{
            foreach($history as $itemH){
                $MH = $Activity_job_model->get_manhour_by_jobid($itemH->Job_id);
                // dd($MH);
                $addH = array(
                    'jobid' => $itemH->Job_id ,
                    'date' => $itemH->created_at,
                    'ActualMH' => $MH->ActualManHourTotal,
                    'finishQTY' => $itemH->aj_qty,
                    'jc' => $MH->jc,
                    'remark' => $itemH->Job_finish_remark,
                );
                array_push($data['history'], $addH);
            }
        }
        // dd($data['history']);
        $data['ANP'] = $Assign_model->get_assign_by_assign_id($anp_id);
        $data['item'] = array();
        $dataIMA = $Base_model->get_data_by_mppid($data['ANP'][0]->ANP_key_IMA);
        // dd($dataIMA);
        $aj = null;
        $ajname = null;
        if(!empty($jd)){
            $ajj = $Activity_job_model->getActivityJobByJobidTop1($jd[0]->Job_id);
            // dd($ajj);
        }
        if(empty($ajj)){
            $aj = null;
        }else{
            $aj = $ajj[0]->aj_activity;
        }
        switch ($aj) {
          case '0':
          $ajname = 'START';
          break;
          case '1' :
        //   $ajname = 'Break '.$ajj['RP_name'];
        $ajname = 'Break ' . (isset($ajj['RP_name']) && !is_null($ajj['RP_name']) ? $ajj['RP_name'] : 'Nama tidak tersedia');
          break;
          case '2' :
          $ajname = 'NOT START';
          break;

          default:
          $ajname = 'NOT START';
          break;
        }
        $add = array(
            'RP_name' => $ajname,
            'ANP_id' => $data['ANP'][0]->ANP_id,
            'mppid' => $data['ANP'][0]->ANP_key_IMA,
            'PN_product' => $dataIMA[0]->PN,
            'PRO' => $data['ANP'][0]->ANP_data_PRO,
            'PN_component' => $dataIMA[0]->PartNumberComponent,
            'qty_nesting' => $data['ANP'][0]->ANP_qty,
            'customer' => $data['ANP'][0]->customer_name,
            'Name_material' => $dataIMA[0]->MaterialName,
            'Process_status' => $data['ANP'][0]->ANP_progres
        );
        array_push($data['item'], $add);
        // dd($data);

        $data['ANP_id'] = $anp_id;
        // $data['_view'] = 'Job/schedule_progress_history';
        return view('PBEngine.job.schedule_progress_history', $data);

        // $this->fun->redirectPage($data, 'Detail Job', 'schedule_progress_history', 'Schedule Progress History'); 

    }
}
