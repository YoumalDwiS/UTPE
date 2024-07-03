<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbCustomer;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class CustomerController extends Controller
{

    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('customer') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {

        try {
            if ($this->PermissionActionMenu('customer')->r == 1) {

                 if ($request->ajax()) {
                    $data = TbCustomer::where('customer_delete_status', 0)
                            ->select(['customer_id', 'customer_name', 'customer_created_at as created_at', 'customer_created_by as created_by', 'customer_modified_at as updated_at', 'customer_modified_by as updated_by'])
                            ->latest()
                            ->get();
                    return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row) {

                            // $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->customer_id.'" title="Edit" class="edit btn btn-warning btn-sm editCustomer" style="height: 30px; width: 35px;"><i class="icon mdi mdi-edit"></i></a>';
                            // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->customer_id.'" title="Delete" class="btn btn-danger btn-sm deleteCustomer" style="height: 30px; width: 35px;"><i class="icon mdi mdi-delete"></i></a>';

                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->customer_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editCustomer">Ubah</a>';
                            $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->customer_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteCustomer">Hapus</a>';

                            return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }
                
                // Non-AJAX request, return the view
                return view('PBEngine.customer.index');
                

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
            'customer_name' => [
                'required',
                'unique:tb_customer,customer_name,NULL,customer_id,customer_delete_status,0'
            ],
        ], [
            'customer_name.required' => 'Data customer wajib diisi',
            'customer_name.unique' => 'Data customer tersebut sudah terdaftar'
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
            'customer_name' => [
                'required',
                'unique:tb_customer,customer_name,NULL,customer_id,customer_delete_status,0'
            ],
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }else{

            if ($request->has('customer_id')) {
                // Jika ada, berarti ini adalah operasi edit
                $customer = TbCustomer::find($request->customer_id);
            
                if ($customer) {
                    $customer->update([
                        'customer_name' => $request->customer_name,
                        'customer_modified_by' => auth()->user()->id,
                        'customer_modified_at' => Carbon::now()
                    ]);
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Diubah!',
                        'data'    => $customer  
                    ]);
                 
            } else {
                // Jika tidak ada, berarti ini adalah operasi simpan baru
                $customer = TbCustomer::updateOrCreate(
                    ['customer_id' => $request->customer_id],
                    [
                        'customer_name' => $request->customer_name,
                        'customer_created_by' => auth()->user()->id,
                        'customer_created_at' => Carbon::now()
                    ]
                );
            
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                    'data'    => $customer  
                ]); 
            }}
        
        }
    
    }

    public function edit($customer_id)
    {
        $customer = TbCustomer::find($customer_id);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        $customer = TbCustomer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }
    
        // Ubah nilai attribute customer_delete_status menjadi 1
        $customer->customer_delete_status = 1;
        // Set nilai customer_modified_at ke waktu sekarang
        $customer->customer_modified_by = auth()->user()->id;
        $customer->customer_modified_at = now();

        // Sementara nonaktifkan pengaturan tanggal otomatis
        $customer->timestamps = false;
            $customer->save();
        
        
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
                'data'    => $customer  
            ]); 
    }
}
