<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbComponent;
use Illuminate\Http\Request;
use App\Models\Table\PBEngine\TbMappingImageComponent;
use App\Models\Table\PBEngine\TbMatrixProdComp;
use App\Models\Table\PBEngine\TbProduct;
use App\Models\View\PBEngine\VwCompProd;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class MappingImageComponentController extends Controller
{

    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('mapping-image') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {

        try {
            if ($this->PermissionActionMenu('mapping-image')->r == 1) {

                if ($request->ajax()) {
                    $data = TbMappingImageComponent::where('MIC_status_delete', 0)
                        ->where('MIC_Status_Aktifasi', 0)
                        ->select(['MIC_id','MIC_ComponentID_IMA' , 'MIC_component_name','MIC_PN_component','MIC_Modification_no','MIC_Drawing','MIC_ProductID_IMA' ,'MIC_product_name','MIC_PN_product', 'MIC_created as created_at', 'MIC_createdby as created_by', 'MIC_modified as updated_at', 'MIC_modifiedby as updated_by'])
                        ->latest()
                        ->get();
                
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row) {

                            // $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" title="Edit" class="edit btn btn-warning btn-sm editMIC" style="height: 30px; width: 35px; margin-right: 5px; margin-bottom: 5px;"><i class="icon mdi mdi-edit"></i></a>';
                            // $historyBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" title="History" class="edit btn btn-success btn-sm historyMIC" style="height: 30px; width: 35px; margin-right: 5px; margin-bottom: 5px;"><i class="icon mdi mdi-accounts-list-alt"></i></a>';
                            // $deleteBtn = ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" title="Delete" class="btn btn-danger btn-sm deleteMIC" style="height: 30px; width: 35px; margin-right: 5px; margin-bottom: 5px;"><i class="icon mdi mdi-delete"></i></a>';
        
                            // $btn = '<div class="btn-group" role="group">' . $editBtn . '<span style="margin-right: 5px;"></span>' . $deleteBtn . '</div>';
                            // $btn .= '<div class="mt-2">' . $historyBtn.'</div>';
                            $btn = '<div class="btn-group">';
$btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm mr-1 editMIC">Ubah</a>';
$btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="History" class="edit btn btn-success btn-sm mr-1 historyMIC">History</a>';
$btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteMIC">Hapus</a>';
$btn .= '</div>';

                            return $btn;
                        })
                        ->addColumn('MIC_Drawing_Image', function($row) {
                            //return '<a href="' . asset('pdfEnovia/' . $row->MIC_Drawing) . '" target="_blank"><img src="' . asset('pdfEnovia/pdf.png') . '" width="50" height="50"></a>';
                            return '<a href="' . asset('pdfEnovia/' . $row->MIC_Drawing) . '" target="_blank">
                            <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                                <img src="' . asset('pdfEnovia/pdf.png') . '" style="width: 100%; height: auto;" alt="PDF Icon">
                            </div>
                            </a>';
                        })
                        ->rawColumns(['action', 'MIC_Drawing_Image'])
                        ->make(true);
                }
                
                // Non-AJAX request, return the view
                return view('PBEngine.mapping-image-component.index');
                

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
            'MIC_Modification_no' => [
                'required',
                'numeric',
                'min:0',
                //'unique:vw_comp_prod,MIC_Modification_no,NULL,MIC_id'
                function ($attribute, $value, $fail) use ($request) {
                    // Lakukan pengecekan pada vw_comp_prod dengan kondisi pn_component
                    $exists = TbMappingImageComponent::where('MIC_Modification_no', $value)
                                        ->where('MIC_ComponentID_IMA', $request->component_id)
                                        ->exists();
        
                    // Jika ada duplikasi, maka validasi gagal
                    if ($exists) {
                        $fail('MIC Modification Number Registered');
                    }
                }
            ],
        ], [
            'MIC_Modification_no.required' => 'Modification Number wajib diisi',
            'MIC_Modification_no.numeric' => 'Modification Number harus berupa angka',
            'MIC_Modification_no.min' => 'Modification Number tidak boleh kurang dari 0',
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

            //Validasi jika file yang diunggah adalah file PDF
            $request->validate([
            
            'MIC_ComponentID_IMA' => 'required',
            'MIC_component_name' => 'required',
            'MIC_PN_component' => 'required',
            'MIC_Modification_no' => 'required',
            'MIC_ProductID_IMA' => 'required',
            'MIC_Drawing' => 'required|mimes:pdf|max:2048', // Maksimal 2048KB (2MB)
            'MIC_product_name' => 'required',
            'MIC_PN_product' => 'required'

            ]);
            // Simpan file MIC_Drawing
            $file = $request->file('MIC_Drawing');
            //dump($file);
            $tmp1 = explode('.', $file->getClientOriginalName());
            $ext1 = end($tmp1);
            $filename = rand(100000,1001238912).".".$ext1;
        
            //$filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'public/pdfEnovia/';
            $file->move($path, $filename);
            //$file->storeAs('public/pdfEnovia', $filename); // Ganti 'path_to_store' dengan path yang sesuai dengan kebutuhan Anda
            $existingData = TbMappingImageComponent::where('MIC_ComponentID_IMA', $request->MIC_ComponentID_IMA)->get();

            // Jika ada data yang sudah ada di database
            if ($existingData->isNotEmpty()) {
                TbMappingImageComponent::where('MIC_ComponentID_IMA', $request->MIC_ComponentID_IMA)
                    ->update(['MIC_Status_Aktifasi' => 1]);
            }
            // Cek apakah ada proses yang dipilih
            if (is_array($request->MIC_ProductID_IMA) && 
                !empty($request->MIC_ProductID_IMA) && 
                is_array($request->MIC_product_name) && 
                !empty($request->MIC_product_name) && 
                is_array($request->MIC_PN_product) && 
                !empty($request->MIC_PN_product)) {
            
                //Tambahkan entri baru untuk setiap elemen dalam array
                foreach ($request->MIC_ProductID_IMA as $index => $productId) {
                        $mic = TbMappingImageComponent::create([
                        'MIC_id' => $request->MIC_id,
                        'MIC_ComponentID_IMA' => $request->MIC_ComponentID_IMA,
                        'MIC_component_name' => $request->MIC_component_name,
                        'MIC_PN_component' => $request->MIC_PN_component,
                        'MIC_Modification_no' => $request->MIC_Modification_no,
                        'MIC_Drawing' => $filename,
                        'MIC_ProductID_IMA' => $productId, // Gunakan indeks untuk mengakses nilai dari array
                        'MIC_product_name' => $request->MIC_product_name[$index], // Gunakan indeks untuk mengakses nilai dari array
                        'MIC_PN_product' => $request->MIC_PN_product[$index], // Gunakan indeks untuk mengakses nilai dari array
                        'MIC_created' => Carbon::now(),
                        'MIC_createdby' => auth()->user()->id,
                    ]);
                    //Periksa apakah entri berhasil disimpan
                    if (!$mic->save()) {
                        $success = false;
                        $message = 'Gagal menyimpan data!';
                        break;
                    }
                    //Tambahkan data yang berhasil disimpan ke dalam array
                        $dopgData[] = $mic;
                }
            }

                //Kembalikan respons berdasarkan keberhasilan operasi
                if ($success) {
                    return response()->json([
                    'success' => $success,
                    'message' => $message,
                    'data'    => $dopgData
                ]); 
                }

            // Jika ada kesalahan, kembalikan respons dengan pesan error
            return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan data',

            ]);


    }

    public function update(Request $request)
    {
        $success = true;
        $message = 'Data Berhasil Disimpan!';
        $dopgData = [];

        //Validasi jika file yang diunggah adalah file PDF
        $request->validate([
        
        'MIC_ComponentID_IMA' => 'required',
        'MIC_component_name' => 'required',
        'MIC_PN_component' => 'required',
        'MIC_Modification_no' => 'required',
        'MIC_ProductID_IMA' => 'required',
        'MIC_Drawing' => 'required|mimes:pdf|max:2048', // Maksimal 2048KB (2MB)
        'MIC_product_name' => 'required',
        'MIC_PN_product' => 'required'

        ]);
        // Simpan file MIC_Drawing
        $file = $request->file('MIC_Drawing');
        //dump($file);
        $tmp1 = explode('.', $file->getClientOriginalName());
        $ext1 = end($tmp1);
        $filename = rand(100000,1001238912).".".$ext1;
       
        //$filename = time() . '.' . $file->getClientOriginalExtension();
        $path = 'public/pdfEnovia/';
        $file->move($path, $filename);
        //$file->storeAs('public/pdfEnovia', $filename); // Ganti 'path_to_store' dengan path yang sesuai dengan kebutuhan Anda
        $existingData = TbMappingImageComponent::where('MIC_ComponentID_IMA', $request->MIC_ComponentID_IMA)->get();

        // Jika ada data yang sudah ada di database
        if ($existingData->isNotEmpty()) {
    
            TbMappingImageComponent::where('MIC_ComponentID_IMA', $request->MIC_ComponentID_IMA)
                ->update(['MIC_Status_Aktifasi' => 1]);
        }

            if ($request->has('MIC_id')) {
                // Jika ada, berarti ini adalah operasi edit
                $mic = TbMappingImageComponent::find($request->sfc_id);
            
                if ($mic) {
                    $mic->update([
                        'MIC_ComponentID_IMA' => $request->MIC_ComponentID_IMA,
                        'MIC_component_name' => $request->MIC_component_name,
                        'MIC_PN_component' => $request->MIC_PN_component,
                        'MIC_Modification_no' => $request->MIC_Modification_no,
                        'MIC_Drawing' => $filename,
                        'MIC_ProductID_IMA' => $request->MIC_ProductID_IMA,
                        'MIC_product_name' => $request->MIC_product_name, 
                        'MIC_PN_product' => $request->MIC_PN_product, 
                        'MIC_Status_Aktifasi' => '0',
                        'MIC_modified' => Carbon::now(),
                        'MIC_modifiedby' => auth()->user()->id
                    ]);
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Diubah!',
                        'data'    => $mic  
                    ]);
                } else {
                    // Jika tidak ada, berarti ini adalah operasi simpan baru
                    $sfc = TbMappingImageComponent::updateOrCreate(
                        ['MIC_id' => $request->MIC_id],
                        [
                            'MIC_ComponentID_IMA' => $request->MIC_ComponentID_IMA,
                            'MIC_component_name' => $request->MIC_component_name,
                            'MIC_PN_component' => $request->MIC_PN_component,
                            'MIC_Modification_no' => $request->MIC_Modification_no,
                            'MIC_Drawing' => $filename,
                            'MIC_ProductID_IMA' => $request->MIC_ProductID_IMA, 
                            'MIC_product_name' => $request->MIC_product_name, 
                            'MIC_PN_product' => $request->MIC_PN_product,
                            'MIC_Status_Aktifasi' => '0', 
                            'MIC_created' => Carbon::now(),
                            'MIC_createdby' => auth()->user()->id
                        ]
                    );
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Disimpan!',
                        'data'    => $sfc  
                    ]); 
            }}
        
        
    }

    public function edit($MIC_id)
    {      
        $mic = TbMappingImageComponent::find($MIC_id);

        // Periksa apakah data ditemukan sebelum melanjutkan
        if (!$mic) {
            // Jika data tidak ditemukan, kirimkan respons JSON dengan pesan kesalahan
        return response()->json(['error' => 'Data not found'], 404);
        }
        return response()->json($mic);
    
    }

    public function destroy($id)
    {
        $mic = TbMappingImageComponent::find($id);

        if (!$mic) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        }
    
        // Ubah nilai attribute sfc_delete_status menjadi 1
        $mic->MIC_status_delete = 1;
        // Set nilai sfc_modified_at ke waktu sekarang
        $mic->MIC_modifiedby = auth()->user()->id;
        $mic->MIC_modified = now();

        // Sementara nonaktifkan pengaturan tanggal otomatis
        $mic->timestamps = false;
        $mic->save();
      
       
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
            'data'    => $mic  
        ]); 
    }

    public function cardHistoryPage($MIC_id)
    {

        $mic = TbMappingImageComponent::find($MIC_id);

        // Periksa apakah data ditemukan sebelum melanjutkan
        if (!$mic) {
            // Jika data tidak ditemukan, kirimkan respons JSON dengan pesan kesalahan
        return response()->json(['error' => 'Data not found'], 404);
        }
        return response()->json($mic);
    }

    public function showHistoryPage(Request $request)
    {
        $micId = $request->MIC_id;
        $componentId = $request->MIC_ComponentID_IMA;
        $productId = $request->MIC_ProductID_IMA;

        if ($request->ajax()) {
            $data = TbMappingImageComponent::where('MIC_status_delete', 0)
                ->where('MIC_ComponentID_IMA', $componentId)
                ->where('MIC_ProductID_IMA', $productId)
            
                ->select(['MIC_id','MIC_ComponentID_IMA' , 'MIC_component_name','MIC_PN_component','MIC_Modification_no','MIC_Drawing','MIC_ProductID_IMA' ,'MIC_product_name','MIC_PN_product','MIC_Status_Aktifasi', 'MIC_created as created_at', 'MIC_createdby as created_by', 'MIC_modified as updated_at', 'MIC_modifiedby as updated_by'])
                ->latest()
                ->get();

            return DataTables::of($data) // Return as array to maintain consistency
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm mr-1 editHis">Ubah</a>';
                    if ($row->MIC_Status_Aktifasi == 0 || $row->MIC_Status_Aktifasi == 'Activated') {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="History" class="edit btn btn-success btn-sm mr-1 activateHis" disabled>Aktifkan</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="Delete" class="btn btn-danger btn-sm mr-1 deleteHis" disabled>Hapus</a>';
                    } else {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="History" class="edit btn btn-success btn-sm mr-1 activateHis">Aktifkan</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->MIC_id.'" data-original-title="Delete" class="btn btn-danger btn-sm mr-1 deleteHis">Hapus</a>';
                    }
                    
                    
                    return $btn;
                })
                ->addColumn('MIC_Drawing_Image', function($row) {
                    return '<a href="' . asset('pdfEnovia/' . $row->MIC_Drawing) . '" target="_blank">
                        <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                            <img src="' . asset('pdfEnovia/pdf.png') . '" style="width: 100%; height: auto;" alt="PDF Icon">
                        </div>
                        </a>';
                })
                ->rawColumns(['action','MIC_Drawing_Image'])
                ->make(true);
        }
            
        return view('PBEngine.mapping-image-component.history');
    }


    // mengecek data komponen tersedia atau belum
    public function checkComponentExists(Request $request)
    {
        $component_id = $request->input('MIC_ComponentID_IMA');

        // Lakukan pencarian berdasarkan component_id di tabel MappingImage
        $mappingImage = TbMappingImageComponent::where('MIC_ComponentID_IMA', $component_id)->first();

        if ($mappingImage) {
            // Jika ditemukan, kembalikan respons bahwa component_id sudah ada
            return response()->json(['exists' => true]);
        } else {
            // Jika tidak ditemukan, kembalikan respons bahwa component_id belum ada
            return response()->json(['exists' => false]);
        }
    }

    //menampilkan gambar saat edit
    public function getImage(Request $request)
    {
        $componentId = $request->component_id;

        // Query database untuk mendapatkan nama file gambar berdasarkan component_id
        $imageName = TbMappingImageComponent::where('MIC_ComponentID_IMA', $componentId)->value('MIC_Drawing');

        // Mengembalikan nama file gambar sebagai respons
        return response()->json([
            'success' => true,
            'MIC_Drawing' => $imageName
        ]);
    }

    public function getImageHistory(Request $request)
    {
        $componentId = $request->component_id;
        $productId = $request->product_id;

        // Query database untuk mendapatkan nama file gambar berdasarkan component_id
        $imageName = TbMappingImageComponent::where('MIC_ComponentID_IMA', $componentId)
        ->where('MIC_ProductID_IMA', $productId)
        ->where('MIC_Status_Aktifasi', 0)
        ->value('MIC_Drawing');

        // Mengembalikan nama file gambar sebagai respons
        return response()->json([
            'success' => true,
            'MIC_Drawing' => $imageName
        ]);
    }

    public function getImageHistoryAct(Request $request)
    {
        $micId = $request->mic_id;
        

        // Query database untuk mendapatkan nama file gambar berdasarkan component_id
        $imageName = TbMappingImageComponent::where('MIC_id', $micId)
        
        //->where('MIC_Status_Aktifasi', 0)
        ->value('MIC_Drawing');

        // Mengembalikan nama file gambar sebagai respons
        return response()->json([
            'success' => true,
            'MIC_Drawing' => $imageName
        ]);
    }

    public function activate($MIC_id)
    {      

        // Ambil data MIC berdasarkan MIC_id yang baru saja diubah
        $mic = TbMappingImageComponent::find($MIC_id);

        // Periksa apakah data ditemukan
        if (!$mic) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Ubah status aktivasi untuk semua MIC dengan compent_id dan product_id yang sama menjadi 1
        TbMappingImageComponent::where('MIC_ComponentID_IMA', $mic->MIC_ComponentID_IMA)
                                ->where('MIC_ProductID_IMA', $mic->MIC_ProductID_IMA)
                                
                                ->update(['MIC_Status_Aktifasi' => 1 , 'MIC_created' => Carbon::now(),
                                'MIC_createdby' => auth()->user()->id,]);

        // Ubah status aktivasi untuk MIC dengan MIC_id yang diberikan menjadi 0
            $updatedMic = TbMappingImageComponent::where('MIC_id', $MIC_id)->update(['MIC_Status_Aktifasi' => 0]);

            // Periksa apakah data berhasil diubah
            if (!$updatedMic) {
                return response()->json(['error' => 'Data not found'], 404);
            }


            // Kirimkan respons sukses
            return response()->json(['success' => 'Activation successful']);



    }



    //select2 component saat add
    public function getComponent(Request $request)
    {
        $tags = [];
        if ($search = $request->name) {
            $tags = TbComponent::where('pn_component', 'LIKE', "%$search%")
                ->get();
                
        }

        return response()->json($tags);
    }

    // select2 multiple product add
    public function getProduct(Request $request)
    {
        $products = [];

        if ($component_id = $request->component_id) {
            $products = TbMatrixProdComp::join('tb_product as p', 'tb_matrix_prod_comp.product_id', '=', 'p.id')
                ->select('p.name', 'tb_matrix_prod_comp.product_id', 'p.pn_product', 'tb_matrix_prod_comp.component_id')
                ->where('component_id', $component_id)
                ->get();
        }

        return response()->json($products);
    }

    //select2 multiple product edit
    public function getProductEdit(Request $request)
    {
        $productsEdit = [];

        if ($component_id = $request->component_id) {
            $productsEdit = TbMatrixProdComp::join('tb_product as p', 'tb_matrix_prod_comp.product_id', '=', 'p.id')
                ->select('p.name', 'tb_matrix_prod_comp.product_id', 'p.pn_product', 'tb_matrix_prod_comp.component_id')
                ->where('component_id', $component_id)
                ->get();
        }

        return response()->json($productsEdit);
    }

    //mengambil data modification no
    public function getMappingByComponent($component_id){
        $mapping = TbMappingImageComponent::select('MIC_Modification_no')
                ->where('MIC_ComponentID_IMA',$component_id )
                ->where('MIC_Status_Aktifasi','=','0')
                ->where('MIC_status_delete', '=', '0')
                ->take(1)
                ->get();

        // Mengambil nilai MIC_Modification_no dari hasil pertama (jika ada)
        $modificationNo = $mapping->isEmpty() ? null : $mapping[0]->MIC_Modification_no;
        
        
        return response()->json($modificationNo);
    }

    public function getAllProduct(Request $request){
        $products = [];
    
        if ($search = $request->name) {
            $products = TbMatrixProdComp::join('tb_product as p', 'tb_matrix_prod_comp.product_id', '=', 'p.id')
            ->select('p.name', 'tb_matrix_prod_comp.product_id', 'p.pn_product', 'tb_matrix_prod_comp.component_id')
            ->where('pn_product', 'LIKE', "%$search%")
            ->get();
        }
        return response()->json($products);
    
    }
    
    //select2  semua product saat edit
    public function getAllProductEdit(Request $request){
        $productsEdit = [];
    
        if ($search = $request->name) {
            $productsEdit = TbMatrixProdComp::join('tb_product as p', 'tb_matrix_prod_comp.product_id', '=', 'p.id')
            ->select('p.name', 'tb_matrix_prod_comp.product_id', 'p.pn_product', 'tb_matrix_prod_comp.component_id')
            ->where('pn_product', 'LIKE', "%$search%")
            ->get();
        }
        return response()->json($productsEdit);
    
    }

}



