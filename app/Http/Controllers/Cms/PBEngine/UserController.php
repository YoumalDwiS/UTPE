<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbDetailUserProcessGroup;
use App\Models\Table\PBEngine\TbProcess;
use App\Models\View\PBEngine\VwUserProcessGroup;
use App\Models\View\VwUserRoleGroup;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{

    
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('user') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }


    public function index(Request $request)
    {


        try {
            if ($this->PermissionActionMenu('user')->r == 1) {

                if ($request->ajax()) {
                    $data = VwUserProcessGroup::query()
                        ->select(['user', 'username','role_name','process_names','proses_ids','email','DOPG_id', 'DOPG_Created_at as created_at', 'DOPG_Created_by as created_by', 'DOPG_Modified_at as updated_at', 'DOPG_MOdified_by as updated_by'])
                        ->latest()
                        ->get();
                    return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row) {
                            // $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->DOPG_id.'" title="Edit" class="edit btn btn-warning btn-sm editUser" style="height: 30px; width: 35px;"><i class="icon mdi mdi-edit"></i></a>';

                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->DOPG_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Ubah</a>';
                            return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                    }
                
                // Non-AJAX request, return the view
                return view('PBEngine.user.index');
                

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
            'email' => [
                'required',
                'unique:VwUserProcessGroup,email,NULL,user'
            ],
        ], [
            'email.required' => 'Email atau NRP wajib diisi',
            'email.unique' => 'Email atau NRP sudah terdaftar'

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
        $success = true;
        $message = 'Data Berhasil Disimpan!';
        $dopgData = [];

        // coba3, BISA CUMAN HANYA HAPUS 1 DATA(udah solve)

        // Hapus semua entri terkait dengan DOPG_id yang ada sebelumnya
        $deleteResult = TbDetailUserProcessGroup::where('DOPG_user_id', $request->DOPG_user_id)->delete();

        // Cek apakah penghapusan berhasil dilakukan
        if ($deleteResult === false) {
        $success = false;
        $message = 'Gagal menghapus data sebelumnya!';
        } else {
        // Penghapusan berhasil, lanjutkan dengan menambahkan entri baru
        // Inisialisasi variabel success dan message
        $success = true;
        $message = 'Data Berhasil Disimpan!';
        $dopgData = [];

        // Cek apakah ada proses yang dipilih
        if (is_array($request->DOPG_Process_id) && !empty($request->DOPG_Process_id)) {
        // Tambahkan entri baru
        foreach ($request->DOPG_Process_id as $processId) {
        $dopg = TbDetailUserProcessGroup::create([
        'DOPG_id' => $request->DOPG_id,
        'DOPG_user_id' => $request->DOPG_user_id,
        'DOPG_Process_id' => $processId,
        'DOPG_Created_by' => auth()->user()->id,
        'DOPG_Created_at' => now()
        ]);

        // Periksa apakah entri berhasil disimpan
        if (!$dopg) {
        $success = false;
        $message = 'Gagal menyimpan data!';
        break;
        }

        // Tambahkan data yang berhasil disimpan ke dalam array
        $dopgData[] = $dopg;
        }
        }

        // Kembalikan respons berdasarkan keberhasilan operasi
        if ($success) {
        return response()->json([
        'success' => $success,
        'message' => $message,
        'data'    => $dopgData
        ]); 
        }
        }

        // Jika ada kesalahan, kembalikan respons dengan pesan error
        return response()->json([
        'success' => $success,
        'message' => $message,
        ]);


    }

    public function edit($DOPG_id)
    {

        // Ambil detail proses dari TbDetailUserProcessGroup
        $dopg = TbDetailUserProcessGroup::find($DOPG_id);

        // Periksa apakah data ditemukan sebelum melanjutkan
        if (!$dopg) {
            // Jika data tidak ditemukan, kirimkan respons JSON dengan pesan kesalahan
        return response()->json(['error' => 'Data not found'], 404);
        }
    
    
        // Ambil informasi pengguna dari VwUserProcessGroup
        $userData = VwUserProcessGroup::where('user', $dopg->DOPG_user_id)->first();

        // Gabungkan data dari kedua sumber
        $data = [
            'DOPG_id' => $dopg->DOPG_id,
            'DOPG_user_id' => $dopg->DOPG_user_id,
            'proses_ids' => $userData->proses_ids,
            'email' => $userData->email,
            'username' => $userData->username,
            'role_name' => $userData->role_name,
            'process_names' => $userData->process_names
        //    Jika perlu, tambahkan data lain dari VwUserProcessGroup yang diperlukan
        ];

        // Kembalikan data dalam respons JSON
        return response()->json($data);
    }

    public function destroy($id)
    {
        $dopg = TbDetailUserProcessGroup::find($id);

        if (!$dopg) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }
    
        // Ubah nilai attribute sfc_delete_status menjadi 1
        $dopg->sfc_delete_status = 1;
        // Set nilai sfc_modified_at ke waktu sekarang
        $dopg->DOPG_Modified_by = auth()->user()->id;
        $dopg->DOPG_Modified_at = now();

        // Sementara nonaktifkan pengaturan tanggal otomatis
        $dopg->timestamps = false;
        $dopg->save();
      
       
        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
            'data'    => $dopg  
        ]); 
    }

    public function autocomplete(Request $request)
    {
        $data = VwUserRoleGroup::select("email as value", "user")
        ->where('apps', '=', 26)
        ->where('role_name', '!=', 'Admin PBEngine')
        ->where('email', 'LIKE', '%' . $request->get('search') . '%')
        ->get();
        
        return response()->json($data);

    }

    public function getUserData(Request $request)
    {
        // Mengambil data pengguna berdasarkan email
        $userData = VwUserRoleGroup::where('email', $request->email);

            $validasi = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'unique:vw_user_process_group,email,NULL,user'
                ],
            ], [
                'email.required' => 'Email atau NRP wajib diisi',
                'email.unique' => 'Email atau NRP sudah terdaftar'

            ]);

        
            // Menambahkan kondisi untuk apps=26 dan role_name bukan 'AdminPBEngine'
            $userData->where('apps', 26)->where('role_name', '!=', 'Admin PBEngine');

            // Mendapatkan hasil pertama
            $userData = $userData->first();


            // Menentukan nilai IH/OH
            $ihohValue = preg_match('/^\d+$/', $request->email) ? 'IH' : 'OH';

            if ($validasi->fails()) {
                return response()->json(['errors' => $validasi->errors()]);
            }

            // Mengembalikan data pengguna beserta nilai IH/OH
            return response()->json([
                'user' => $userData->user,
                'role_name' => $userData->role_name,
                'ihoh' => $ihohValue
            ]);
    }

    public function getProcess(Request $request)
    {
        $tags=[];
        
        if ($search=$request->name) {
            $tags=TbProcess::where('process_name','LIKE',"%$search%")->get();
        }
        return response()->json($tags);
    }

    public function getProcessEdit(Request $request)
    {
        $tags_edit=[];
        
        if ($search=$request->name) {
            $tags_edit=TbProcess::where('process_name','LIKE',"%$search%")->get();
        }
        return response()->json($tags_edit);
    }


}


