<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Helpers\LinkHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;
use App\Models\Table\PBEngine\TbProduct;
use App\Models\Table\PBEngine\TbMaterial;
use App\Models\Table\PBEngine\TbComponent;
use App\Models\Table\PBEngine\TbNotification;
use App\Models\Table\PBEngine\TbMatrixProdComp;
use App\Models\Table\PBEngine\TbSemifinishInventory;

class APIController extends Controller
{
    // private $DOMAIN = "http://10.48.10.43/imaapi/api/";
    // $DOMAIN = LinkHelper::$API_IMA;

    // ----------------------------------------------------------- IMA API
    public function getMemoList()
    {
        $response = Http::get(env('ENV_IMA_API') . '/api/GetMemoList');
        $arr = json_decode($response, true);
        return $arr;
    }

    public function getMemoDetail($id)
    {
        $response = Http::get(env('ENV_IMA_API') . '/api/GetDetailMemo', [
            "id" => 4854
        ]);
        $arr = json_decode($response, true);
        return $arr;
    }

    // ----------------------------------------------------------- PB ENGINE API
    public function addNotification(Request $data)
    {
        $notif = TbNotification::create([
            'role_name' => $data->role_name,
            'reference_id' => $data->reference_id, //id memo
            'remark' => $data->remark,
            'type' => $data->type,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
        ]);

        if ($notif) {
            return response()->json([
                'message' => 'Notification Send'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Notification Failed to Send'
            ], 500);
        }
    }

    public function syncronizeMasterData(){
        $syncMaterial = $this->syncronizeMaterial();
        $syncComponent = $this->syncronizeComponent();
        $syncProduct = $this->syncronizeProduct();
        $syncMatrix = $this->syncronizeMatrixProdComp();

        if($syncMaterial && $syncComponent && $syncProduct && $syncMatrix){
            return 200;
        } else {
            return 500;
        }
    }

    public function syncronizeMaterial(){
        $response = Http::get(env('ENV_IMA_API') . '/api/GetAllMaterial');
        $arr = json_decode($response, true);
        $count = 0;

        foreach ($arr as $material) {
            $find_comp = TbMaterial::where('id', $material['ID'])->first();
            if ($find_comp == null) {
                $material = TbMaterial::create([
                    'id' => $material['ID'],
                    'name' => $material['Name'],
                    'created_at' => $material['Created'],
                    'created_by' => $material['CreatedBy'],
                    'updated_at' => $material['LastModified'],
                    'updated_by' => $material['LastModifiedBy'],
                ]);
                $count++;
            }
        }

        if ($material) {
            return 200;
        } else {
            return 500;
        }
    }

    public function syncronizeComponent(){
        $response = Http::get(env('ENV_IMA_API') . '/api/GetAllComponent');
        $arr = json_decode($response, true);
        $count = 0;

        foreach ($arr as $component) {
            // dd($component);
            $find_comp = TbComponent::where('pn_component', $component['PartNumber'])->first();
            if (!$find_comp) {
                $comp = TbComponent::create([
                    'id' => $component['ID'],
                    'name' => $component['PartName'],
                    'pn_component' => $component['PartNumber'],
                    'material_id' => $component['MaterialID'],
                    'thickness' => $component['Thickness'],
                    'lenght' => $component['Long'],
                    'width' => $component['Width'],
                    'outer_diameter' => $component['OuterDiameter'],
                    'inner_diameter' => $component['InnerDiameter'],
                    'is_in_house' => $component['IsInHouse'],
                    'created_at' => $component['Created'],
                    'created_by' => $component['CreatedBy'],
                    'updated_at' => $component['LastModified'],
                    'updated_by' => $component['LastModifiedBy'],
                ]);
            }

            $find_semifinish_inventory = TbSemifinishInventory::where('pn_component', $component['PartNumber'])->first();

            if ($find_semifinish_inventory == null) {
                $component = TbComponent::where('pn_component', $component['PartNumber'])->first();
                $si = TbSemifinishInventory::create([
                    'component_id' => $component->id,
                    'pn_component' => $component->pn_component,
                    'component_name' => $component->name,
                    'quantity' => 0,
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);
            }
        }

        return 200;
    }

    public function syncronizeProduct(){
        $response = Http::get(env('ENV_IMA_API') . '/api/GetAllProduct');
        $arr = json_decode($response, true);
        $count = 0;

        foreach ($arr as $product) {
            $find = TbProduct::where('id', $product['ID'])->first();
            if ($find == null) {
                $product = TbProduct::create([
                    'id' => $product['ID'],
                    'product_sub_group_id' => $product['ProductSubGroupID'],
                    'product_sub_group' => $product['ProductSubGroup'],
                    'product_group_id' => $product['ProductGroupID'],
                    'product_group' => $product['ProductGroup'],
                    'name' => $product['Name'],
                    'pn_product' => $product['PN'],
                    'total_day' => $product['TotalDay'],
                    'product_reference_id' => $product['ProductReferenceID'],
                    'product_reference' => $product['ProductReference'],
                    'id_default_product_reference' => $product['IsDefaultProductReference'],
                    'created_at' => $product['Created'],
                    'created_by' => $product['CreatedBy'],
                    'updated_at' => $product['LastModified'],
                    'updated_by' => $product['LastModifiedBy']
                ]);
                $count++;
            }
        }

        if ($product) {
            return 200;
        } else {
            return 500;
        }
    }

    public function syncronizeMatrixProdComp(){
        $response = Http::get(env('ENV_IMA_API') . '/api/GetAllViewMasterProcessComponent');
        $arr = json_decode($response, true);
        $count = 0;

        foreach ($arr as $matrix) {
            $exist_matrix = TbMatrixProdComp::where('product_id', $matrix['ProductID'])->where('component_id', $matrix['ComponentID'])->first();

            if (!$exist_matrix) {
                $matrix = TbMatrixProdComp::create([
                    'product_id' => $matrix['ProductID'],
                    'component_id' => $matrix['ComponentID'],
                    'quantity' => $matrix['Quantity'],
                    'is_default' => $matrix['IsDefaultComponent'],
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name
                ]);
            }
        }

        if ($matrix) {
            return 200;
        } else {
            return 500;
        }
    }
}
