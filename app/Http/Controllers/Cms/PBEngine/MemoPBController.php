<?php

namespace App\Http\Controllers\Cms\PBEngine;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\View\VwUserRoleGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Table\PBEngine\TbUnit;
use App\Models\Table\CCR\TbMaterialCCR;
use App\Models\Table\PBEngine\TbMemoPPC;
use App\Models\Table\PBEngine\TbProduct;
use App\Models\Table\Kanban\TbMemoKanban;
use App\Models\Table\PBEngine\TbComponent;
use App\Models\View\CCR\VwCalaculateStock;
use App\Models\Table\PBEngine\TbMemoPROPPC;
use App\Models\View\CCR\VwStockRawMaterial;
use App\Models\Table\PBEngine\TbRawMaterial;
use App\Models\Table\PBEngine\TbCartMaterial;
use App\Models\Table\PBEngine\TbMatrixCompRaw;
use App\Models\Table\PBEngine\TbMatrixProdComp;
use App\Models\Table\PBEngine\TbMemoComponentPPC;
use App\Models\Table\Kanban\TbMemoComponentKanban;
use App\Models\Table\Kanban\TbMemoReferenceKanban;
use App\Models\View\Kanban\VwPartNumberSubProccess;
use App\Models\View\PBEngine\VwRawMaterialWithStock;
use App\Models\Table\Kanban\TbMemoNotificationKanban;
use App\Models\Table\Kanban\TbTicketDeliveryComponentKanban;
use App\Models\Procedure\PBEngine\SpGetRequirementRawMaterial;
use App\Models\Table\Kanban\TbTicketDeliveryComponentDetailKanban;

class MemoPBController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('memo-pb') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            if ($this->PermissionActionMenu('memo-pb')->r == 1) {
                $memo_kanban = TbMemoKanban::with('memoReference')->whereIn('id_proses', [50, 51, 52, 53])->orderBy('updated_at', 'DESC')->get();
                
                return view('PBEngine/memo-pb/index')->with('data', [
                    'memo_kanban' => $memo_kanban,
                ]);

            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            if ($this->PermissionActionMenu('memo-pb')->c == 1) {

                // $product = TbProduct::all();
                // $pn_raw_material = TbRawMaterial::get('pn_raw_material')->toArray();
                // $stockRawMaterial = VwStockRawMaterial::whereIn('material_number', $pn_raw_material)->get();

                // hapus data keranjang sebelumnya dari user tersebut
                TbCartMaterial::where('user_id', Auth::user()->id)->delete();

                return view('PBEngine/memo-pb/create')->with('data', [
                    // 'product' => $product,
                    // 'stockRawMaterial' => $stockRawMaterial,
                ]);

            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    // public function store(Request $request)
    // {
    //     $product = TbProduct::where('id', $request->product_hidden)->first();
    //     $memo_list = explode(',', $request->memo_list_hidden);
    //     $req_date = $request->requirement_date;

    //     $product_init = explode('-', $product->pn_product);
    //     $date = date('d.m.y');
    //     $count = TbMemoKanban::count('id') + 1;
    //     $memo_number = $product_init[0] . "/PB/WHS/" . $date . "/" . $count;

    //     $memo_quantity = TbCartMaterial::where('user_id', Auth::user()->id)->count('id');

    //     // create data memo
    //     $memo = TbMemoKanban::create([
    //         'memo_number' => $memo_number,
    //         'product_number' => $product->pn_product,
    //         'product_description' => $product->name,
    //         'requirement_date' => $req_date,
    //         'section' => 'PB',
    //         'id_proses' => 50,
    //         'memo_quantity' => $memo_quantity,
    //         'memo_quantity_done' => 0,
    //         'plant' => 'UCKR',
    //         'request_to' => 'Supervisor Warehouse',
    //         'created_by' => Auth::user()->name,
    //     ]);

    //     // create data memo component
    //     $cart = TbCartMaterial::where('user_id', Auth::user()->id)->get();
    //     foreach ($cart as $c) {
    //         // dd($c);
    //         TbMemoComponentKanban::create([
    //             'id_memo' => $memo->id,
    //             'part_number' => $c->pn_raw_material,
    //             'part_description' => $c->raw_material_description,
    //             'bahan' => $c->material_name,
    //             'tebal' => $c->thickness,
    //             'requirement_quantity' => $c->quantity,
    //             'quantity' =>  $c->quantity,
    //             'quantity_done' =>  0,
    //             'created_by' => Auth::user()->name,
    //         ]);

    //         $c->delete();
    //     }


    //     // create data memo reference
    //     foreach ($memo_list as $ml) {

    //         $memo_kanban = TbMemoKanban::where('id_memo_ima', $ml)->first();

    //         TbMemoReferenceKanban::create([
    //             'id_memo' => $memo->id,
    //             'id_memo_reference' => $memo_kanban->id,
    //             'created_by' => Auth::user()->name,
    //         ]);

    //         if ($memo_kanban->id_proses < 2) {
    //             TbMemoKanban::where('id', $memo_kanban->id)->update([
    //                 'id_proses' => 2
    //             ]);
    //         }
    //     }

    //     // create notification kanban
    //     $user_whs   = VwUserRoleGroup::distinct()->where('username', '!=', Auth::user()->name)
    //         ->where(function ($query) {
    //             $query->where('role_name', '=', 'Supervisor Warehouse Kanban')->orWhere('role_name', '=', 'Warehouse Pulling Kanban');
    //         })->get();

    //     foreach ($user_whs as $whs) {
    //         TbMemoNotificationKanban::insert([
    //             'id_memo'       => $memo->id,
    //             'issued_to'     => $whs->user,
    //             'purpose'       => 'Raw Material',
    //             'created_by'    => Auth::user()->name
    //         ]);
    //     }

    //     // update status memo ima
    //     foreach ($memo_list as $ml) {
    //         $response = Http::post(env('ENV_IMA_API') . '/api/OnProgressMemo', [
    //             "MemoID" => $ml,
    //             "Created" => now(),
    //             "CreatedBy" => Auth::user()->name,
    //         ]);
    //     }

    //     if ($memo) {
    //         $icon = "success";
    //         $title = "Create Memo Material";
    //         $text = "Memo material created successfully";
    //     } else {
    //         $icon = "error";
    //         $title = "Create Memo Material";
    //         $text = "Memo material failed to create";
    //     }

    //     return redirect(url('memo-pb'))->with('alert', [
    //         'icon'   => $icon,
    //         'title'   => $title,
    //         'text'   => $text,
    //     ]);

    //     try {
    //         if ($this->PermissionActionMenu('memo-pb')->c == 1) {
    //         } else {
    //             return redirect()->back();
    //         }
    //     } catch (Exception $e) {
    //         $this->ErrorLog($e);
    //         return redirect()->back();
    //     }
    // }

    public function store(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('memo-pb')->c == 1) {
                
                $memo_id = $request->memo_hidden; 
                $memoPPC = TbMemoPPC::where('id', $memo_id)->first();
                $product = TbProduct::where('id', $memoPPC->product_id)->first();
                $req_date = $request->requirement_date;

                $product_init = explode('-', $product->pn_product);
                $date = date('d.m.y');
                $count = TbMemoKanban::count('id') + 1;
                $memo_number = $product_init[0] . "/PB/WHS/" . $date . "/" . $count;

                $memo_quantity = TbCartMaterial::where('user_id', Auth::user()->id)->count('id');

                // create data memo
                $memo = TbMemoKanban::create([
                    'memo_number' => $memo_number,
                    'product_number' => $product->pn_product,
                    'product_description' => $product->name,
                    'requirement_date' => $req_date,
                    'section' => 'PB',
                    'id_proses' => 50,
                    'memo_quantity' => $memo_quantity,
                    'memo_quantity_done' => 0,
                    'plant' => 'UCKR',
                    'request_to' => 'Supervisor Warehouse',
                    'created_by' => Auth::user()->name,
                ]);

                // create data memo component
                $cart = TbCartMaterial::where('user_id', Auth::user()->id)->get();
                foreach ($cart as $c) {
                    TbMemoComponentKanban::create([
                        'id_memo' => $memo->id,
                        'part_number' => $c->pn_raw_material,
                        'part_description' => $c->raw_material_description,
                        'bahan' => $c->material_name,
                        'tebal' => $c->thickness,
                        'requirement_quantity' => $c->quantity,
                        'quantity' =>  $c->quantity,
                        'quantity_done' =>  0,
                        'created_by' => Auth::user()->name,
                    ]);

                    $c->delete();
                }

                // create data memo reference
                $memo_kanban = TbMemoKanban::where('id_memo_ima', $memoPPC->id)->first();

                TbMemoReferenceKanban::create([
                    'id_memo' => $memo->id,
                    'id_memo_reference' => $memo_kanban->id,
                    'created_by' => Auth::user()->name,
                ]);

                if ($memo_kanban->id_proses < 2) {
                    TbMemoKanban::where('id', $memo_kanban->id)->update([
                        'id_proses' => 2
                    ]);
                }

                // create notification kanban
                $user_whs   = VwUserRoleGroup::distinct()->where('username', '!=', Auth::user()->name)
                    ->where(function ($query) {
                        $query->where('role_name', '=', 'Supervisor Warehouse Kanban')->orWhere('role_name', '=', 'Warehouse Pulling Kanban');
                    })->get();

                foreach ($user_whs as $whs) {
                    TbMemoNotificationKanban::insert([
                        'id_memo'       => $memo->id,
                        'issued_to'     => $whs->user,
                        'purpose'       => 'Raw Material',
                        'created_by'    => Auth::user()->name
                    ]);
                }

                // update status memo ima
                $response = Http::post(env('ENV_IMA_API') . '/api/OnProgressMemo', [
                    "MemoID" => $memo->id,
                    "Created" => now(),
                    "CreatedBy" => Auth::user()->name,
                ]);

                if ($memo) {
                    $icon = "success";
                    $title = "Create Memo Material";
                    $text = "Memo material created successfully";
                } else {
                    $icon = "error";
                    $title = "Create Memo Material";
                    $text = "Memo material failed to create";
                }

                return redirect(url('memo-pb'))->with('alert', [
                    'icon'   => $icon,
                    'title'   => $title,
                    'text'   => $text,
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
            if ($this->PermissionActionMenu('memo-pb')->v == 1) {

                $memo = TbMemoKanban::where('id', $id)->first();
                $memo_reference = TbMemoReferenceKanban::with('memo')->where('id_memo', $id)->get();
                $memo_component = TbMemoComponentKanban::where('id_memo', $id)->get();
                $ticket_delivery = TbTicketDeliveryComponentKanban::where('id_memo', $id)->get();


                return view('PBEngine/memo-pb/detail')->with("data", [
                    'memo' => $memo,
                    'memo_reference' => $memo_reference,
                    'memo_component' => $memo_component,
                    'ticket_delivery'=> $ticket_delivery,
                ]);

            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }

    }

    //------------------------------------------------------------------------------ ajax function

    // mendapatkan detail tiket atas memo raw material
    public function getDetailTicket($id)
    {
        $ticket = TbTicketDeliveryComponentKanban::where('id', $id)->first();
        $ticket_detail = TbTicketDeliveryComponentDetailKanban::with('memoComponent')->where('id_ticket', $id)->get();

        $receiver = $ticket->accepted_date != null ? User::where('id', $ticket->accepted_by)->pluck('name')->first() : "-";
        $shipper = $ticket->delivered_by != null ? User::where('id', $ticket->delivered_by)->pluck('name')->first() : "-";
        $ticket->setAttribute('accepted_by_name', $receiver);
        $ticket->setAttribute('delivered_by_name', $shipper);
        $ticket->accepted_date = $ticket->accepted_date == null ? "-" : $ticket->accepted_date;
        // dd($ticket->toArray());

        // foreach ($ticket_detail as $td) {
        //     $component = TbMemoComponentKanban::where('id', $td->id_memo_component)->first();
        //     $td->setAttribute('part_name', $component->part_name);
        //     $td->setAttribute('part_description', $component->part_description);
        // }

        // dd($ticket_detail);

        $data = [
            'ticket' => $ticket,
            'ticket_detail' => $ticket_detail,
        ];

        // dd($data);

        return $data;
    }

    // [TIDAK DIGUNAKAN] mendapatkan data raw material dan stocknya
    public function getRawMaterialStock()
    {
        $rawMaterial = VwStockRawMaterial::all();
        return $rawMaterial;
    }

    // [TIDAK DIGUNAKAN] mendapatkan sub proses yang dimiliki produk
    public function getSubProsesByProduct($product_id)
    {
        $product =  TbProduct::where('id', $product_id)->first();
        $subproses = VwPartNumberSubProccess::where('product_number', $product->pn_product)->distinct()->get(['subproses']);
        return $subproses;
    }

    // mendapatkan data memo PPC berdasarkan produk
    public function getMemoPPC($product_id = "")
    {
        if ($product_id != "") {
            $memo = TbMemoPPC::where('product_id', $product_id)->where('is_closed', 0)->orderBY('created_at', 'desc')->get();
        } else {
            $memo = TbMemoPPC::where('is_closed', 0)->orderBY('created_at', 'desc')->get();
        }

        foreach($memo as $m){
            $memoPRO = TbMemoPROPPC::where('memo_id', $m->id)->orderBy('pro_number', 'asc')->get();
            $arrMemoPRO = $memoPRO->toArray();
            if(count($arrMemoPRO) > 0){
                $m->setAttribute('reference_pro', $memoPRO[0]['pro_number'].' - '.$memoPRO[count($memoPRO)-1]['pro_number']);
            } else {
                $m->setAttribute('reference_pro', ' - ');
            }
        }
        
        return $memo->toArray();
    }

    // mendapatkan pro yang digunakan pada memo tersebut
    public function getPRO($memo_id = "")
    {
        if ($memo_id != "") {
            $memo = TbMemoPROPPC::where('memo_id', $memo_id)->get();
            
        } else {
            $memo = TbMemoPROPPC::all();
        }

        foreach($memo as $m){
            $sn = TbUnit::where('pro_number', $m->pro_number)->get('serial_number');
            $m->setAttribute('serial_numbers', $sn->toArray());
        }

        return $memo->toArray();
    }

    // public function getMemoComponentPPC(Request $request)
    // {

    //     $memo_list = $request->memo_list;
    //     $product_id = $request->product_id;
    //     $sub_proses = $request->sub_proses;

    //     //get only component

    //     // $comp = TbMatrixProdComp::join('tb_component', 'tb_component.id', '=', 'tb_matrix_prod_comp.component_id')
    //     //     ->select(
    //     //         'tb_component.id',
    //     //         'tb_component.pn_component',
    //     //         'tb_component.name',
    //     //     )->where('product_id', $product_id)->distinct()->get();

    //     // foreach ($comp as $c) {

    //     //     $total = 0;

    //     //     // foreach ($memo_list as $id_memo) {
    //     //     for ($i = 0; $i < count($memo_list); $i++) {

    //     //         $unit = TbMemoPROPPC::where('memo_id', $memo_list[$i])->count('id');

    //     //         $memo_comp = TbMemoComponentPPC::where('component_id', $c->id)->where('memo_id', $memo_list[$i])->first();

    //     //         if ($memo_comp) {
    //     //             $quantity = $memo_comp->quantity;
    //     //             $total = $total + ($quantity * $unit);
    //     //         }

    //     //         // whereIn('memo_id', $memo_list)
    //     //         // ->where('component_id', $c->id)
    //     //         // ->groupBy('component_id')
    //     //         // ->sum('quantity');
    //     //     }

    //     //     $c->setAttribute('quantity', $total);
    //     // }

    //     $product = TbProduct::where('id', $product_id)->first();
    //     $part_number_of_sub_process = VwPartNumberSubProccess::where(function($query) use($product, $sub_proses){
            
    //         $query->where('product_number', $product->pn_product);

    //         if($sub_proses != 'All'){
    //             $query->where('subproses', $sub_proses);
    //         }
    //     })->distinct()->get('part_number')->toArray();

    //     // dd($part_number_of_sub_process);

    //     $comp_in_subproses = TbComponent::whereIn('pn_component', $part_number_of_sub_process)->get()->pluck('id')->toArray();
    //     $comp_in_memo = TbMemoComponentPPC::whereIn('memo_id', $memo_list)->distinct()->get()->pluck('component_id')->toArray();
        
    //     if($sub_proses == 'All'){
    //         $component = $comp_in_memo;
    //     } else {
    //         $component = array_intersect($comp_in_memo, $comp_in_subproses);
    //     }
    //     // dd($component);

    //     // dd($comp_in_subproses, $comp_in_memo, $component);

    //     $raw_material = TbMatrixCompRaw::join('tb_raw_material', 'tb_raw_material.id', '=', 'tb_matrix_comp_raw.raw_material_id')
    //         ->join('tb_matrix_prod_comp', 'tb_matrix_prod_comp.component_id', '=', 'tb_matrix_comp_raw.component_id')
    //         ->where('tb_matrix_prod_comp.product_id', $product_id)
    //         // ->whereIn('tb_matrix_prod_comp.component_id', $comp_in_memo)
    //         ->whereIn('tb_matrix_prod_comp.component_id', $component)
    //         ->distinct()
    //         ->get([
    //             'tb_matrix_comp_raw.raw_material_id',
    //             'tb_raw_material.pn_raw_material',
    //             'tb_raw_material.description',
    //         ]);

    //     // dd($raw_material->toArray());

    //     foreach ($raw_material as $rm) {
    //         // dapatkan stok raw material dari ccr
    //         $raw_material_ccr = VwStockRawMaterial::where('material_number', $rm->pn_raw_material)->first();

    //         $rm->setAttribute('stock', floatval(round($raw_material_ccr->stock, 2)));

    //         // cari componet berdasarkan product dan raw material nya
    //         $comp = TbMatrixCompRaw::join('tb_matrix_prod_comp', 'tb_matrix_prod_comp.component_id', '=', 'tb_matrix_comp_raw.component_id')
    //             ->join('tb_component', 'tb_component.id', '=', 'tb_matrix_comp_raw.component_id')
    //             ->select([
    //                 'tb_component.id',
    //                 'tb_component.pn_component',
    //                 'tb_component.name',
    //             ])
    //             ->where('tb_matrix_comp_raw.raw_material_id', $rm->raw_material_id)
    //             // ->where('tb_matrix_prod_comp.product_id', $product_id)
    //             ->whereIn('tb_matrix_comp_raw.component_id', $component)
    //             ->groupBy([
    //                 'tb_component.id',
    //                 'tb_component.pn_component',
    //                 'tb_component.name',
    //             ])
    //             ->get();
    //         // $comp = TbComponent::select([
    //         //         'tb_component.id',
    //         //         'tb_component.pn_component',
    //         //         'tb_component.name',
    //         //     ])
    //         //     ->whereIn('id', $component)
    //         //     ->where()
    //         //     ->get();

    //         // hitung quantity component yang dibutuhkan
    //         foreach ($comp as $c) {
    //             $total = 0;
    //             for ($i = 0; $i < count($memo_list); $i++) {

    //                 $unit = TbMemoPROPPC::where('memo_id', $memo_list[$i])->count('id');
    //                 $memo_comp = TbMemoComponentPPC::where('component_id', $c->id)->where('memo_id', $memo_list[$i])->first();

    //                 if ($memo_comp) {
    //                     $quantity = $memo_comp->quantity; 
    //                     $total = $total + ($quantity * $unit);
    //                 }
    //             }

    //             $c->setAttribute('quantity', $total);
    //         }
    //         $rm->setAttribute('component', $comp->toArray());
    //     }

    //     return $raw_material->toArray();
    // }

    // mendapatkan data raw material dan rekomendasinya
    public function getMemoComponentPPC(Request $request){
        try {
            if ($this->PermissionActionMenu('memo-pb')->d == 1) {

                $memo = $request->memo;

                $raw_material = VwRawMaterialWithStock::all();

                $pro = TbMemoPROPPC::where('memo_id', $memo)->first();

                // $arr = SpGetRequirementRawMaterial::get($pro->pro_number);

                foreach ($raw_material as $rm) {
                    // $raw_material_ccr = VwStockRawMaterial::where('material_number', $rm->pn_raw_material)->first();
                    $standart_qty = TbMaterialCCR::where('material_number', $rm->pn_raw_material)->whereNull('deletion_flag')->whereIn('production_order', $pro->toArray())->groupBy('material_number')->selectRaw('sum(requirement_quantity) as standart_qty')->value('standart_qty');
                    
                    // $rm->setAttribute('stock', 0);
                    // if($raw_material_ccr){
                    //     $rm->setAttribute('stock', floatval(round($raw_material_ccr->stock, 2)));
                    // } else {
                    //     $rm->setAttribute('stock', 0);
                    // }

                    // $rm->setAttribute('is_recomended', 1);
                    if($standart_qty > 0){
                        $rm->setAttribute('is_recomended', 1);
                    }else{
                        $rm->setAttribute('is_recomended', 0); 
                    }

                }

                $arr = $raw_material->toArray();
                array_multisort(
                    array_map(
                        static function ($element) {
                            return $element['is_recomended'];
                        },
                        $arr
                    ),
                    SORT_DESC,
                    $arr
                );

                return $arr;

            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
        
    }

    // mendapatkan data keranjang
    public function getMaterialCart(Request $request)
    {
        $memo_id = $request->memo_id;

        $pro = TbMemoPROPPC::where('memo_id', $memo_id)->get('pro_number');
        
        $cart = TbCartMaterial::where('user_id', Auth::user()->id)->get();
        foreach($cart as $c){
            $standart_qty = TbMaterialCCR::where('material_number', $c->pn_raw_material)->whereNull('deletion_flag')->whereIn('production_order', $pro->toArray())->groupBy('material_number')->selectRaw('sum(requirement_quantity) as standart_qty')->value('standart_qty');
            $c->setAttribute('standart_qty', floatval(round($standart_qty, 2)) );

            // select `quantity_done` from `memo_component` where `part_number` = 125-0060-152609-X and `id_memo` = (select * from `memo_reference` as `mr` inner join `memo` as `m` on `m`.`id` = `mr`.`id_memo_reference` where `m`.`id_memo_ima` = 4939 limit 1) limit 1)
            $done_qty = TbMemoComponentKanban::where('part_number', $c->pn_raw_material)->whereIn('id_memo', function($query) use($memo_id){
                $query->from('memo_reference as mr')->join('memo as m', 'm.id', '=', 'mr.id_memo_reference')->select('mr.id_memo')->where('m.id_memo_ima', $memo_id);
            })->value('quantity_done');
            $c->setAttribute('done_qty', floatval(round($done_qty, 2)) );
        }

        return $cart;
    }

    // tambah raw material pada keranjang
    public function addMaterialCart(Request $request)
    {
        $rawMaterial = $request->rawMaterial;
        $memoID = $request->memo_id;

        $pro = TbMemoPROPPC::where('memo_id', $memoID)->get('pro_number');
        $exist_raw_material = TbCartMaterial::where('user_id', Auth::user()->id)->where('pn_raw_material', '=', $rawMaterial)->first();
        $raw_material_ccr = VwStockRawMaterial::where('material_number', $rawMaterial)->first();

        if (!$exist_raw_material && $raw_material_ccr->stock > 0) {
            $raw_material = TbRawMaterial::with('material')->where('pn_raw_material', '=', $rawMaterial)->first();
            // dd($raw_material);

            $standart_qty = TbMaterialCCR::where('material_number', $rawMaterial)->whereNull('deletion_flag')->whereIn('production_order', $pro->toArray())->groupBy('material_number')->selectRaw('sum(requirement_quantity) as standart_qty')->value('standart_qty');
            $done_qty = TbMemoComponentKanban::where('part_number', $rawMaterial)->whereIn('id_memo', function($query) use($memoID){
                $query->from('memo_reference as mr')->join('memo as m', 'm.id', '=', 'mr.id_memo_reference')->select('mr.id_memo')->where('m.id_memo_ima', $memoID);
            })->value('quantity_done');
            $raw_material_ccr = VwStockRawMaterial::where('material_number', $rawMaterial)->first();
            $stock = floor($raw_material_ccr->stock);
            $qty = ceil($standart_qty - $done_qty);

            if( $stock <= $qty){
                $qty = $stock;
            } 

            TbCartMaterial::create([
                'raw_material_id' => $raw_material->id,
                'pn_raw_material' => $raw_material->pn_raw_material,
                'raw_material_description' => $raw_material->description,
                'thickness' => $raw_material->thickness,
                'material_name' => $raw_material->material->name,
                'quantity' => $qty,
                'user_id' => Auth::user()->id,
            ]);
            $status = 200;
        } else {
            $status = 403;
        }

        return $status;
    }

    //update data raw material pada keranjang
    public function updateMaterialCart(Request $request, $id)
    {
        // $id = $request->id;
        $quantity = $request->quantity;
        $cart = TbCartMaterial::where('user_id', Auth::user()->id)->where('id', $id)->first();

        $raw_material = VwStockRawMaterial::where('material_number', $cart->pn_raw_material)->first();
        if ($quantity <= $raw_material->stock) {
            $cart->update([
                "quantity" => $quantity
            ]);
            $status = 200;
        } else {
            $status = 403;
        }

        return $status;
    }

    //hapus data raw material pada keranjang
    public function deleteMaterialCart(Request $request)
    {
        $id = $request->id;

        $cart = TbCartMaterial::where('user_id', Auth::user()->id);

        if ($id != null) {
            $cart = $cart->where('id', $id)->first();
        }

        $cart->delete();

        if ($cart) {
            $status = 200;
        } else {
            $status = 400;
        }

        return http_response_code($status);
    }
}
