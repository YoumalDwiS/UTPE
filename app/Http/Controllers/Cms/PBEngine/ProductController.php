<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('product') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            if ($this->PermissionActionMenu('product')->r == 1) {
                $data = null;
                return view('PBEngine/product/index')->with([
                    'data' => $data
                ]);
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    public function getProduct()
    {
        $data = TbProduct::all();
        return $data;
    }

    public function syncProductIMA()
    {
        try {
            if ($this->PermissionActionMenu('product')->c == 1) {
                $response = Http::get(env('ENV_IMA_API') . '/api/GetAllProduct');
                $arr = json_decode($response, true);
                // dd($arr);
                $count = 0;

                foreach ($arr as $product) {
                    // dd($product);
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
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }
}
