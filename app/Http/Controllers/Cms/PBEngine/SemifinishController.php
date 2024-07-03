<?php

namespace App\Http\Controllers\Cms\PBEngine;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Table\PBEngine\TbUnit;
use App\Exports\InventoryDetailExport;
use App\Models\Table\PBEngine\TbMemoPPC;
use App\Models\Table\PBEngine\TbProduct;
use App\Models\Table\Kanban\TbMemoKanban;
use App\Models\Table\PBEngine\TbComponent;
use App\Models\Table\PBEngine\TbMemoPROPPC;
use App\Models\View\VwMemoIdProgressProduct;
use App\Models\Table\PBEngine\TbSemifinishLog;
use App\Models\Table\PBEngine\TbMatrixProdComp;
use App\Models\Table\PBEngine\TbProgressProduct;
use App\Models\Table\PBEngine\TbMemoComponentPPC;
use App\Models\Table\Kanban\TbMemoComponentKanban;
use App\Models\View\Kanban\VwPartNumberSubProccess;
use App\Models\Table\PBEngine\TbSemifinishInventory;
use App\Models\Table\PBEngine\TbMappingImageComponent;

class SemifinishController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('semifinish') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    /*
        Show index page
    */
    public function index()
    {
        try {
            if ($this->PermissionActionMenu('semifinish')->r == 1) {
                $products = [];
                $units = TbUnit::select('product_id')
                    ->where('status', true)
                    ->groupBy('product_id')
                    ->get();

                foreach ($units as $unit) {
                    $product = TbProduct::findOrFail($unit->product_id);
                    $result = collect([
                        'id' => $unit->product_id,
                        'name' => $product->name,
                        'product_number' => $product->pn_product,
                    ]);
                    array_push($products, $result);
                }

                return view('PBEngine/semifinish/index', [
                    'products' => $products,
                ]);
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    /*
        Show progress per component page
    */
    public function show($product_id, $memo_id = null, $sub_proccess_name = null, $search_pn = null)
    {
        // try {
        //     if ($this->PermissionActionMenu('semifinish')->r == 1) {
                // Get data all products
                $products = [];
                $units = TbUnit::select('product_id')
                    ->where('status', true)
                    ->groupBy('product_id')
                    ->get();
                foreach ($units as $unit) {
                    $this_product = TbProduct::findOrFail($unit->product_id);
                    $result =collect([
                        'id' => $unit->product_id,
                        'name' => $this_product->name,
                        'product_number' => $this_product->pn_product,
                    ]);
                    array_push($products, $result);
                }

                // Get data product by id
                $product = [];
                foreach ($products as $item) {
                    if ($item['id'] == $product_id) {
                        $product = $item;
                    }
                }

                // Get data memo numbers by product id
                $memo_ppc = TbMemoPPC::where('product_id', $product_id)->get();

                // Get pro by memo id
                if (str_contains($memo_id, "-")) {
                    $memo_ids = explode("-", $memo_id);
                } elseif($memo_id == null) {
                    $memo_ids = [];
                } else {
                    $memo_ids = [$memo_id];
                }
                $pro_number_by_memo = [];
                foreach($memo_ids as $id) {
                    $pro_by_memo = TbMemoPROPPC::where('memo_id', $id)->get();
                    foreach ($pro_by_memo as $pro) {
                        array_push($pro_number_by_memo, $pro->pro_number);
                    }
                }

                // Get sub proccess by product number
                $part_numbers_per_sub_proccess = [];
                $sub_proccess_names = [];
                $sub_proccess = [];
                $sub_proccess_per_product = VwPartNumberSubProccess::where('product_number', $product['product_number'])->get();
                $part_numbers = [];
                $part_numbers_arr = [];
                foreach($memo_ids as $id) {
                    $pns = TbMemoComponentPPC::where('memo_id', $id)->get();
                    foreach($pns as $pn) {
                        array_push($part_numbers, $pn);
                        array_push($part_numbers_arr, $pn->pn_component);
                    }
                }

                if ($sub_proccess_name === 'All') {
                    foreach($part_numbers as $pn){
                        array_push($part_numbers_per_sub_proccess, $pn["pn_component"]);
                    }
                } else {
                    foreach ($sub_proccess_per_product as $sub) {
                        if (
                            !in_array($sub->part_number, $part_numbers_per_sub_proccess)
                            && $sub->subproses == $sub_proccess_name
                        ) {
                            array_push($part_numbers_per_sub_proccess, $sub->part_number);
                        }
                        // if (!in_array($sub->subproses, $sub_proccess_names)) {
                        //     array_push($sub_proccess_names, $sub->subproses);
                        //     array_push($sub_proccess, $sub);
                        // }
                    }
                }

                // Get All Sub Proses of Product
                $subproses = VwPartNumberSubProccess::where('product_number', $product['product_number'])->distinct()->get('subproses');
                foreach($subproses as $sp){
                    array_push($sub_proccess, $sp['subproses']);
                }

                // Get quantity per pro and sn (Tb Progress)
                $qtys_per_pro_sn = [];
                if ($memo_id) {
                    $data_tb_progress = VwMemoIdProgressProduct::select('*')
                                                            ->whereIn('memo_id', $memo_ids)
                                                            ->get();
                    foreach ($data_tb_progress as $item) {
                        $result = collect([
                            'id_progress_product' => $item->id,
                            'part_number' => $item->pn_component,
                            'pro' => $item->pro_number,
                            'sn' => $item->serial_number,
                            'qty' => $item->quantity,
                        ]);
                        array_push($qtys_per_pro_sn, $result);
                    }
                }

                // Get data PRO and SN by product id
                $data_pro = [];
                $data_sn = [];
                $data_unit_id = [];
                $data_unit_product = TbUnit::where('product_id', $product_id)
                                            ->where('status', true)
                                            ->orderBy('pro_number', 'ASC')
                                            ->get();
                foreach ($data_unit_product as $item) {
                    if (in_array($item->pro_number, $pro_number_by_memo)) {
                        $pro = collect([
                            'pro' => $item->pro_number,
                        ]);
                        $sn = collect([
                            'sn' => $item->serial_number,
                        ]);

                        array_push($data_pro, $pro);
                        array_push($data_sn, $sn);
                        array_push($data_unit_id, $item->id);
                    }
                }

                // Get data part numbers by product id
                $data_part_numbers = [];
                $data_matrix_prod_comp = TbMatrixProdComp::where('product_id', $product_id)
                    ->orderBy('is_default', 'DESC')
                    ->get();

                if($sub_proccess_name === 'All'){
                    foreach ($data_matrix_prod_comp as $item) {
                        $component = TbComponent::findOrFail($item->component_id);
                        $unit_ids = TbProgressProduct::select('unit_id')
                            ->distinct()
                            ->where('component_id', $component->id)
                            ->get();

                        $count_unit_per_component = 0;
                        foreach ($unit_ids as $unit) {
                            if (in_array($unit->unit_id, $data_unit_id)) {
                                $count_unit_per_component++;
                            }
                        }

                        $image_component_arr = TbMappingImageComponent::where("MIC_PN_component", $component->pn_component)->get();

                        if (count($image_component_arr)) {
                            $image_component = $image_component_arr->first()->MIC_Drawing;
                        } else {
                            $image_component = "";
                        }

                        if (in_array($component->pn_component, $part_numbers_arr)) {
                            $is_active = true;
                        } else {
                            $is_active = false;
                        };

                        $part_number = collect([
                            'part_number' => $component->pn_component,
                            'image_component' => $image_component,
                            'name' => $component->name,
                            'qty_per_unit' => $item->quantity,
                            'is_active' => $is_active,
                            'count_unit_per_component' => $count_unit_per_component,
                            'total_qty_per_component' => $count_unit_per_component * $item->quantity,
                        ]);

                        if ($search_pn) {
                            if (str_contains(strtolower($component->pn_component), strtolower($search_pn))) {
                                array_push($data_part_numbers, $part_number);
                            }
                        } else {
                            array_push($data_part_numbers, $part_number);
                        }
                    }
                } else {
                    foreach ($data_matrix_prod_comp as $item) {
                        $component = TbComponent::findOrFail($item->component_id);
                        if (in_array($component->pn_component, $part_numbers_per_sub_proccess)) {
                            $unit_ids = TbProgressProduct::select('unit_id')
                                ->distinct()
                                ->where('component_id', $component->id)
                                // ->count('unit_id');
                                ->get();

                            $count_unit_per_component = 0;
                            foreach ($unit_ids as $unit) {
                                if (in_array($unit->unit_id, $data_unit_id)) {
                                    $count_unit_per_component++;
                                }
                            }

                            $image_component_arr = TbMappingImageComponent::where("MIC_PN_component", $component->pn_component)->get();

                            if (count($image_component_arr)) {
                                $image_component = $image_component_arr->first()->MIC_Drawing;
                            } else {
                                $image_component = "";
                            }

                            if (in_array($component->pn_component, $part_numbers_arr)) {
                                $is_active = true;
                            } else {
                                $is_active = false;
                            };

                            $part_number = collect([
                                'part_number' => $component->pn_component,
                                'image_component' => $image_component,
                                'name' => $component->name,
                                'qty_per_unit' => $item->quantity,
                                'is_active' => $is_active,
                                'count_unit_per_component' => $count_unit_per_component,
                                'total_qty_per_component' => $count_unit_per_component * $item->quantity,
                            ]);

                            if ($search_pn) {
                                if (str_contains(strtolower($component->pn_component), strtolower($search_pn))) {
                                    array_push($data_part_numbers, $part_number);
                                }
                            } else {
                                array_push($data_part_numbers, $part_number);
                            }
                        }
                    }
                }

                // All quantity needed
                $total_qty_per_unit = 0;
                foreach ($data_part_numbers as $part_number) {
                    $total_qty_per_unit += $part_number['qty_per_unit'];
                }

                // Return
                return view('PBEngine/semifinish/show', [
                    'product_id' => $product_id,
                    'products' => $products,
                    'product' => $product,
                    'data_part_numbers' => $data_part_numbers,
                    'data_pro' => $data_pro,
                    'data_sn' => $data_sn,
                    'total_qty_per_unit' => $total_qty_per_unit,
                    'qtys_per_pro_sn' => $qtys_per_pro_sn,
                    'memo_ids' => $memo_ids,
                    'memo_id' => $memo_id,
                    'sub_proccess_name' => $sub_proccess_name,
                    'memo_ppc' => $memo_ppc,
                    'sub_proccess' => $sub_proccess,
                    'pro_number_by_memo' => $pro_number_by_memo,
                    'search_pn' => $search_pn,
                ]);
        //     } else {
        //         return redirect()->back();
        //     }
        // } catch (Exception $e) {
        //     $this->ErrorLog($e);
        //     return redirect()->back();
        // }
    }

    /*
        Update progress per PRO and SN
    */
    public function update_progress(Request $request)
    {
        try {
            $data = $request->all()['data'];
            $qty_needed_per_pro = $data['qtyNeededPerPro'];
            $part_number = $data['partNumber'];
            $new_qty = $data['newQty'];
            $ids_progress_product = explode(",", $data['idsProgressProduct']);
            $memo_ids = $data['memoId'];

            // Get qty per ids
            $qty_per_ids = [];
            foreach($ids_progress_product as $id) {
                $progress_product = TbProgressProduct::findOrFail($id);
                $id_quantity = $id.";".$progress_product->quantity;
                array_push($qty_per_ids, $id_quantity);
            }

            // Update qty per ids
            $qty_component_ids = [];
            foreach($qty_per_ids as $qty_per_id)  {
                $id = explode(';', $qty_per_id)[0];
                $qty = explode(';', $qty_per_id)[1];
                $qty_needed = $qty_needed_per_pro - $qty;

                if ($new_qty < $qty_needed) {
                    $qty += $new_qty;
                    $progress_product = TbProgressProduct::findOrFail($id);
                    $progress_product->quantity = $qty;
                    $progress_product->updated_at = now();
                    $progress_product->updated_by = Auth::user()->name;
                    $progress_product->save();

                    $qty_component_id = $progress_product->unit_id.';'.$progress_product->component_id.';'.$new_qty;
                    array_push($qty_component_ids, $qty_component_id);
                    break;
                } else {
                    $qty += $qty_needed;
                    $new_qty -= $qty_needed;

                    $progress_product = TbProgressProduct::findOrFail($id);
                    $progress_product->quantity = $qty;
                    $progress_product->updated_at = now();
                    $progress_product->updated_by = Auth::user()->name;
                    $progress_product->save();

                    $qty_component_id = $progress_product->unit_id.';'.$progress_product->component_id.';'.$qty_needed;
                    array_push($qty_component_ids, $qty_component_id);
                }
            }

            // Buat semifinish logs
            $semifinish_logs = [];
            foreach($qty_component_ids as $id) {
                $id_arr = explode(';', $id);
                $sl_unit_id = $id_arr[0];
                $sl_component_id = $id_arr[1];
                $sl_qty = $id_arr[2];

                $sl = VwMemoIdProgressProduct::select('*')
                                                ->where('unit_id', $sl_unit_id)
                                                ->where('component_id', $sl_component_id)
                                                ->get();

                if (count($sl) > 0 && $sl_qty > 0) {
                    $semifinish_log = [];
                    $semifinish_log["component_id"] = $sl->first()->component_id;
                    $semifinish_log["pn_component"] = $sl->first()->pn_component;
                    $semifinish_log["component_name"] = $sl->first()->component_name;
                    $semifinish_log["memo_id"] = $sl->first()->memo_id;
                    $semifinish_log["memo_number"] = $sl->first()->memo_number;
                    $semifinish_log["quantity"] = $sl_qty;

                    array_push($semifinish_logs, $semifinish_log);
                }
            }

            // Gabungkan semifinish log berdasar component_id dan memo_id
            $sls = [];
            foreach($memo_ids as $memo_id) {
                $sl_memo_id = [];
                foreach($semifinish_logs as $semifinish_log) {
                    if($memo_id == $semifinish_log['memo_id']) {
                        array_push($sl_memo_id, $semifinish_log);
                    }
                }
                $sum_quantity = 0;
                foreach($sl_memo_id as $sl) {
                    $sum_quantity += (int) $sl['quantity'];
                }
                if (count($sl_memo_id) > 0) {
                    $semifinish_log = [];
                    $semifinish_log["component_id"] = $sl_memo_id[0]["component_id"];
                    $semifinish_log["pn_component"] = $sl_memo_id[0]["pn_component"];
                    $semifinish_log["component_name"] = $sl_memo_id[0]["component_name"];
                    $semifinish_log["memo_id"] = $sl_memo_id[0]["memo_id"];
                    $semifinish_log["memo_number"] = $sl_memo_id[0]["memo_number"];
                    $semifinish_log["quantity"] = $sum_quantity;
                    array_push($sls, $semifinish_log);
                }
            }

            // Before Start
            // $component_id_1 = $semifinish_logs[0]['component_id'];
            // $memo_id_1 = $semifinish_logs[0]['memo_id'];
            // $sls = [];
            // $sl_qty_sum = 0;
            // foreach($semifinish_logs as $semifinish_log) {
            //     $component_id = $semifinish_log['component_id'];
            //     $memo_id = $semifinish_log['memo_id'];

            //     if ($component_id_1 == $component_id && $memo_id_1 == $memo_id) {
            //         $sl_qty_sum += $semifinish_log['quantity'];
            //     } else {
            //         $semifinish_log['quantity'] = $sl_qty_sum;
            //         array_push($sls, $semifinish_log);
            //     }
            // }
            // $semifinish_log['quantity'] = $sl_qty_sum;
            // array_push($sls, $semifinish_log);
            // Before End


            // Insert semifinish logs
            foreach($sls as $sl) {
                $sf_log = new TbSemifinishLog();
                $sf_log->component_id = $sl["component_id"];
                $sf_log->pn_component = $sl["pn_component"];
                $sf_log->component_name = $sl["component_name"];
                $sf_log->memo_id = $sl["memo_id"];
                $sf_log->memo_number = $sl["memo_number"];
                $sf_log->quantity = $sl["quantity"];
                $sf_log->from_location = null;
                $sf_log->to_location = null;
                $sf_log->type = "IN";
                $sf_log->created_by = Auth::user()->name;
                $sf_log->updated_by = Auth::user()->name;
                $sf_log->save();
            }

            // Get new qty per ids
            $new_qty_per_ids = [];
            foreach($ids_progress_product as $id) {
                $progress_product = TbProgressProduct::findOrFail($id);
                $id_quantity = $id.";".$progress_product->quantity.';'.$progress_product->unit->pro_number;
                array_push($new_qty_per_ids, $id_quantity);
            }

            // ------------------------- Update Stock PB Engine
            // foreach($memo_ids as $memo_id){
            //     $component_id = TbProgressProduct::where('id', $ids_progress_product[0])->first()->component_id;
            //     // Update qty semifinish inventory
            //     $qty_in = TbProgressProduct::where('component_id', $component_id)->sum('quantity');
            //     $qty_out = TbSemifinishLog::where('component_id', $component_id)->where('type', 'OUT')->sum('quantity');

            //     $inventory = TbSemifinishInventory::where('component_id', $component_id)->first();
            //     if($inventory != null){
            //         $inventory->update([
            //             'quantity' => $qty_in - $qty_out,
            //         ]);
            //     } else {
            //         $component = TbComponent::where('id', $component_id)->first();

            //         TbSemifinishInventory::create([
            //             'component_id' => $component->id,
            //             'pn_component' => $component->pn_component,
            //             'component_name' => $component->name,
            //             'quantity' => $qty_in - $qty_out,
            //             'created_by' => Auth::user()->name,
            //             'updated_by' => Auth::user()->name
            //         ]);
            //     }

            //     // Get PRO yang ada pada memo
            //     $pro = TbMemoPROPPC::where('memo_id', $memo_id)->get('pro_number')->toArray();

            //     // Get Unit berdasarkan PRO
            //     $unit = TbUnit::whereIn('pro_number', $pro)->get('id')->toArray();

            //     // Get sum quantitiy dari progres product berdasarkan pro yang ada pada memo
            //     $qty = TbProgressProduct::whereIn('unit_id', $unit)->where('component_id', $component_id)->groupBy('component_id')->sum('quantity');

            //     // Update qty done memo component
            //     $memo_component = TbMemoComponentPPC::where('memo_id', $memo_id)->where('component_id', $component_id)->first();

            //     if ($memo_component->is_fullfilled != 1) {
            //         $memo_component->update([
            //             'quantity_done' => (int) $qty
            //         ]);


            //         // check apakah quantity sudah full fill
            //         $count_pro = TbMemoPROPPC::where('memo_id', $memo_id)->count('id');

            //         if($memo_component->total_quantity == null || $memo_component->total_quantity == ''){
            //             $memo_component->update([
            //                 'total_quantity' => $memo_component->quantity*$count_pro
            //             ]);
            //         }

            //         if ($memo_component->quantity_done >= $memo_component->total_quantity) {
            //             $memo_component->update([
            //                 'is_fullfilled' => 1
            //             ]);
            //         }
            //     }

            //     // Update qty done memo component kanban
            //     $memo_kanban = TbMemoKanban::where('id_memo_ima', $memo_id)->first();
            //     $memo_component_kanban = TbMemoComponentKanban::where('id_memo', $memo_kanban->id)->where('part_number', $part_number)->first();
            //     if ($memo_component_kanban->status != 1) {
            //         $memo_component_kanban->update([
            //             'quantity_done' => (int) $qty
            //         ]);


            //         // check apakah quantity sudah full fill
            //         if ($memo_component_kanban->quantity_done >= $memo_component_kanban->quantity) {
            //             $memo_component_kanban->update([
            //                 'status' => 1
            //             ]);
            //         }
            //     }

            //     // check apakah memo sudah bisa di close
            //     $done_comp = TbMemoComponentPPC::where('memo_id', $memo_id)->where('is_fullfilled', 1)->count('id');
            //     $all_comp = TbMemoComponentPPC::where('memo_id', $memo_id)->count('id');

            //     if($done_comp == $all_comp){
            //         $memo = TbMemoPPC::where('id', $memo_id)->update([
            //             'is_closed' => 1
            //         ]);

            //         //update status memo ima
            //         $response = Http::post(env('ENV_IMA_API') . '/api/CloseMemo', [
            //             "MemoID" => $memo_id,
            //             "Created" => now(),
            //             "CreatedBy" => Auth::user()->name,
            //         ]);
            //         // dd($response);

            //         //update status memo kanban
            //         TbMemoKanban::where('id_memo_ima', $memo_id)->first()->update([
            //             'id_proses' => 3,
            //             'closed_at' => now(),
            //             'closed_by' => Auth::user()->name,
            //         ]);
            //     }
            // }

            $data = collect([
                'part_number' => $part_number,
                'new_qty_per_ids' => $new_qty_per_ids,
            ]);

            // Return
            return response()->json([
                'status' => 201,
                'message' => 'Success.',
                'data' => $data,
                'result' => $sls
            ]);
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'status' => 400,
                'message' => $error_message,
            ]);
        }
    }

    public function indexInventory()
    {
        try{
            $listProduct = TbProduct::select('id', 'name', 'pn_product')->get();
            $data = [
                'listProduct' => $listProduct
            ];
            return view('PBEngine.semifinish.index-inventory')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error Request, Exception Error!'
            ]);
        }
    }

    public function inventoryDetail($id)
    {
        try{
            $semifinishLog = TbSemifinishLog::where('component_id', $id)->orderBy('created_at', 'desc')->get();
            $component = TbSemifinishInventory::where('component_id', $id)->first();
            $data = [
                'semifinishLog' => $semifinishLog,
                'component' => $component
            ];
            return view('PBEngine.semifinish.inventory-detail')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error Request, Exception Error!'
            ]);
        }
    }

    public function inventoryDetailExport(Request $request)
    {
        try{
            $component = TbSemifinishInventory::where('component_id', $request->q)->first();
            return Excel::download(new InventoryDetailExport($request->q), 'InventoryDetail_'.$component->pn_component.'_'.date('Ymd').'.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error Request, Exception Error!'
            ]);
        }
    }

    public function getSemifinishInventory()
    {
        $data = TbSemifinishInventory::get();
        return response()->json($data);
    }

    public function getSemifinishInventoryProduct($type, $product)
    {
        $getListComp = TbMatrixProdComp::where('product_id', $product)->pluck('component_id')->toArray();
        if($type == 'cek'){
            if($getListComp){
                $getListInventory = TbSemifinishInventory::whereIn('component_id', $getListComp)->get();
                if($getListInventory){
                    return response()->json(['status' => 1, 'data' => $getListInventory]);
                }else{
                    return response()->json(['status' => 0, 'msg' => 'Semifinish Inventory from selected product is not found!']);
                }
            }else{
                return response()->json(['status' => 0, 'msg' => 'Component from selected product is not found!']);
            }
        }else{
            $getListInventory = TbSemifinishInventory::whereIn('component_id', $getListComp)->get();
            return response()->json($getListInventory);
        }
    }

    public function indexMonitoring()
    {
        try{
            if($this->PermissionActionMenu('semifinish/monitoring')->r==1){
                $listMemo = TbMemoPPC::selectRaw('tb_memo_ppc.id,tb_memo_ppc.memo_number, sum(b.quantity_done) as quantity_done, sum(b.total_quantity) as total_quantity')
                                ->leftJoin('tb_memo_component_ppc as b', 'b.memo_id', '=', 'tb_memo_ppc.id')
                                ->groupBy('tb_memo_ppc.memo_number')
                                ->get();
                $listProduct = TbProduct::select('id','name')->get();
                $data = [
                    'listProduct' => $listProduct,
                    'listMemo' => $listMemo
                ];
                return view('PBEngine.semifinish.index-monitoring')->with('data', $data);
            }else{
                return redirect()->back()->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Error Request, Access Denied!'
                ]);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error Request, Exception Error!'
            ]);
        }
    }

    public function detailMonitoring($memo_id)
    {
        try{
            if($this->PermissionActionMenu('semifinish/monitoring')->v==1){
                $listPROMemo = TbMemoPROPPC::where('memo_id', $memo_id)->get();
                $getProductNumberMemo = TbMemoPPC::leftJoin('tb_product as a', 'a.id', '=', 'tb_memo_ppc.product_id')->where('tb_memo_ppc.id', $memo_id)->first();
                $subProcessPerProduct = VwPartNumberSubProccess::where('product_number', $getProductNumberMemo['pn_product'])->groupBy('subproses')->get();
                $subProcessPerProductAll = VwPartNumberSubProccess::where('product_number', $getProductNumberMemo['pn_product'])->groupBy('subproses')->get();
                $subProcessPerProductAll->push((object)[
                    'subproses' => 'All'
                ]);

                foreach($listPROMemo as $lpm){
                    $dataSubProcess = [];
                    foreach($subProcessPerProduct as $subProcess){
                        $componentSubProcess = VwPartNumberSubProccess::where('product_number', $getProductNumberMemo['pn_product'])->where('subproses', $subProcess->subproses)->get('part_number')->toArray();
                        $getReqQuantity = TbMemoComponentPPC::where('memo_id', $memo_id)->whereIn('pn_component', $componentSubProcess)->sum('quantity');
                        $getUnitID = TbUnit::where('pro_number', $lpm->pro_number)->first();
                        $getComponentID = TbComponent::whereIn('pn_component', $componentSubProcess)->get('id')->toArray();
                        $getActQuantity = TbProgressProduct::where('unit_id', $getUnitID->id)->whereIn('component_id', $getComponentID)->sum('quantity');
                        $dataSubProcess[] = [
                            'sub_process_name' => $subProcess->subproses,
                            'quantity_req' => $getReqQuantity,
                            'quantity_act' => $getActQuantity
                        ];
                    }

                    $componentAll = TbMatrixProdComp::where('product_id', $getProductNumberMemo['product_id'])->get('component_id')->toArray();
                    $getReqQuantityAll = TbMemoComponentPPC::where('memo_id', $memo_id)->whereIn('component_id', $componentAll)->sum('quantity');

                    $getUnitIDAll = TbUnit::where('pro_number', $lpm->pro_number)->first();
                    $getActQuantityAll = TbProgressProduct::where('unit_id', $getUnitIDAll->id)->whereIn('component_id', $componentAll)->sum('quantity');
                    $dataSubProcess[] = [
                        'sub_process_name' => 'All',
                        'quantity_req' => $getReqQuantityAll,
                        'quantity_act' => $getActQuantityAll
                    ];

                    $dataPRO[] = [
                        'pro_number' => $lpm->pro_number,
                        'sub_process' => $dataSubProcess
                    ];
                }

                $chartPRO;
                $chartQtyReq = array();
                $chartQtyAct = array();
                $chartSubProses = array();
                if($dataPRO){
                    foreach($dataPRO as $keysp => $dp){
                        if($keysp === 0){
                            $chartPRO = $dp['pro_number'];
                            foreach($dp['sub_process'] as $sp){
                                if($sp['sub_process_name'] != 'All'){
                                    array_push($chartSubProses, $sp['sub_process_name']);
                                    array_push($chartQtyReq, $sp['quantity_req']);
                                    array_push($chartQtyAct, $sp['quantity_act']);
                                }else{
                                    $totalQtyReq = $sp['quantity_req'];
                                    $qtyReqPie = $sp['quantity_req'];
                                    $qtyActPie = $sp['quantity_act'];
                                }
                            }
                            break;
                        }
                    }
                }
                $data = [
                    'memo_id' => $memo_id,
                    'memo' => $getProductNumberMemo,
                    'listPRO' => $dataPRO,
                    'listSubProcess' => $subProcessPerProductAll,
                    'chartData' => [
                        'chartPRO' => $chartPRO,
                        'chartSubProses' => $chartSubProses,
                        'chartQtyReq' => $chartQtyReq,
                        'chartQtyAct' => $chartQtyAct,
                        'chartQtyReqPie' => $qtyReqPie,
                        'chartQtyActPie' => $qtyActPie,
                        'totalQtyReq' => $totalQtyReq
                    ]
                ];
                return view('PBEngine.semifinish.detail-monitoring')->with('data', $data);
            }else{
                return redirect()->back()->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Error Request, Access Denied!'
                ]);
            }
        }catch(Exception $e){
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error Request, Exception Error!'
            ]);
        }
    }

    public function getChartDataPRO(Request $request)
    {
        $getProductNumberMemo = TbMemoPPC::leftJoin('tb_product as a', 'a.id', '=', 'tb_memo_ppc.product_id')->where('tb_memo_ppc.id', $request->memo_id)->first();
        $subProcessPerProduct = VwPartNumberSubProccess::where('product_number', $getProductNumberMemo['pn_product'])->groupBy('subproses')->get();

        $dataSubProcess = [];
        foreach($subProcessPerProduct as $subProcess){
            $componentSubProcess = VwPartNumberSubProccess::where('product_number', $getProductNumberMemo['pn_product'])->where('subproses', $subProcess->subproses)->get('part_number')->toArray();
            $getReqQuantity = TbMemoComponentPPC::where('memo_id', $request->memo_id)->whereIn('pn_component', $componentSubProcess)->sum('quantity');
            $getUnitID = TbUnit::where('pro_number', $request->pro_number)->first();
            $getComponentID = TbComponent::whereIn('pn_component', $componentSubProcess)->get('id')->toArray();
            $getActQuantity = TbProgressProduct::where('unit_id', $getUnitID->id)->whereIn('component_id', $getComponentID)->sum('quantity');
            $dataSubProcess[] = [
                'sub_process_name' => $subProcess->subproses,
                'quantity_req' => $getReqQuantity,
                'quantity_act' => $getActQuantity
            ];
        }

        $componentAll = TbMatrixProdComp::where('product_id', $getProductNumberMemo['product_id'])->get('component_id')->toArray();
        $getReqQuantityAll = TbMemoComponentPPC::where('memo_id', $request->memo_id)->whereIn('component_id', $componentAll)->sum('quantity');
        $getUnitIDAll = TbUnit::where('pro_number', $request->pro_number)->first();
        $getActQuantityAll = TbProgressProduct::where('unit_id', $getUnitIDAll->id)->whereIn('component_id', $componentAll)->sum('quantity');
        $dataSubProcess[] = [
            'sub_process_name' => 'All',
            'quantity_req' => $getReqQuantityAll,
            'quantity_act' => $getActQuantityAll
        ];
        $dataPRO[] = [
            'pro_number' => $request->pro_number,
            'sub_process' => $dataSubProcess
        ];

        $chartPRO;
        $chartQtyReq = array();
        $chartQtyAct = array();
        $chartSubProses = array();
        if($dataPRO){
            foreach($dataPRO as $keysp => $dp){
                if($keysp === 0){
                    $chartPRO = $dp['pro_number'];
                    foreach($dp['sub_process'] as $sp){
                        if($sp['sub_process_name'] != 'All'){
                            array_push($chartSubProses, $sp['sub_process_name']);
                            array_push($chartQtyReq, $sp['quantity_req']);
                            array_push($chartQtyAct, $sp['quantity_act']);
                        }else{
                            $totalQtyReq = $sp['quantity_req'];
                            $qtyPie[] = $sp['quantity_req'] - $sp['quantity_act'];
                            $qtyPie[] = $sp['quantity_act'];
                        }
                    }
                    break;
                }
            }
        }

        $chartData = [
            'chartPRO' => $chartPRO,
            'chartSubProses' => $chartSubProses,
            'chartQtyReq' => $chartQtyReq,
            'chartQtyAct' => $chartQtyAct,
            'chartQtyPie' => $qtyPie,
            'totalQtyReq' => $totalQtyReq
        ];

        return response()->json($chartData);
    }

    public function getListMemo($product)
    {
        $listMemo = TbMemoPPC::select('tb_memo_ppc.id','tb_memo_ppc.memo_number', 'b.quantity_done', 'b.total_quantity')
                            ->leftJoin('tb_memo_component_ppc as b', 'b.memo_id', '=', 'tb_memo_ppc.id')
                            ->where('tb_memo_ppc.product_id', $product)
                            ->groupBy('tb_memo_ppc.memo_number')
                            ->get();
        return response()->json($listMemo);
    }

    public function getListMemoAll()
    {
        $listMemo = TbMemoPPC::select('tb_memo_ppc.id','tb_memo_ppc.memo_number', 'b.quantity_done', 'b.total_quantity')
                                ->leftJoin('tb_memo_component_ppc as b', 'b.memo_id', '=', 'tb_memo_ppc.id')
                                ->groupBy('tb_memo_ppc.memo_number')
                                ->get();
        return response()->json($listMemo);
    }
}
