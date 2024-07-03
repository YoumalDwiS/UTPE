<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\MstApps;
use App\Models\Table\PBEngine\TbAssign;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbDetailUserProcessGroup;
use App\Models\Table\PBEngine\TbMesin;
use App\Models\Table\PBEngine\TbUser;
use App\Models\User;
use App\Models\View\PBEngine\VwUserProcessGroup;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->where('accessed_app', 26)->first();

        $apps = MstApps::where('id', Auth::user()->accessed_app)->first();
        if (!empty($user)) {
            $data = array();

            $role = $this->GetUserRoleGroup();
           
            switch ($role) {
                case "Operator PBEngine":

                   // Hitung jumlah pekerjaan yang sedang berlangsung
                    $ongoingJobsCount = TbAssign::countOngoingJobs();
                    
                    // Hitung jumlah data untuk status tertentu
                    $ForFinish = TbBase::getRowAmountData(4);

                    // Hitung jumlah mesin dengan kondisi tertentu
                    $machineCount = TbMesin::countMachines();

                    // Kirim data ke view
                    $data['ongoingJobsCount'] = $ongoingJobsCount;
                    $data['ForFinish'] = $ForFinish; // Kirim jumlah data untuk finish ke view
                    $data['machineCount'] = $machineCount; // Kirim jumlah mesin ke view

                    $view = "PBEngine/homepage/operator";

                    
                    break;
                case "Nesting Planner PBEngine":

                     // Hitung jumlah pekerjaan yang sedang berlangsung
                    $ongoingJobsCount = TbAssign::countOngoingJobs();
                    
                    // Hitung jumlah data untuk status tertentu
                    $ForFinish = TbBase::getRowAmountData(4);

                    // Hitung jumlah mesin dengan kondisi tertentu
                    $machineCount = TbMesin::countMachines();

                    // Kirim data ke view
                    $data['ongoingJobsCount'] = $ongoingJobsCount;
                    $data['ForFinish'] = $ForFinish; // Kirim jumlah data untuk finish ke view
                    $data['machineCount'] = $machineCount; // Kirim jumlah mesin ke view

                    $view = "PBEngine/homepage/nesting";
                    break;
                case "Group Leader PBEngine":

                    // Hitung jumlah pekerjaan yang sedang berlangsung
                    $ongoingJobsCount = TbAssign::countOngoingJobs();
                        
                    // Hitung jumlah data untuk status tertentu
                    $ForFinish = TbBase::getRowAmountData(4);

                    // Hitung jumlah mesin dengan kondisi tertentu
                    $machineCount = TbMesin::countMachines();

                    // Kirim data ke view
                    $data['ongoingJobsCount'] = $ongoingJobsCount;
                    $data['ForFinish'] = $ForFinish; // Kirim jumlah data untuk finish ke view
                    $data['machineCount'] = $machineCount; // Kirim jumlah mesin ke view

                    $view = "PBEngine/homepage/gl";
                    break;
                case "Matrol PBEngine":
                    $view = "PBEngine/homepage/matrol";
                    break;
                case "Supervisor PBEngine":
                    $view = "PBEngine/homepage/supervisor";
                    break;
                case "Admin PBEngine":
                    $view = "PBEngine/homepage/admin";
                    break;
                default:
                    $view = "PBEngine/homepage/operator";
                    break;
            }

            // dd($data);
            // return view($view)->with([
            //     'data' => $data
            // ]);
            return view($view, $data);
        } else {
            if (empty($apps)) {
                return redirect('pb_homepage');
            } else {

                if ($this->PermissionMenu($apps->link) == 0) {
                    User::where('id', Auth::user()->id)
                        ->update(
                            [
                                'accessed_app' => NULL,
                            ]
                        );

                    return redirect('welcome')->with('err_message', 'Akses Ditolak!');
                }
                return redirect($apps->link);
            }
        }
        try {
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function getMachine(Request $request)
    {
        $tags = [];
        
        // query yg benar
        if ($id = $request->id) {
            $machines =  TbMesin::join('pbengine6.tb_detail_mesin as dm', 'tb_mesin.mesin_kode_mesin', '=', 'dm.DM_mesin_kode_mesin')
                ->join('pbengine6.tb_detail_user_process_group as dp', 'dm.DM_process_id', '=', 'dp.DOPG_Process_id')
                ->join('satria3.users as u', 'dp.DOPG_user_id', '=', 'u.id')
                // ->select('tb_mesin.mesin_kode_mesin', 'tb_mesin.mesin_nama_mesin')
                ->where('u.id', $id)
                ->get();
        }

        return response()->json($machines);
    }

    
}
