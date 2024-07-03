<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbReasonPause;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ReasonPauseController extends Controller
{

    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('reason-pause') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }


    public function index(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('reason-pause')->r == 1) {

                if ($request->ajax()) {
                    $data = TbReasonPause::where('RP_status', 0)
                            ->select(['RP_id', 'RP_name', 'RP_created_at as created_at', 'RP_created_by as created_by', 'RP_modified_at as updated_at', 'RP_modified_by as updated_by'])
                            ->latest()
                            ->get();
                    return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row) {

                            // $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->RP_id.'" title="Edit" class="edit btn btn-warning btn-sm editSFC" style="height: 30px; width: 35px;"><i class="icon mdi mdi-edit"></i></a>';
                            // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->RP_id.'" title="Delete" class="btn btn-danger btn-sm deleteSFC" style="height: 30px; width: 35px;"><i class="icon mdi mdi-delete"></i></a>';
        
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->RP_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editSFC">Ubah</a>';
                            $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->RP_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSFC">Hapus</a>';
        
                            return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }
                
                // Non-AJAX request, return the view
                return view('PBEngine.reason-pause.index');
                

            } else {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Access Denied!');
        }
    }

    public function search(Request $request)
    {   
        $validasi = Validator::make($request->all(), [
            'RP_name' => [
                'required',
                'unique:tb_reason_pause,RP_name,NULL,RP_id,RP_status,0',
            ],
        ], [
            'RP_name.required' => 'Reason Pause wajib diisi',
            'RP_name.unique' => 'Reason Pause sudah ada',
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }

        // Jika validasi berhasil, kembalikan pesan sukses
        return response()->json([
            'success' => true,
            'message' => 'Validasi Berhasil',
        ]);
    }

    public function store(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'RP_name' => [
                'required',
                'unique:tb_reason_pause,RP_name,NULL,RP_id,RP_status,0',
            ],
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }else{

            if ($request->has('RP_id')) {
                // Jika ada, berarti ini adalah operasi edit
                $rp = TbReasonPause::find($request->RP_id);
            
                if ($rp) {
                    $rp->update([
                        'RP_name' => $request->RP_name,
                        'RP_modified_by' => auth()->user()->id,
                        'RP_modified_at' => Carbon::now()
                    ]);
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Diubah!',
                        'data'    => $rp  
                    ]);
                 
            } else {
                // Jika tidak ada, berarti ini adalah operasi simpan baru
                $sfc = TbReasonPause::updateOrCreate(
                    ['RP_id' => $request->RP_id],
                    [
                        'RP_name' => $request->RP_name,
                        'RP_created_by' => auth()->user()->id,
                        'RP_created_at' => Carbon::now()
                    ]
                );
            
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                    'data'    => $sfc  
                ]); 
            }}
        
        }
        
    }

    public function edit($RP_id)
    {
        $rp = TbReasonPause::find($RP_id);
        return response()->json($rp);
    }

    public function destroy($id)
    {

        $rp = TbReasonPause::find($id);

        if (!$rp) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }
    
        // Ubah nilai attribute sfc_delete_status menjadi 1
        $rp->RP_status = 1;
        // Set nilai sfc_modified_at ke waktu sekarang
        $rp->RP_modified_by = auth()->user()->id;
        $rp->RP_modified_at = now();

        // Sementara nonaktifkan pengaturan tanggal otomatis
        $rp->timestamps = false;
        $rp->save();
      
       
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus.',
            'data'    => $rp  
        ]); 
    }


    public function autocomplete(Request $request)
    {
        $data = TbReasonPause::select("RP_name as value", "RP_id")
        ->where('RP_name', 'LIKE', '%'. $request->get('RP_name'). '%')
        ->where('RP_status', 0) // Hanya ambil data dengan RP_status = 0
        ->get();

        return response()->json($data);
    }

    
}
