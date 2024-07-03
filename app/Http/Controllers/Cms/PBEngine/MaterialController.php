<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbMaterial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MaterialController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('material') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // $data = TbMaterial::all();
        $data = null;
        return view('PBEngine/material/index')->with([
            'data' => $data
        ]);
        try {
            if ($this->PermissionActionMenu('material')->r == 1) {
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    public function getMaterial()
    {
        $data = TbMaterial::all();
        return $data;
    }

    public function getMaterialSelect(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $data = TbMaterial::select('id', 'name as text')->where('id', '!=', 0)->get()->toArray();
        } else {
            $data = TbMaterial::orderby('name', 'asc')->select('id', 'name as text')->where('name', 'like', '%' . $search . '%')->limit(5)->get();
        }

        echo json_encode($data);
    }

    // syncMaterialIMA
    public function syncMaterialIMA()
    {
        try {
            if ($this->PermissionActionMenu('material')->r == 1) {
                $response = Http::get(env('ENV_IMA_API') . '/api/GetAllMaterial');
                $arr = json_decode($response, true);
                // dd($arr);
                $count = 0;

                foreach ($arr as $material) {
                    // dd($material);
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
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }
}
