<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbActivityJob;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbJob;
use App\Models\Table\PBEngine\TbJobIssue;
use App\Models\Table\PBEngine\TbMappingImageComponent;
use Illuminate\Http\Request;

class JobIssueController extends Controller
{

    public function issue_during_production($anp_id, Request $request){
        $data['anp_id'] = $anp_id;

        //dd($anp_id);
        $TbJob = new TbJob();
        $TbJobIssue = new TbJobIssue();

        $dataJob = $TbJob->get_job_by_anp($anp_id);

        if(!empty($dataJob)){
            $jobId = optional($dataJob)->Job_id;

        
            if(empty($jobId)){
                $jobId = 0;
            }

            $aj = TbActivityJob::select ('tb_activity_job.*' , 'j.RP_name' , 'op.user_nama')
            ->leftJoin('tb_detail_reason_pause as rp', 'rp.DRP_aj_id', '=', 'tb_activity_job.aj_id')
            ->leftJoin('tb_reason_pause as j', 'j.RP_id', '=', 'rp.DRP_rp_id')
            ->leftJoin('tb_user as op','op.user_employe_number', '=', 'tb_activity_job.modified_by')
            ->orderBy('created_at', 'desc')
            ->where('tb_activity_job.aj_job_id', $jobId)
            ->first();

            $data['history'] = array();

            $history = $TbJobIssue->job_issue_by_anp($anp_id);


            $data['history'] = $history;

            $data['ANP'] = TbAssign::select('tb_assign_nesting_programmer.*', 'b.*', 'd.process_name', 'c.customer_name')
            ->join('tb_mesin as b', 'b.mesin_kode_mesin', '=', 'ANP_mesin_kode_mesin')
            ->join('tb_detail_mesin as dm', 'dm.DM_mesin_kode_mesin','=','b.mesin_kode_mesin')
            ->join('tb_process as d', 'd.proses_id', '=', 'dm.DM_process_id')
            ->leftJoin('tb_mapping_pro_customer as mpc', 'mpc.mapping_pro','=','ANP_data_PRO')
            ->leftJoin('tb_customer as c', 'c.customer_id', '=', 'mpc.mapping_customer_id')
            ->leftJoin('tb_base_table as bt', 'bt.mppid', '=', 'ANP_key_IMA')
            ->where('ANP_id', $anp_id)
            ->first();

            $ANPkey = $data['ANP']->ANP_key_IMA ;

            $data['item'] = array();

            $dataIMA= TbBase::where('mppid', $ANPkey)
            ->first();

            $imagestatus = 0;

            if($dataIMA){

                $PnComp = $dataIMA->PartNumberComponent;
                $PN  = $dataIMA->PN;

                $image = TbMappingImageComponent::where('MIC_PN_component',$PnComp)
                ->where('MIC_PN_product', $PN)
                ->get();

                
                if(empty($image)){
                    $imagestatus = 1;
                }
                else{
                    $imagestatus = 2;
                }
            }

            $data['ANP_id'] = $anp_id;
            //dd($data['ANP']);

            return view('PBEngine.job.issue-during-production',
            [
            'data'=>$data,
            'imagestatus'=>$imagestatus,
            'history'=>$history,
            'dataIMA'=>$dataIMA,
            'anp_id'=>$anp_id

            ]
              
        );


        }


    }

    public function addIssue($anp_id, Request $request){

        $request->validate([
            'issue_description' => 'required|string|max:255',
            'issue_start' => 'required|date'
        ]);

        TbJobIssue::create([
            'issue_description' => $request->input('issue_description'),
            'issue_start' => $request->input('issue_start'),
            'issue_anp_id' => $anp_id,
            'issue_delete_status' => 0,
            'created_by' => auth()->user()->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'modified_by' => auth()->user()->id,
            'modified_at' => now()->format('Y-m-d H:i:s')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data issue job berhasil ditambahkan.',
            'redirect_url' => url('issue-during-production/'.$anp_id)
        ]);

        // return redirect('issue-during-production/'.$anp_id);
    }

    public function finishIssue($issue_id, $anp_id, Request $request){

        TbJobIssue::where('issue_id', $issue_id)->update([
            'issue_finish' => $request->input('issue_finish'),
            'modified_by' => auth()->user()->id,
            'modified_at' => now()->format('Y-m-d H:i:s')
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Data issue job berhasil diselesaikan.',
            'redirect_url' => url('issue-during-production/'.$anp_id)
        ]);

        
        // return redirect('issue-during-production/'.$anp_id);
    }

    public function deleteIssue($issue_id, $anp_id)
    {

        TbJobIssue::where('issue_id', $issue_id)->update([
            'issue_delete_status' => 1 ,
            'modified_by' => auth()->user()->id,
            'modified_at' => now()->format('Y-m-d H:i:s')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data issue job berhasil dihapus.',
            'redirect_url' => url('issue-during-production/'.$anp_id)
        ]);

        // return redirect('issue-during-production/'.$anp_id);
          
    }

    public function showAddIssue(Request $request, $anp_id){
        return view('PBEngine.issue.issue-create',
        [
        
        'anp_id'=>$anp_id
        ]);
    }

    public function getFinishIssue(Request $request, $issue_id, $anp_id){
        $history = TbJobIssue::where('issue_id', $issue_id)
            ->first();

        
        return view('PBEngine.issue.issue-finish',
        [
        'issue_id'=>$issue_id,
        'anp_id'=>$anp_id,
        'history'=>$history
        ]);
    }

    

    public function getDeleteIssue(Request $request, $issue_id, $anp_id){
        $history = TbJobIssue::where('issue_id', $issue_id)
            ->first();

        
        return view('PBEngine.issue.issue-delete',
        [
        'issue_id'=>$issue_id,
        'anp_id'=>$anp_id,
        'history'=>$history
        ]);
    }


}
