<?php

namespace App\Http\Controllers\Cms\PBEngine;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\MatrixCompRawImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Table\PBEngine\TbProduct;
use App\Models\Table\PBEngine\TbComponent;
use App\Models\Table\PBEngine\TbRawMaterial;
use App\Models\Table\PBEngine\TbMatrixCompRaw;
use App\Models\Table\PBEngine\TbMatrixProdComp;

class BOMController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('bom') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            if ($this->PermissionActionMenu('bom')->r == 1) {
                $data = array();

                $product = TbProduct::all();

                $data = [
                    'product' => $product
                ];

                return view('PBEngine/bom/index')->with([
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

    public function show($id)
    {


        try {
            if ($this->PermissionActionMenu('bom')->v == 1) {
                $data = array();

                $product = TbProduct::where('id', $id)->first();
                $matrixProdComp = TbMatrixProdComp::with('product', 'component')->where('product_id', $id)->get();
                // $matrixCompRaw = TbMatrixCompRaw::with('component', 'rawMaterial')->whereIn('component_id', $matrixProdComp->pluck('component_id'))->get();
                // // dd($matrixCompRaw);

                $component = array();

                foreach ($matrixProdComp as $mpc) {
                    $mcr = TbMatrixCompRaw::with('component', 'rawMaterial')->where('component_id', $mpc->component_id)->get();
                    $component[] = [
                        'id' => $mpc->component_id,
                        'name' => $mpc->component->name,
                        'pn_component' => $mpc->component->pn_component,
                        'quantity' => $mpc->quantity,
                        'raw_material' => $mcr
                    ];
                }

                $data = [
                    'product' => $product,
                    'component' => $component,
                ];

                // dd($data['component']);

                return view('PBEngine/bom/show')->with([
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

    // import data matrix component to raw material dari excel
    public function importMatrixExcel(Request $request)
    {

        try {
            if ($this->PermissionActionMenu('bom')->c == 1) {
                $matrixCompRawMat = Excel::toArray(new MatrixCompRawImport, $request->file('file_matrix_rawmat'));
                unset($matrixCompRawMat[0][0]);

                $mCompRawMatInsert = [];
                foreach ($matrixCompRawMat[0] as $matrixCR) {
                    $getIdComponent = TbComponent::where('pn_component', $matrixCR[0])->first();
                    $getIdRawMat = TbRawMaterial::where('pn_raw_material', $matrixCR[1])->first();

                    if ($getIdComponent && $getIdRawMat) {
                        $cek = TbMatrixCompRaw::where('component_id', $getIdComponent->id)->where('raw_material_id', $getIdRawMat->id)->first();
                        if (!$cek) {
                            $mCompRawMatInsert = TbMatrixCompRaw::create([
                                'component_id' => $getIdComponent->id,
                                'raw_material_id' => $getIdRawMat->id,
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name
                            ]);
                        }
                    }
                }

                if ($mCompRawMatInsert) {
                    return redirect()->back()->with('alert', [
                        'icon' => 'success',
                        'title' => 'Success',
                        'text' => 'Import Matrix Component Raw Material Successfully!'
                    ]);
                } else {
                    return redirect()->back()->with('alert', [
                        'icon' => 'error',
                        'title' => 'Error',
                        'text' => 'Import Matrix Component Raw Material Failed!'
                    ]);
                }
            } else {
                return redirect()->back();
            }
            
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    // sinkronisasi matrix product to component dari IMA
    public function syncProdCompIMA()
    {
        try {
            if ($this->PermissionActionMenu('bom')->u == 1) {
                $response = Http::get(env('ENV_IMA_API') . '/api/GetAllViewMasterProcessComponent');
                $arr = json_decode($response, true);
                // dd($arr);
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
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    // [TIDAK DIGUNAKAN] sinkronisasi data matrix component to raw material berdasarkan grade dari IMA
    public function syncCompRawIMA()
    {
        try {
            if ($this->PermissionActionMenu('bom')->u == 1) {
                $component = TbComponent::all();

                foreach ($component as $c) {
                    $rawMaterial = TbRawMaterial::where('material_id', $c->material_id)->where('thickness', $c->thickness)->get();
                    foreach ($rawMaterial as $rm) {
                        $existMatrix = TbMatrixCompRaw::where('component_id', $c->id)->where('raw_material_id', $rm->id)->first();

                        if (!$existMatrix) {
                            TbMatrixCompRaw::create([
                                'component_id' => $c->id,
                                'raw_material_id' => $rm->id,
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name,
                            ]);
                        }
                    }
                }

                return 200;
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
        
    }

    // mendapatkan data matrix product to component
    public function getProdComp()
    {
        try {
            if ($this->PermissionActionMenu('bom')->r == 1) {
                $data = TbMatrixProdComp::with(['product', 'component'])->get();
                return $data;
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
        
    }

    // mendapatkan data matrix component to raw material
    public function getCompRaw()
    {
        try {
            if ($this->PermissionActionMenu('bom')->r == 1) {
                $data = TbMatrixCompRaw::with(['component', 'rawMaterial'])->get();
                // dd($data);
                return $data;
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
        
    }
}
