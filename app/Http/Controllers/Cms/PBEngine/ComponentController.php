<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbComponent;
use App\Models\Table\PBEngine\TbSemifinishInventory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ComponentController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('component') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            if ($this->PermissionActionMenu('component')->r == 1) {
                return view('PBEngine/component/index');
            } else {
                return redirect()->back()->with('err_message', 'Access Denied');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Internal Server Error');
        }
    }

    //------------------------------------------------------------------------------ ajax function


    // mendapatkan data component
    public function getComponent()
    {
        try {
            $data = TbComponent::all();
            return $data;
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }

    // sinkronisasi data komponen dari IMA
    public function syncComponentIMA()
    {
        try {
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

        } catch (Exception $e) {
            $this->ErrorLog($e);

            return 500;
        }
    }
}
