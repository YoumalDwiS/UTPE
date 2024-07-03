<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbSafetyFactorCapacity;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class SafetyFactorCapacityController extends Controller
{

    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('safety-factor-capacity') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    //Index
    public function index(Request $request)
    {
        try{
            if($this->PermissionActionMenu('safety-factor-capacity')->r = 1) {
     
            if ($request->ajax()) {
                $data = TbSafetyFactorCapacity::where('sfc_delete_status', 0)
                        ->select(['sfc_id', 'sfc_value', 'sfc_create_at as created_at', 'sfc_create_by as created_by', 'sfc_modified_at as updated_at', 'sfc_modified_by as updated_by'])
                        ->latest()
                        ->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        // $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->sfc_id.'" title="Edit" class="edit btn btn-warning btn-sm editSFC" style="height: 30px; width: 35px;   margin-right: 5px; margin-bottom: 5px;"><i class="icon mdi mdi-edit" style="center"></i></a>';
                        // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->sfc_id.'" title="Delete" class="btn btn-danger btn-sm deleteSFC" style="height: 30px; width: 35px;  margin-right: 5px; margin-bottom: 5px;"><i class="icon mdi mdi-delete"></i></a>';

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->sfc_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editSFC">Ubah</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->sfc_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSFC">Hapus</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        
        // Non-AJAX request, return the view
        return view('PBEngine.safety-factor-capacity.index');
        
        } else {
            return redirect()->back()->with('err_message', 'Access Denied!');
            }
        }catch (Exception $e){
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
           
        }
    }

    public function search(Request $request)
    {   
        $validasi = Validator::make($request->all(), [
            'sfc_value' => [
                'required',
                'unique:tb_safety_factor_capacity,sfc_value,NULL,sfc_id,sfc_delete_status,0',
                'numeric',
                'max:1'
            ],
        ], [
            'sfc_value.required' => 'Safety Factor Capacity wajib diisi',
            'sfc_value.unique' => 'Safety Factor Capacity Value Already Registered',
            'sfc_value.max' => 'Maximal Value 1'
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

    //Create & Edit (Post Data)
    public function store(Request $request)
    {
        try{
            if($this->PermissionActionMenu('safety-factor-capacity')->c = 1) {

        $validasi = Validator::make($request->all(), [
            'sfc_value' => [
                'required',
                'unique:tb_safety_factor_capacity,sfc_value,NULL,sfc_id,sfc_delete_status,0',
                'numeric',
                'max:1'
            ],
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }else{

            if ($request->has('sfc_id')) {
                // Jika ada, berarti ini adalah operasi edit
                $sfc = TbSafetyFactorCapacity::find($request->sfc_id);
            
                if ($sfc) {
                    $sfc->update([
                        'sfc_value' => $request->sfc_value,
                        'sfc_modified_by' => auth()->user()->id,
                        'sfc_modified_at' => Carbon::now()
                    ]);
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Diubah!',
                        'data'    => $sfc  
                    ]);
                 
            } else {
                // Jika tidak ada, berarti ini adalah operasi simpan baru
                $sfc = TbSafetyFactorCapacity::updateOrCreate(
                    ['sfc_id' => $request->sfc_id],
                    [
                        'sfc_value' => $request->sfc_value,
                        'sfc_create_by' => auth()->user()->id,
                        'sfc_create_at' => Carbon::now()
                    ]
                );
            
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                    'data'    => $sfc  
                ]); 
            }}


        
        }

        } else {
                return response()->json([
                    'message' => 'Akses Ditolak!',
                    'data' => null
                ], 404);
                // return response()->with('err_message', 'Akses Ditolak!');
            }
        }catch (Exception $e){
            $this->ErrorLog($e);
            // return redirect()->back()->with('err_message', 'Error Request, Exception Error');
            return response()->json([
                'message' => 'Error Request',
                'data' => null
            ], 404);
        }
    
    }

    //Call Form Edit
    public function edit($sfc_id)
    {
        try{
            if($this->PermissionActionMenu('safety-factor-capacity')->u = 1) {

        $sfc = TbSafetyFactorCapacity::find($sfc_id);
        return response()->json($sfc);

        } else {
                return response()->json([
                    'message' => 'Akses Ditolak!',
                    'data' => null
                ], 404);
                // return response()->with('err_message', 'Akses Ditolak!');
            }
        }catch (Exception $e){
            $this->ErrorLog($e);
            // return redirect()->back()->with('err_message', 'Error Request, Exception Error');
            return response()->json([
                'message' => 'Error Request',
                'data' => null
            ], 404);
        }
    }

    //Delete Data
    public function destroy($id)
    {
        try{
            if($this->PermissionActionMenu('safety-factor-capacity')->d = 1) {
        $sfc = TbSafetyFactorCapacity::find($id);

        if (!$sfc) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }
    
        // Ubah nilai attribute sfc_delete_status menjadi 1
        $sfc->sfc_delete_status = 1;
        // Set nilai sfc_modified_at ke waktu sekarang
        $sfc->sfc_modified_by = auth()->user()->id;
        $sfc->sfc_modified_at = now();

        // Sementara nonaktifkan pengaturan tanggal otomatis
        $sfc->timestamps = false;
        $sfc->save();
        
       
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
            'data'    => $sfc  
        ]);
        
        } else {
                return response()->json([
                    'message' => 'Akses Ditolak!',
                    'data' => null
                ], 404);
                // return response()->with('err_message', 'Akses Ditolak!');
            }
        }catch (Exception $e){
            $this->ErrorLog($e);
            // return redirect()->back()->with('err_message', 'Error Request, Exception Error');
            return response()->json([
                'message' => 'Error Request',
                'data' => null
            ], 404);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = TbSafetyFactorCapacity::select("sfc_value as value", "sfc_id")
        ->where('sfc_value', 'LIKE', $request->get('sfc_value'). '%')
        ->where('sfc_delete_status', 0) // Hanya ambil data dengan sfc_delete_status = 0
        ->get();

        return response()->json($data);

  
    }
}
