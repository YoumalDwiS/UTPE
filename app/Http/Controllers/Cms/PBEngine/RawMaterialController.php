<?php

namespace App\Http\Controllers\Cms\PBEngine;

use Illuminate\Http\Request;
use App\Imports\RawMaterialImport;
use App\Http\Controllers\Controller;
use App\Models\Table\CCR\TbInventoriCCR;
use App\Models\Table\CCR\TbMaterialGroupCCR;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Table\PBEngine\TbMaterial;
use App\Models\Table\PBEngine\TbRawMaterial;
use App\Models\View\CCR\VwStockRawMaterial;
use Exception; 

class RawMaterialController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('raw-material') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            if ($this->PermissionActionMenu('raw-material')->r == 1) {
                return view('PBEngine.raw-material.index')->with('data', []);
            } else {
                return redirect()->back()->with('err_message', 'Access Denied');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Internal Server Error');
        }
    }

    // import data raw material dari excel
    public function importExcel(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('raw-material')->c == 1) {
                $rawMaterial = Excel::toArray(new RawMaterialImport, $request->file('file_raw_material'));
                unset($rawMaterial[0][0]);

                $insertRawMat = [];
                foreach ($rawMaterial[0] as $rawMat) {
                    $getRawMaterial = TbRawMaterial::where('pn_raw_material', $rawMat[0])->first();
                    $getMaterial = TbMaterial::where('name', $rawMat[2])->first();
                    if ($rawMat[6] == null) {
                        $type = 'PLATE';
                    } else {
                        $type = 'PIPE';
                    }
                    if (!$getRawMaterial && $getMaterial) {
                        $insertRawMat = TbRawMaterial::insert([
                            'pn_raw_material' => $rawMat[0],
                            'description' => $rawMat[1],
                            'material_id' => $getMaterial->id,
                            'thickness' => floatval($rawMat[3]),
                            'length' => floatval($rawMat[4]),
                            'width' => floatval($rawMat[5]),
                            'diameter' => floatval($rawMat[6]),
                            'type' => $type,
                            'status' => 1,
                            'created_by' => Auth::user()->id
                        ]);
                    }
                }

                if($insertRawMat){
                    return redirect()->back()->with('alert', [
                        'icon' => 'success',
                        'title' => 'Success',
                        'text' => 'Import Data Raw Material Successfully!'
                    ]);
                }else{
                    return redirect()->back()->with('alert', [
                        'icon' => 'error',
                        'title' => 'Error',
                        'text' => 'Import Data Raw Material Failed!'
                    ]);
                }
            } else {
                return redirect()->back()->with('err_message', 'Access Denied');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Internal Server Error');
        }
        
    }

    //------------------------------------------------------------------------------ ajax function

    // [TIDAK DIGUNAKAN] sinkronisasi data raw material 
    public function syncRawMaterialCCR()
    {
        try {

        } catch (Exception $e) {
            $this->ErrorLog($e);
        }

        $data = array();
        $raw_material_ccr = TbInventoriCCR::with('materialGroup')->whereIn('plant', ['UCKR', 'PCKR', '1000'])->where('material_type', 'ZRAW')->get();

        foreach ($raw_material_ccr as $rmc) {
            $raw_material_pb = TbRawMaterial::where('pn_raw_material', $rmc->material_number)->first();

            if ($raw_material_pb == null) {

                // $dimension = explode('x', strtolower($rmc->material_description));
                // dd($dimension);
                // $width = floatval($dimension[1]);
                // $length = floatval($dimension[2]);

                $desc = explode('x', strtolower($rmc->material_description));
                $dimension = explode('-', $rmc->material_number);

                //HBN
                $left_desc = explode(' ', $desc[0]);
                if(count($left_desc)){

                }
                
                $right_desc = explode(' ', $desc[count($desc) - 1]);

                if (str_contains(strtolower($rmc->material_description), 'hbn')) {
                    if (str_contains(strtolower($rmc->material_description), 'hbn 450')) {
                        $thickness = floatval($dimension[1]) / 10;
                    } else {
                        $thickness = floatval($dimension[0]) / 10;
                    }
                } else {
                    $thickness = floatval($dimension[1]) / 10;
                }

                $type = strtoupper($rmc->materialGroup->description);
                $dm = [
                    'material_description' => $rmc->material_description,
                    'thickness' => $thickness,
                    // 'width' => $width,
                    // 'length' => $length,
                    'type' => $type,
                ];

                array_push($data, $dm);
            }
        }

        return $data;
    }

    // mendapatkan data raw material
    public function getRawMaterial()
    {
        try {
            $data = TbRawMaterial::with('material')->where('status', 1)->get();
        return $data;
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }

    // mendapatakan data raw material yang akan diedit 
    public function editRawMaterial(Request $request)
    {
        try {
            $rawMaterial = TbRawMaterial::where('id', $request->id)->first();
            $material = TbMaterial::where('id', '!=', 0)->get();
            $data = [
                'rawMaterial' => $rawMaterial,
                'material' => $material
            ];
            return response()->json($data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }

    // update data raw material
    public function updateRawMaterial(Request $request)
    {
        try {
            $data = TbRawMaterial::where('id', $request->id_edit)->update([
                'pn_raw_material' => $request->pn_edit,
                'description' => $request->desc_edit,
                'material_id' => $request->grade_edit,
                'thickness' => $request->thickness_edit,
                'length' => $request->length_edit,
                'width' => $request->width_edit,
                'diameter' => $request->diameter_edit,
                'type' => $request->type_edit,
                'updated_by' => Auth::user()->id
            ]);
    
            if ($data) {
                return redirect()->back()->with('alert', [
                    'icon' => 'success',
                    'title' => 'Success',
                    'text' => 'Update Data Raw Material Successfully!'
                ]);
            } else {
                return redirect()->back()->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Update Data Raw Material Failed'
                ]);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }

    // nonaktifkan data raw material
    public function deleteRawMaterial(Request $request)
    {
        try {
            $data = TbRawMaterial::where('id', $request->idDelete)->update([
                'status' => 0
            ]);
    
            return redirect()->back()->with('alert', [
                'icon' => 'success',
                'title' => 'Success',
                'text' => 'Delete Data Raw Material Successfully!'
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
        } 
    }
}
