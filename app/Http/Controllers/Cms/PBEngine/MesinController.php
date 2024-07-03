<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbDetailMesin;
use App\Models\Table\PBEngine\TbMesin;
use App\Models\Table\PBEngine\TbProcess;
use App\Models\Table\PBEngine\TbSafetyFactorCapacity;
use App\Models\View\PBEngine\VwMesinDetail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Exception;

class MesinController extends Controller
{

    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('machine') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('machine')->r == 1) {

                $safety_model = (new TbSafetyFactorCapacity());
                $sfc = $safety_model->allData();
                // dd($sfc);

                $selectedSafety = $request->input('safety'); // Atau metode lain untuk mendapatkan nilai terpilih

             
                if ($request->ajax()) {
                   // $data = TbMesin::where('mesin_delete_status', 0 )
                   $data = VwMesinDetail::query()
                        ->select(['mesin_kode_mesin','mesin_nama_mesin', 'process_id','process_name','DM_id','sfc_value','sfc_id','mesin_rating','mesin_thickness_min',
                             'mesin_thickness_max','min_requirement','max_requirement','mesin_priority','mesin_status',
                             'mesin_quantity', 'mesin_created_at as created_at', 'mesin_created_by as created_by', 
                             'mesin_modified_at as modified_at', 'mesin_modified_by as modified_by' ])
                        ->latest()
                        ->orderBy('created_at', 'ASC')
                        ->get();
                   
                            
                    return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row) {

                    
                            $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->DM_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm mr-2 editSFC">Ubah</a>';
                            $deleteBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->DM_id.'" data-original-title="Delete" class="btn btn-danger btn-sm mr-2 deleteSFC">Hapus</a>';

                            if ($row->mesin_status == 0) {
                                $statusBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->DM_id.'" data-original-title="Nonaktifkan" class="btn btn-warning btn-sm statusSFC">Nonaktif</a>';
                            } else {
                                $statusBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->DM_id.'" data-original-title="Aktifkan" class="btn btn-success btn-sm statusSFC">Aktif</a>';
                            }

                            // Menggabungkan tombol-tombol edit, delete, dan status dalam satu baris
                            $btn = '<div class="btn-group" role="group">';
                            $btn .= $editBtn;
                            $btn .= $deleteBtn;
                            $btn .= $statusBtn;
                            $btn .= '</div>';

                            return $btn;
                       
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }
                // Non-AJAX request, return the view
                return view('PBEngine.machine.index', compact('sfc', 'selectedSafety'));
                

            } else {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Access Denied!');
        }
    }

    public function search(Request $request){   
        $validasi = Validator::make($request->all(), [
            'mesin_kode_mesin' => [
                'required',
                'unique:tb_mesin,mesin_kode_mesin,NULL'
            ],
            
        ], [
            'mesin_kode_mesin.required' => 'Kode Mesin wajib diisi',
            'mesin_kode_mesin.unique' => 'Kode Mesin sudah terdaftar !',
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
            // 'mesin_kode_mesin' => 'required|unique:tb_mesin,mesin_kode_mesin',
            'mesin_nama_mesin' => 'required',
            'mesin_rating' => 'required',
            // 'mesin_safety_factor_capacity_id' => 'required',
            'mesin_thickness_min' => 'required',
            'mesin_thickness_max' => 'required',
            'min_requirement' => 'required',
            'max_requirement' => 'required',
            'mesin_priority' => 'required',
            'mesin_quantity' => 'required',
            'mesin_kode_mesin' => 'required',
            'mesin_status' => 'required',
            'safety' => 'required',
            'tags' => 'required',
        ], [
            'mesin_kode_mesin.required' => 'Kode Mesin wajib diisi',
            'mesin_nama_mesin.required' => 'Nama Mesin wajib diisi',
            'safety.required' => 'Safety Factor wajib diisi',
            'tags.required' => 'Process wajib diisi',
            'mesin_rating.required' => 'Rating Mesin wajib diisi',
            // 'mesin_safety_factor_capacity_id.required' => 'Safety Factor Capacity wajib diisi',
            'mesin_thickness_min.required' => 'Thickness Min Mesin wajib diisi',
            'mesin_thickness_max.required' => 'Thickness Max Mesin wajib diisi',
            'min_requirement.required' => 'Min Requirement Mesin wajib diisi',
            'max_requirement.required' => 'Max Requirement Mesin wajib diisi',
            'mesin_priority.required' => 'Priority Mesin wajib diisi',
            'mesin_status.required' => 'Status Mesin wajib diisi',
            'mesin_quantity.required' => 'Quantity Mesin wajib diisi',
            // 'mesin_kode_mesin.required' => 'Kode Mesin sudah wajib diisi',
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }else{

            if ($request->has('mesin_kode_mesin')) {
                // Jika ada, berarti ini adalah operasi edit
                $msn = TbMesin::find($request->mesin_kode_mesin);
                if ($msn) {
                    $msn->update([
                        
                        //'mesin_kode_mesin'                  => $request->mesin_kode_mesin,
                        'mesin_id_gm'                       => $request->mesin_id_gm,
                        'mesin_nama_mesin'                  => $request->mesin_nama_mesin, 
                        'mesin_rating'                      => $request->mesin_rating, 
                        'mesin_safety_factor_capacity_id'   => $request->safety, 
                        'mesin_thickness_min'               => $request->mesin_thickness_min, 
                        'mesin_thickness_max'               => $request->mesin_thickness_max,   
                        'min_requirement'                   => $request->min_requirement,
                        'max_requirement'                   => $request->max_requirement,
                        'mesin_priority'                    => $request->mesin_priority ,
                        'mesin_quantity'                    => $request->mesin_quantity ,
                        'mesin_status'                      => $request->mesin_status, 
                        'mesin_modified_by'                  => auth()->user()->id,
                        'mesin_modified_at'                  => Carbon::now()
                    ]);
                    TbDetailMesin::where('DM_mesin_kode_mesin', $request->mesin_kode_mesin)->delete();

                    foreach ($request->tags as $inputan) {
                        TbDetailMesin::create([
                            'DM_mesin_kode_mesin'  => $request->mesin_kode_mesin,
                            'DM_process_id'        => $inputan,
                            'DM_created_by'        => auth()->user()->id,
                            'DM_created_at'        => Carbon::now()
                            // Tambahkan kolom-kolom lain yang diperlukan di sini
                        ]);
                    }
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Diubah!',
                        'data'    => $msn  
                    ]);
                 
            } else {
                
                // Jika tidak ada, berarti ini adalah operasi simpan baru
                $sfc = TbMesin::Create(
                    
                    [
                        
                        'mesin_kode_mesin'                  => $request->mesin_kode_mesin,
                        'mesin_id_gm'                       => $request->mesin_id_gm,
                        'mesin_nama_mesin'                  => $request->mesin_nama_mesin, 
                        'mesin_rating'                      => $request->mesin_rating, 
                        'mesin_safety_factor_capacity_id'   => $request->safety, 
                        'mesin_thickness_min'               => $request->mesin_thickness_min, 
                        'mesin_thickness_max'               => $request->mesin_thickness_max,   
                        'min_requirement'                   => $request->min_requirement,
                        'max_requirement'                   => $request->max_requirement,
                        'mesin_priority'                    => $request->mesin_priority ,
                        'mesin_quantity'                    => $request->mesin_quantity ,
                        'mesin_status'                      => $request->mesin_status, 
                        'mesin_created_by'                  => auth()->user()->id,
                        'mesin_created_at'                  => Carbon::now()
                    ]
                );
                foreach ($request->tags as $inputan) {
                    TbDetailMesin::create([
                        'DM_mesin_kode_mesin'  => $request->mesin_kode_mesin,
                        'DM_process_id'        => $inputan,
                        'DM_created_by'        => auth()->user()->id,
                        'DM_created_at'        => Carbon::now(),
                        'DM_modified_by'        => auth()->user()->id,
                        'DM_modified_at'        => Carbon::now()
                        // Tambahkan kolom-kolom lain yang diperlukan di sini
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                    'data'    => $sfc  
                ]); 
            }}
        
        }
    
    }
    
    public function edit($DM_id)
    {
        // Ambil detail proses dari TbDetailMesin
        $DM = TbDetailMesin::find($DM_id);
  
        // Periksa apakah data ditemukan sebelum melanjutkan
        if (!$DM) {
        // Jika data tidak ditemukan, kirimkan respons JSON dengan pesan kesalahan
        return response()->json(['error' => 'Data not found'], 404);
        }
   
   
        //Ambil informasi pengguna dari VwMesinDetail
        $mesinData = VwMesinDetail::where('DM_id', $DM->DM_id)->first();

        // Gabungkan data dari kedua sumber
        $data = [
                'DM_id'              => $DM->DM_id,
                'mesin_kode_mesin'   => $mesinData->mesin_kode_mesin,
                'sfc_value'          => $mesinData->sfc_value,
                'sfc_id'             => $mesinData->sfc_id,
                'mesin_nama_mesin'   => $mesinData->mesin_nama_mesin,
                'process_name'       => $mesinData->process_name,
                'process_id'         => $mesinData->process_id,
                'mesin_rating'       => $mesinData->mesin_rating,
                'mesin_thickness_min'=> $mesinData->mesin_thickness_min,
                'mesin_thickness_max'=> $mesinData->mesin_thickness_max,
                'min_requirement'    => $mesinData->min_requirement,
                'max_requirement'    => $mesinData->max_requirement,
                'mesin_priority'     => $mesinData->mesin_priority,
                'mesin_status'       => $mesinData->mesin_status,
                'mesin_quantity'     => $mesinData->mesin_quantity,
        ];
        // Kembalikan data dalam respons JSON
        return response()->json($data);
    }
        
    public function destroy($id)
    {
        $msn = TbDetailMesin::find($id);

        if (!$msn) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }

        // Ambil data mesin dari view mesin detail
        $mesinData = VwMesinDetail::where('DM_id', $msn->DM_id)->first();

        // Perbarui nilai mesin_delete_status menjadi 1 pada tabel tb_mesin
        $mesin = TbMesin::find($mesinData->mesin_kode_mesin);
        $mesin->mesin_delete_status = 1;
        $mesin->mesin_modified_by = auth()->user()->id;
        $mesin->mesin_modified_at = now();
        $mesin->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
            'data'    => $msn  
        ]);
    }

    public function update($id)
    {
        $msn = TbDetailMesin::find($id);

        if (!$msn) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }
        // Ambil data mesin dari view mesin detail
        $mesinData = VwMesinDetail::where('DM_id', $msn->DM_id)->first();

        // Perbarui nilai mesin_delete_status menjadi 1 pada tabel tb_mesin
        $mesin = TbMesin::find($mesinData->mesin_kode_mesin);

        //Memeriksa jika status mesin adalah 1, maka ubah menjadi 0 dan sebaliknya
        $mesin->mesin_status = ($mesin->mesin_status == 1) ? 0 : 1;
        $mesin->mesin_modified_by = auth()->user()->id;
        $mesin->mesin_modified_at = now();
        $mesin->save();

        return response()->json([
            'success' => true,
            'message' => 'Mesin Berhasil Dinonaktifkan.',
            'data'    => $msn  
        ]);

    }

    public function autocomplete(Request $request)
    {
        $data = TbMesin::select("mesin_kode_mesin as kode", "mesin_kode_mesin")
        ->where('mesin_kode_mesin', 'LIKE', $request->get('mesin_kode_mesin'). '%')
        ->where('mesin_delete_status', 0) // Hanya ambil data dengan sfc_delete_status = 0
        ->get();

        return response()->json($data);
    }

    public function getProcess(Request $request){
        $tags=[];
        if ($search=$request->name) {
            $tags=TbProcess::where('process_name','LIKE',"%$search%")->get();
        }
        return response()->json($tags);
    }

    public function m_list_machine_breakdown()
    {

        $mesinModel = new TbMesin();
        $mesin = $mesinModel->get_breakdown_mesin_list();
    
        // Mengonversi objek stdClass menjadi array
        $mesinArray = Collection::wrap($mesin)->toArray();

        return view('PBEngine.machine.MesinListBreakdown', compact('mesinArray'));
       
    }
    
}
