<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbBase;
use App\Models\Table\PBEngine\TbCustomer;
use App\Models\Table\PBEngine\TbMappingPRO;
use Illuminate\Http\Request;

class MappingProCustomerController extends Controller
{
    public function index(Request $request){
        //customer
        $mapping = TbMappingPRO::select('tb_mapping_pro_customer.*', 'c.customer_name')
        ->join('tb_customer as c','c.customer_id', '=', 'mapping_customer_id')
        ->where('mapping_delete_status', 0)
        ->get();

        $customers = TbCustomer::where('customer_delete_status','=', 0)
        ->get();

        return view('PBEngine.mapping-pro-customer.index',
        ['mapping'=>$mapping,
        'customers'=>$customers,
        // 'selectedCustomer'=>$request->customer,
        ]
        );



    }

    public function addMappingPRO(Request $request){

        TbMappingPRO::create([
                'mapping_pro' => $request->input('PRONumber'),
                'mapping_customer_id' => $request->input('customers'),
                'mapping_pn' => $request->input('PN'),
                'mapping_created_by' => auth()->user()->id,
                'mapping_created_at' => now()->format('Y-m-d H:i:s'),
                'mapping_modified_by' => auth()->user()->id,
                'mapping_modified_at' => now()->format('Y-m-d H:i:s'),
                'mapping_delete_status' => 0,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan.', 'redirect_url' => url('mapping-pro-customer/')]);
        // return redirect('mapping-pro-customer/');
    }


    public function editMappingPRO(Request $request, $mappingid){

        TbMappingPRO::where('mapping_id', $mappingid)->update([
            'mapping_pro' => $request->input('PRONumber2'),
            'mapping_pn' => $request->input('PN2'),
            'mapping_customer_id' => $request->input('customers2'),
            'mapping_modified_by' => auth()->user()->id,
            'mapping_modified_at' => now()->format('Y-m-d H:i:s'),
        ]);
        return response()->json(['status' => 'success', 'message' => 'Data berhasil diubah.', 'redirect_url' => url('mapping-pro-customer/')]);
        // return redirect('mapping-pro-customer/');
    }

    public function deleteMappingPRO(Request $request, $mappingid){
        TbMappingPRO::where('mapping_id', $mappingid)->update([
            'mapping_delete_status' => '1'
        ]);


        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus.', 'redirect_url' => url('mapping-pro-customer/')]);
        // return redirect('mapping-pro-customer/');
    }

    public function getMappingId(Request $request, $mappingid){
        
        $mapping = TbMappingPRO::find($mappingid);
        $mappingProIds = json_decode($mapping->mapping_pro);
        $tag = TbBase::select('id','PN', 'PRONumber')->where('PRONumber', $mappingProIds)->first();
        // dd($tags);
    
        return response()->json([
            'mapping_customer_id' => $mapping->mapping_customer_id,
            'tag' => $tag,
        ]);
    }

    public function getPRO(Request $request)
    {
        // $tags = [];

    if ($search = $request->name) {
        $tags = TbBase::select('id','PRONumber', 'PN')
        ->where('PRONumber', 'LIKE', "%$search%")
        ->distinct()
        ->get();
            
    }

    return response()->json($tags);
    }


}