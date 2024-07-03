<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbActivityJob;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbBundlingJob;
use App\Models\Table\PBEngine\TbBundlingRedoJob;
use App\Models\Table\PBEngine\TbDetailMesin;
use App\Models\Table\PBEngine\TbJob;
use App\Models\Table\PBEngine\TbMappingImageComponent;
use App\Models\Table\PBEngine\TbMesin;
use App\Models\Table\PBEngine\TbRedo;
use App\Models\Table\PBEngine\TbBundlingStartStop;
use App\Models\Table\PBEngine\TbBundlingStartStopRedo;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PBEngine\TbOperatorWorking;
use App\Models\Table\PBEngine\TbProcess;
use App\Models\Table\PBEngine\TbReasonPause;
use App\Models\Table\PBEngine\TbUser;
use Carbon\Carbon;

class JobGroupLeaderController extends Controller
{

    public function chooseMachine(Request $request){
        dd($request->all());
        return redirect('outstanding-job/'.$request->machine);
    }


    public function index( Request $request){
        $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber', 'bt.qty','bt.PartNumberComponent', 'ANP_qty', 'ANP_progres', 'ANP_id', 'm.mesin_nama_mesin')
       ->leftJoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_id')
       ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
       ->join('tb_mesin as m', 'ANP_mesin_kode_mesin','=','m.mesin_kode_mesin');
        //    ->where('ANP_mesin_kode_mesin', $mesin_kode_mesin);

            $userId = Auth::user()->id;
                            $machines = TbMesin::join('pbengine6.tb_detail_mesin as dm', 'mesin_kode_mesin', '=', 'dm.DM_mesin_kode_mesin')
                                ->join('pbengine6.tb_detail_user_process_group as dp', 'dm.DM_process_id', '=', 'dp.DOPG_Process_id')
                                ->join('satria3.users as u', 'dp.DOPG_user_id', '=', 'u.id')
                                // ->leftJoin('tb_mesin as m' , 'm.mesin_kode_mesin' ,'=', 'dm.DM_mesin_kode_mesin')
                                ->select('mesin_kode_mesin as  kode_mesin', 'mesin_nama_mesin')
                                ->where('mesin_status' ,0)
                                ->where('mesin_delete_status' ,0)
                                ->where('u.id', $userId)
                                ->get();

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
                // $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'ANP_qty', 'ANP_progres', 'ANP_id')
                // ->leftJoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_id')
                // ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
            
                // ->where('ANP_mesin_kode_mesin', $mesin_nama_mesin);
                

                // $results = $query->get();
                $results = $query->paginate(10);

                // dd($results);

            

                return view('PBEngine.job.outstanding_job',
                ['results' => $results,
                    'mesin_nama_mesin' => $results,
                    'machines'=>$machines,
                    'selectedMachine'=>$request->ANPKodeMesin
                // 'mesin_kode_mesin'=> $mesin_kode_mesin
                ]
                )
                ;
            // return view('PBEngine.job.index');
    }

    public function search($mesin_nama_mesin, Request $request)
    {
   

            $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'ANP_qty', 'ANP_progres', 'ANP_id')
                ->leftJoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_key_IMA')
                ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
                ->where('ANP_mesin_kode_mesin', $mesin_nama_mesin);

                dd($request->all);



                // Cek apakah input Part Number Product ada
                if (isset($request->PN)) {
                    $partNumberProduct = $request->input('PN');
                    $query->where('bt.PN', 'LIKE', '%' . $partNumberProduct . '%');
                }
            
                // Cek apakah input Part Number Component ada
                if (isset($request->filledPartNumberComponent)) {
                    $partNumberComponent = $request->input('PartNumberComponent');
                    $query->where('bt.PartNumberComponent', 'LIKE', '%' . $partNumberComponent . '%');
                }
            
                // Cek apakah input Status Pekerjaan ada
                if (isset($request->ANPStatus)) {
                    $statusPekerjaan = $request->input('ANPStatus');
                    $query->where('ANP_progres', $statusPekerjaan);
                }

            // $results = $query->get();
            $results = $query->paginate(10);

            if (isset($request->PN)) {
                dd($results);
            }
            


            return view('PBEngine.job.outstanding_job',
            // ,compact('results')
            [
                'results' => $results,
                'mesin_nama_mesin' => $mesin_nama_mesin
            ]);

    
    }

    public function allData($mesin_nama_mesin, Request $request){
   

        $query = TbAssign::select('bj.bj_bundling_key', 'bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent', 'ANP_qty', 'ANP_progres', 'ANP_id')
        ->leftjoin('tb_bundling_job AS bj', 'bj.bj_anp_id', '=', 'ANP_key_IMA')
        ->leftjoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->where('ANP_progres', '=', '1')
        ->where('ANP_mesin_kode_mesin', $mesin_nama_mesin);
        
        $results = $query->paginate(10);

        return view('PBEngine.job.outstanding_job'
        ,
        ['results' => $results,
        'mesin_nama_mesin' => $mesin_nama_mesin]
            
        );


    }


    public function actualProgress($anp_id, Request $request){
        $query = TbAssign::select('bt.PN', 'bt.PRONumber', 'bt.PartNumberComponent','bt.MaterialName','bt.qty', 'ANP_qty','ANP_qty_finish', 'ANP_progres', 'ANP_id', 'c.customer_name')
        ->leftjoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
        ->leftJoin('tb_mapping_pro_customer AS mpc', 'mpc.mapping_pro', '=', 'ANP_data_PRO')
        ->leftJoin('tb_customer AS c', 'c.customer_id' ,'=', 'mpc.mapping_customer_id')
        ->where('ANP_id', $anp_id);
        $item = $query->get()->first();

        $rnq = ($item->ANP_qty) - ($item->ANP_qty_finish);
       

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
        

            $OPpartner = TbOperatorWorking::select('tb_operator_working.*', 'o.user_nama', 'o.user_id')
            ->leftJoin('tb_user AS o','o.user_id', '=', 'tb_operator_working.ow_user_id')
            ->where('tb_operator_working.ow_job_id', $jobId)
            ->get();
        }

            $data= array();

            $operators = TbUser::where('user_role','=','1')
            ->get();

            $rp=TbReasonPause::select('tb_reason_pause.*')
            ->where('RP_status', 0)
            ->get();
            
        

            $data['operators']= $operators;
            $data['activity_job']= $activity_job;



        return view('PBEngine.job.gl_actual-progress',
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


    public function m_moving_machine_one_assign($anpid)
    {

                $data = (new TbAssign())->get_assign_by_id($anpid);
                // dd($data);

                if ($data) {
                    // $dataArray = $data->toArray();
                    $related = (new TbMesin())->get_mesin_by_process_id($data[0]->proses_id);
                    // dd($related);

                    return view('PBEngine.job.moving-machine', compact('data', 'related'));

                    // return response()->json($data);
                    // return response()->json($related);
                } else {
                    return view('PBEngine.job.moving-machine', compact('data', 'related'));

                    // return response()->json(['error' => 'Data not found'], 404);
                }
    }

    public function save_moving(Request $request, $anpid) {
        $now = Carbon::now();
        $data_ANP = (new TbAssign())->get_assign_by_id($anpid);

        // dd($data_ANP);

        if (!$data_ANP) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        // dd($data_ANP);
        // dd($data_ANP->ANP_id);

    
        // Gunakan ANP_id dari $data_ANP sebagai parameter untuk get_job_by_anp
        $jobdata = (new TbJob())->get_job_by_anp($anpid);
        if (!$jobdata) {
            return response()->json(['error' => 'Job data not found'], 404);
        }
        // $jobdata->toArray();
    
        // Debug untuk memastikan $jobdata benar
        // dd($jobdata);
    
      
    
        $lastAJpaused = (new TbActivityJob())->get_activity_job_by_jobid($jobdata['Job_id']);
        if (!$lastAJpaused || count($lastAJpaused) == 0) {
            return response()->json(['error' => 'Last paused activity job not found'], 404);
        }
        // dd($lastAJpaused);
    
        $qi = $request->input('qty');
        if (!$qi) {
            return response()->json(['error' => 'Input quantity finish !'], 400);
        }
    
        $paramsaj = [
            'aj_job_id' => $jobdata['Job_id'],
            'aj_activity_before_id' => $lastAJpaused[0]['aj_id'],
            'aj_activity' => 2,
            'aj_qty' => $qi,
            'created_by' => auth()->user()->id,
            'created_at' => $now->format('Y-m-d H:i:s'),
            'modified_by' => auth()->user()->id,
            'modified_at' => $now->format('Y-m-d H:i:s'),
        ];
        // dd($paramsaj);
        (new TbActivityJob())->add_activity_job($paramsaj);
    
        $qf = $data_ANP[0]->ANP_qty_finish ?? 0;
        $last_qty = intval($qf) + intval($qi);
        $anpprogres = ($data_ANP[0]->ANP_qty <= $last_qty) ? 4 : 3;
    
        $OPpartner = (new TbActivityJob())->get_user_working_by_jobid($jobdata['Job_id']);

        // dd($OPpartner);
        if ($OPpartner) {
            foreach ($OPpartner as $op) {
                $params = ['user_IsAssign' => 0];
                (new TbUser())->update_user($op['user_id'], $params);
            }
        }
    
        $jobdataHour = (new TbActivityJob())->getDataHour($anpid);

        // dd($jobdataHour);
        
        $total = !empty($jobdataHour) ? $jobdataHour : 0;
        // dd($total);
    
        $qtynewassign = $data_ANP[0]->ANP_qty - $last_qty;
        // dd($qtynewassign);

        $params = [
            'ANP_mesin_kode_mesin' => $request->input('mesintujuan'),
            'ANP_data_PRO' => $data_ANP[0]->ANP_data_PRO,
            'ANP_progres' => 0,
            'ANP_key_IMA' => $data_ANP[0]->ANP_key_IMA,
            'ANP_qty' => $qtynewassign,
            'ANP_data_code' => $data_ANP[0]->ANP_data_code,
            'ANP_data_duedate' => $data_ANP[0]->ANP_data_duedate,
            'ANP_data_mhprosess' => $data_ANP[0]->ANP_data_mhprosess,
            'ANP_created_by' => auth()->user()->id,
            'ANP_created_at' => $now->format('Y-m-d H:i:s'),
            'ANP_modified_by' => auth()->user()->id,
            'ANP_modified_at' => $now->format('Y-m-d H:i:s'),
            'ANP_estimate_startdate' => $data_ANP[0]->ANP_estimate_startdate,
            'ANP_estimate_enddate' => $data_ANP[0]->ANP_estimate_enddate,
            'ANP_urgency' => $data_ANP[0]->ANP_urgency,
            'ANP_qty_finish' => 0,
        ];
        // dd($params);
        (new TbAssign())->add_assign($params);
    
        $paramANP = [
            'ANP_qty_finish' => $last_qty,
            'ANP_progres' => $anpprogres,
            'ANP_modified_at' => $now->format('Y-m-d H:i:s'),
            'ANP_modified_by' => auth()->user()->id,
            'ANP_note' => $request->input('reason'),
        ];
        // dd($paramANP);
        (new TbAssign())->update_assign($anpid, $paramANP);
    
        $paramjob = [
            'Job_finish_remark' => 'Move to Other Machine',
            'modified_at' => $now->format('Y-m-d H:i:s'),
            'modified_by' => auth()->user()->id,
        ];
        // dd($paramjob);
        (new TbActivityJob())->update_job($jobdata['Job_id'], $paramjob);
        
    
        return redirect()->route('outstanding-job.index')->with('success', 'Data Berhasil Disimpan!');
        //  return redirect()->route('outstanding-job.index');
    }
    


    public function test(Request $request){
        // if (empty($mesin_nama_mesin)) {
        //     $mesin_nama_mesin = session('active_job');
        // } else {
        //     session(['active_job' => $mesin_nama_mesin]);
        // }
        // $mesin_nama_mesin = $request->mesin_nama_mesin;
        //$mesin_nama_mesin = $request->input('mesin_nama_mesin');
        $mesin_nama_mesin = request()->query('mesin_nama_mesin');


        $assign = (New TbAssign())->getAssignByMesin($mesin_nama_mesin);
      
        //$nama_mesin = (New TbMesin())->getByKodeMesin($assign['mesin_nama_mesin']);
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
    
        //$bundlingkey = (New TbBundlingJob()) ->get_uniq_code($kodemesin);
    
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

            // if (!is_array($item)) {
            //     $item = $item->toArray();
            // }
           

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
        //$data['mesin_nama'] = $nama_mesin['mesin_nama_mesin'];
        $data['mesin_nama'] = $mesin_nama_mesin;
        sort($data['pro']);
    
        // Untuk tab non standar
       // $nama_mesin = (New TbMesin()) ->get_mesin_by_kodemesin($kodemesin);
       // $jobredo = (New TbRedo()) ->get_all_redo_job_not_finish($kodemesin);

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
        //$data['bundling_key_redo'] = (New TbBundlingRedoJob()) ->get_uniq_code($kodemesin);
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
        $data['_view'] = 'PBEngine.job.outstanding_job';
    
        return response()->json($datadetail);
        // $totalRecords = TbAssign::count(); // Menghitung total baris data keseluruhan
        // $totalFilteredRecords = TbAssign::where('mesin_nama_mesin', $mesin_nama_mesin)->count(); // Menghitung total baris data setelah diterapkan filter

        // return response()->json([
        //     'draw' => $request->draw,
        //     'recordsTotal' => $totalRecords,
        //     'recordsFiltered' => $totalFilteredRecords,
        //     'data' => $data
        // ]);
    }

   

   

    public function mcsi($anp_id)
    {
        $assignModel = new TbAssign();
        $baseModel = new TbBase();
        $mappingImageModel = new TbMappingImageComponent();
        $activityJob = new TbActivityJob();

        $dataCSI['anp_id'] = $anp_id;
        $dataCSI['ANP'] = $assignModel->getAssignByAssignId($anp_id);
        
        $qf = $activityJob->getFinishQtyByAnpid($anp_id);
        $dataCSI['qtyfinish'] = empty($qf) ? 0 : (int)$qf['aj_qty'];

        $jobdata = $activityJob->getJobByAnpArray($anp_id);
        $lenght = count($jobdata) - 1;

        $temphour = $activityJob->getDataHour($anp_id);
        $dataCSI['actuanMH'] = $temphour['ActualManHourTotal'];

        $dataCSI['item_csi'] = [];
        $dataCSI['activity_job'] = [];

        if($lenght >= 0) {
            $dataCSI['activity_job'] = $activityJob->getActivityJobByJobidTop1($jobdata[$lenght]['Job_id']);
        }

        $dataIMA = $baseModel->getDataByMppid($dataCSI['ANP']['ANP_key_IMA']);
        $image = $mappingImageModel->getMappingImageByPncPnp($dataIMA['PartNumberComponent'], $dataIMA['PN']);

        $imagestatus = empty($image) ? 1 : 2;

        $add4 = [
            'mappingImage' => $imagestatus,
            'PN_product' => $dataIMA['PN'],
            'Name_product' => $dataIMA['productname'],
            'pro' => $dataIMA['PRONumber'],
            'lenght' => $dataIMA['Length'],
            'width' => $dataIMA['Width'],
            'weight' => $dataIMA['weight'],
            'thickness' => $dataIMA['Thickness'],
            'process_name' => $dataCSI['ANP']['process_name'],
            'PN_component' => $dataIMA['PartNumberComponent'],
            'Name_component' => $dataIMA['PartNameComponent'],
            'Name_material' => $dataIMA['MaterialName'],
            'MH' => $dataIMA['MHProcess'],
            'PlanStartDate' => $dataIMA['PlanStartdate'],
            'PlanEndDate' => $dataIMA['PlanEndDate'],
            'qty' => $dataIMA['qty'],
            'mesin' => $dataCSI['ANP']['mesin_nama_mesin'],
            'qty_nesting' => 0,
            'Process_status' => 0,
            'LastModifiedOP' => 0,
            'LastModifiedAT' => 0,
            'MH_actual' => 0
        ];

        $dataCSI['item_csi'][] = $add4;

        return view('modals.Job.CSI', $dataCSI);
    }

    public function mesin_breakdown($mesin_kode_mesin){

        $Activity_job_model = new TbActivityJob();
        $Assign_model = new TbAssign();
        $Base_model = new TbBase();
        $User_model = new TbUser();
        $Redo_model = new TbRedo();
        $Mesin_model = new TbMesin();
        $BundlingStartStop_model = new TbBundlingStartStop();
        $BundlingStartStopRedo_model = new TbBundlingStartStopRedo();


        $time = Carbon::now();

      
        $datachooses = array();
        $assign = $Assign_model->get_assign_by_mesin($mesin_kode_mesin);
        // dd($assign);
        $mesin = $Mesin_model->get_data_mesin_by_kodemesin($mesin_kode_mesin);
        // dd($mesin);
        $machine_breakdown = $mesin[0]->mesin_kode_mesin;
        // dd($machine_breakdown);
        $process_mesin = $mesin[0]->proses_id;
        // dd($process_mesin);
        //var_dump($assign);die();
        foreach ($assign as $db) {
            $qf = $Activity_job_model->getFinishQtyByAnpid($db->ANP_id);
            // dd($qf);

            $jd = $Activity_job_model->getJobByAnpArray($db->ANP_id);
            //  dd($jd);
            if ($qf[0]->aj_qty_sum == null) {
                $qf[0]->aj_qty_sum = 0;
            }
            if (!empty($jd)) {
                $ajj = $Activity_job_model->getActivityJobByJobidTop1($jd[0]->Job_id);
                if (empty($ajj)) {
                    $aj = null;
                } else {
                    $aj = $ajj[0]->aj_activity;
                }
            } else {
                $aj = 99;
            }
            // dd($aj);
            switch ($aj) {
                case '0':
                    $ajname = 'Sedang Dikerjakan';
                    break;
                case '1':
                    $ajname = 'Jeda ' . $ajj['RP_name'];
                    break;
                case '2':
                    $ajname = 'Berhenti';
                    break;
                default:
                    $ajname = 'Belum Mulai';
                    break;
            }
            // dd($ajname);

            // dd($db->ANP_id);
            $datachoose = $BundlingStartStop_model->get_choosen_ANP($db->ANP_id);
            // dd($datachoose);
            $tempchoose = array(
                'last_progress' => $ajname,
                'ANP_urgency' => $datachoose[0]->ANP_urgency,
                'PN' => $datachoose[0]->PN,
                'PRONumber' => $datachoose[0]->PRONumber,
                'PartNumberComponent' => $datachoose[0]->PartNumberComponent,
                'ANP_qty' => $datachoose[0]->ANP_qty,
                'customer' => $datachoose[0]->customer,
                'finishqty' => $qf[0]->aj_qty_sum,
                'ANP_id' => $datachoose[0]->ANP_id,
                'sisa' => $datachoose[0]->ANP_qty - $qf[0]->aj_qty_sum,
            );
            array_push($datachooses, $tempchoose);
            // dd($tempchoose);
        }
        $data['datachoose'] =  array_unique($datachooses, SORT_REGULAR);
        $data['mesin_kode_mesin'] = $mesin_kode_mesin;
        //  dd($data);
        $data['RelatedMesin'] = $Mesin_model->get_mesin_by_process_id($process_mesin);
        // dd($data['datachoose']);
        return view('PBEngine.job.job_mesin_breakdown', compact('data'));


        // $data['_view'] = 'Job/job_mesin_breakdown';



        // $this->fun->redirectPage($data, 'Outstanding Job', 'Outstanding Job', 'Machine Breakdown');
    }

    public function save_mesin_breakdown(Request $request ){

        $Activity_job_model = new TbActivityJob();
        $Assign_model = new TbAssign();
        $Base_model = new TbBase();
        $User_model = new TbUser();
        $Redo_model = new TbRedo();
        $Mesin_model = new TbMesin();
        $BundlingStartStop_model = new TbBundlingStartStop();
        $BundlingStartStopRedo_model = new TbBundlingStartStopRedo();

        $time = Carbon::now();

        $mesin_kode_mesin = $request->input('mesin_kode_mesin');
        
            // dd($mesin_kode_mesin);
          
        $qty = $request->input('isi');
        // dd($qty);
        $anp = $request->input('checkbox');
        // dd($anp);
        $anpmoving = $request->input('checkboxmoving');
        // dd($anpmoving);
        $indexqty = 0;
        // $machinemove = $request->input('mesintujuanindividual'); //var_dump($machinemove);die();
        // // dd($machinemove);

        // $machinemove1 = $request->input('mesintujuanall'); //var_dump($machinemove);die();
        // dd($machinemove1);

        $mesintujuanindividual = $request->input('mesintujuanindividual');
        $mesintujuanall = $request->input('mesintujuanall');
    
        // Jika mesintujuanindividual adalah null atau tidak ada, gunakan mesintujuanall
        $machinemove = array_map(function($individual) use ($mesintujuanall) {
            return $individual === 'none' ? $mesintujuanall : $individual;
        }, $mesintujuanindividual);

        // dd($machinemove);

        foreach ($anp as $anpid) {
            $datacheck = $Assign_model->get_assign_by_id($anpid);
            // dd($datacheck);
            if ($datacheck[0]->ANP_progres != '4') {
                $now = $time;
                $jobdata = $Activity_job_model->get_job_by_anp($anpid);
                // dd($jobdata);
                if (empty($jobdata)) {
                    $jobdata['Job_id'] = null;
                    $jobdata['Job_category'] = null;
                }
                // dd($jobdata);
                $lastAJpaused =  $Activity_job_model->get_activity_job_by_jobid($jobdata['Job_id']);
                // dd($lastAJpaused);
                $lastajpause_activity_job = null;
                if (!empty($lastAJpaused)) {
                    $lastajpause_activity_job = $lastAJpaused[0]['aj_id'];
                    // dd($lastajpause_activity_job);
                }
                $qi = $qty[$indexqty];
                // dd($qi);

                $paramsaj = array(
                    'aj_job_id' => $jobdata['Job_id'],
                    'aj_activity_before_id' => $lastajpause_activity_job,
                    'aj_activity' => 2,
                    'aj_qty' => $qty[$indexqty],
                    'created_by' => auth()->user()->id,
                    'created_at' => $now->format('Y-m-d H:i:s'),
                    'modified_by' => auth()->user()->id,
                    'modified_at' => $now->format('Y-m-d H:i:s'),
                );
                // dd($paramsaj);

                $Activity_job_model->add_activity_job($paramsaj);
                $assign = $Assign_model->get_assign_by_id($anpid);
                $anpprogres = 0;
                if ($datacheck[0]->ANP_qty_finish == null) {
                    $qf = 0;
                } else {
                    $qf = $datacheck[0]->ANP_qty_finish;
                }

                $lastprogres = 3;
                $movingindex = 0;
                if (!empty($anpmoving)) {
                    foreach ($anpmoving as $anpmove) {
                        if ($anpmove == $anpid) {
                            $lastprogres = 4;
                            // dd($anpmove);
                            break;
                        }
                        $movingindex++;
                    }
                }
                // dd($lastprogres);



                $last_qty = intval($assign[0]->ANP_qty_finish) + intval($qi);
                // dd($last_qty);
                $paramanp = array(
                    'ANP_qty_finish' => $last_qty,
                    'ANP_progres' => $lastprogres,
                    'ANP_modified_by' => auth()->user()->id,
                    'ANP_modified_at' => $now->format('Y-m-d H:i:s'),
                    'ANP_note' => 'Breakdown'
                );
                // dd($paramanp);
                $Assign_model->update_assign($anpid, $paramanp);

                if ($lastprogres == 4) {
                    $qtynew = $datacheck[0]->ANP_qty - $last_qty;
                    // dd($qtynew);
                    if ($qtynew > 0) {
                        $params = array(
                            'ANP_mesin_kode_mesin' => $machinemove[$movingindex],
                            'ANP_data_PRO' => $datacheck[0]->ANP_data_PRO,
                            'ANP_progres' => 0,
                            'ANP_key_IMA' => $datacheck[0]->ANP_key_IMA,
                            'ANP_qty' => $qtynew,
                            'ANP_data_code' => $datacheck[0]->ANP_data_code,
                            'ANP_data_duedate' => $datacheck[0]->ANP_data_duedate,
                            'ANP_data_mhprosess' => $datacheck[0]->ANP_data_mhprosess,
                            'ANP_created_by' => auth()->user()->id,
                            'ANP_created_at' => $time->format('Y-m-d H:i:s'),
                            'ANP_modified_by' => auth()->user()->id,
                            'ANP_modified_at' => $time->format('Y-m-d H:i:s'),
                            'ANP_estimate_startdate' => $datacheck[0]->ANP_estimate_startdate,
                            'ANP_estimate_enddate' => $datacheck[0]->ANP_estimate_enddate,
                            'ANP_urgency' => $datacheck[0]->ANP_urgency,
                            'ANP_qty_finish' => 0,
                        );
                        // dd($params);
                        $Assign_model->add_assign($params);
                    }
                    // dd($params);
                }
                // dd($params);

                $OPpartner = $Activity_job_model->get_user_working_by_jobid($jobdata['Job_id']);
                // dd($OPpartner);
                foreach ($OPpartner as $op) {
                    $params = array('user_IsAssign' => 0);
                    $User_model->update_user($op['user_id'], $params);
                }
            }
            $indexqty++;
        }
        
        $mesinparams = array(
            'mesin_status' => 1,
            'mesin_modified_at' => $time->format('Y-m-d H:i:s'),
            'mesin_modified_by' => auth()->user()->id,
        );
        // dd($mesinparams);

        $Mesin_model->update_mesin($mesin_kode_mesin, $mesinparams);

        // unset($_POST);

        return response()->json(['status' => 'success', 'message' => 'Machine Breakdown berhasil.', 'redirect_url' => url('outstanding-job/')]);

        // return redirect('outstanding-job/');


        // redirect('OutstandingJob/index/');
          
    }

    public function stopJob($anp_id, Request $request){
        $dataJob= TbJob::select('a.Job_id' , 'a.Job_category')
        ->from('tb_job AS a')
        ->where('a.Job_ANP_id',  $anp_id)
        ->whereNull('job_redo_id')
        ->orderBy('a.modified_at','desc')
        ->get()
        ->first();

        // dd($dataJob);
        $jobId = ($dataJob)->Job_id;
        
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

        return response()->json(['status' => 'success', 'message' => 'Job berhasil berhenti.', 'redirect_url' => url('outstanding-job/')]);

   
        // return redirect('outstanding-job/');
    }
}
