<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\Memo;
use App\Helpers\LinkHelper;
use App\Models\Table\Kanban\TbMemoComponentKanban;
use App\Models\Table\Kanban\TbMemoKanban;
use App\Models\Table\Kanban\TbMemoPROKanban;
use App\Models\Table\PBEngine\TbMemoComponentPPC;
use App\Models\Table\PBEngine\TbMemoPPC;
use App\Models\Table\PBEngine\TbMemoPROPPC;
use App\Models\Table\PBEngine\TbProgressProduct;
use App\Models\Table\PBEngine\TbUnit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Auth;

class MemoPPCController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('memo-ppc') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            if ($this->PermissionActionMenu('memo-ppc')->r == 1) {
                return view('PBEngine/memo-ppc/index');
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
            if ($this->PermissionActionMenu('memo-ppc')->v == 1) {

                $response = Http::get(env('ENV_IMA_API') . '/api/GetDetailMemo', [
                    "id" => $id
                ]);
                $arr = json_decode($response, true);

                return view('PBEngine/memo-ppc/detail')->with('data', $arr);
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    // ajax reference function
    public function approve(Request $request, $id)
    {
        try {
            if ($this->PermissionActionMenu('memo-ppc')->u == 1) {
                // approve memo ima
                $response = Http::post(env('ENV_IMA_API') . '/api/ApproveMemo', [
                    "MemoID" => $id,
                    "Created" => now(),
                    "CreatedBy" => Auth::user()->name,
                ]);
                // $response = null;

                // store memo ima to memo ppc
                $response_detail_memo = Http::get(env('ENV_IMA_API') . '/api/GetDetailMemo', [
                    "id" => $id
                ]);
                $memo = json_decode($response_detail_memo, true);

                // dd($memo);

                TbMemoPPC::create([
                    'id' => $memo['ID'],
                    'description' => $memo['Description'],
                    'memo_type_id' => $memo['MemoTypeID'],
                    'memo_number' => $memo['MemoNumber'],
                    'memo_type' => $memo['MemoType'],
                    'product_id' => $memo['ProductID'],
                    'product_name' => $memo['ProductName'],
                    'product_number' => $memo['PNProduct'],
                    'created_at' => $memo['Created'],
                    'created_by' => $memo['CreatedBy'],
                    'updated_at' => $memo['Created'],
                    'updated_by' => $memo['CreatedBy'],
                ]);

                $datetime = new DateTime($memo['Created']);
                $datetime->modify('+7 day');

                $memoKanban = TbMemoKanban::create([
                    'id_memo_ima' => $memo['ID'],
                    'memo_number' => $memo['MemoNumber'],
                    'hal' => $memo['Description'],
                    'id_product_number' => $memo['ProductID'],
                    'product_number' => $memo['PNProduct'],
                    'product_description' => $memo['ProductName'],
                    'id_memo_type' => $memo['MemoTypeID'],
                    'requirement_date' => $datetime,
                    'section' => 'PPC',
                    'id_proses' => 1,
                    'total_pro' => count($memo['MemoPRO']),
                    'memo_quantity' => count($memo['MemoComponent']),
                    'memo_quantity_done' => 0,
                    'plant' => 'UCKR',
                    'request_to' => 'PB',
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),
                    'created_by' => $memo['CreatedBy'],
                    'created_at' => $memo['Created'],
                ]);

                foreach ($memo['MemoPRO'] as $key => $pro) {
                    TbMemoPROPPC::create([
                        'id' => $pro['ID'],
                        'memo_id' => $pro['MemoID'],
                        'pro_id' => $pro['PROID'],
                        'pro_number' => $pro['PRONumber'],
                        'quantity' => $pro['Quantity'],
                        'created_at' => $pro['Created'],
                        'created_by' => $pro['CreatedBy'],
                        'updated_at' => $pro['LastModified'],
                        'updated_by' => $pro['LastModifiedBy'],
                    ]);

                    foreach ($pro['SerialNumbers'] as $sn) {
                        TbMemoPROKanban::create([
                            'id_memo' => $memoKanban->id,
                            'production_order' => $pro['PRONumber'],
                            'serial_number' => $sn,
                            'quantity' => $pro['Quantity'],
                            'created_at' => $pro['Created'],
                            'created_by' => $pro['CreatedBy'],
                            'updated_at' => $pro['LastModified'],
                            'updated_by' => $pro['LastModifiedBy'],
                        ]);

                        $unit = TbUnit::where('serial_number', $sn)->where('pro_number', $pro['PRONumber'])->first();
                        if (!$unit) {
                            $unit = TbUnit::create([
                                'serial_number' => $sn,
                                'pro_number' => $pro['PRONumber'],
                                'product_id' => $memo['ProductID'],
                                'status' => 1,
                                'created_by' => $pro['CreatedBy'],
                                'updated_at' => $pro['LastModified'],
                                'updated_by' => $pro['LastModifiedBy'],
                            ]);
                        }

                        foreach ($memo['MemoComponent'] as $comp) {
                            TbProgressProduct::create([
                                'unit_id' => $unit->id,
                                'component_id' => $comp['ComponentID'],
                                'quantity' => 0,
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name,
                            ]);
                        }
                    }
                }

                foreach ($memo['MemoComponent'] as $key => $comp) {
                    TbMemoComponentPPC::create([
                        'id' => $comp['ID'],
                        'memo_id' => $comp['MemoID'],
                        'component_id' => $comp['ComponentID'],
                        'pn_component' => $comp['PartNumber'],
                        'component_name' => $comp['PartName'],
                        'quantity' => $comp['Quantity'],
                        'quantity_done' => 0,
                        'total_quantity' => $comp['Quantity']*count($memo['MemoPRO']),
                        'is_fullfill' => 0,
                        'status' => $comp['Status'],
                        'created_at' => $comp['Created'],
                        'created_by' => $comp['CreatedBy'],
                        'updated_at' => $comp['LastModified'],
                        'updated_by' => $comp['LastModifiedBy'],
                    ]);

                    TbMemoComponentKanban::create([
                        'id_memo' => $memoKanban->id,
                        'part_number' => $comp['PartNumber'],
                        'part_description' => $comp['PartName'],
                        'requirement_quantity' => $comp['Quantity'],
                        'quantity' => $comp['Quantity'] * count($memo['MemoPRO']),
                        'created_at' => $comp['Created'],
                        'created_by' => $comp['CreatedBy'],
                    ]);
                }

                // $icon = "success";
                // $title = "Approved";
                // $text = "Managed to approve the memo";
                if ($response->getStatusCode() == 200) {
                    $icon = "success";
                    $title = "Approved";
                    $text = "Managed to approve the memo";
                } else {
                    $icon = "error";
                    $title = "Approved";
                    $text = "Fail to approve the memo";
                }

                return redirect()->back()->with('alert', [
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

    public function reject(Request $request, $id)
    {
        try {
            if ($this->PermissionActionMenu('memo-ppc')->u == 1) {

                $response = Http::post(env('ENV_IMA_API') . '/api/RejectMemo', [
                    "MemoID" => $id,
                    "Feedback" => $request->feedback,
                    "Created" => now(),
                    "CreatedBy" => Auth::user()->name
                ]);

                if ($response->getStatusCode() == 200) {
                    $icon = "success";
                    $title = "Rejected";
                    $text = "Managed to reject the memo";
                } else {
                    $icon = "danger";
                    $title = "Rejected";
                    $text = "Fail to reject the memo";
                }

                return redirect()->back()->with('alert', [
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

    //API HTTP CLIENT INSERT DATA KANBAN AFTER APPROVAL IMA
    // public function cekAPI()
    // {
    //     // dd("tes");
    //     $response = Http::get('http://10.48.26.42/satria-kanban/api/kanban-store-ima', [
    //         'ID'            => '4586',
    //         'Description'   => 'XPRO COAL 120. Req. selesai PB => 20 Januari 2022. Supply ke Fabrikasi Plant 1.',
    //         'MemoType'      => 'Normal',
    //         'id_memo_type'  => 1, //TAMABAHAN
    //         'PNProduct'     => "XXX",
    //         'ProductName'   => 'X-PRO COAL 120 CuM HD 785-7',
    //         'Created'       => "2021-11-30T13:06:32.91",
    //         'CreatedBy'     => 'prita',
    //         'TotalQuantityPRO' => 33,
    //         'memoPRO'       => [
    //             [
    //                 "MemoID"            => 4586,
    //                 "PRONumber"         => "210000008031",
    //                 "Quantity"          => 1,
    //                 "Created"           => "2021-11-30T13:06:32.927",
    //                 "CreatedBy"         => "prita",
    //                 "LastModified"      => "2021-11-30T13:06:32.927",
    //                 "LastModifiedBy"    => "prita"
    //             ],
    //             [
    //                 "MemoID"            => 4586,
    //                 "PRONumber"         => "210000008032",
    //                 "Quantity"          => 1,
    //                 "Created"           => "2021-11-30T13:06:32.927",
    //                 "CreatedBy"         => "prita",
    //                 "LastModified"      => "2021-11-30T13:06:32.927",
    //                 "LastModifiedBy"    => "prita"
    //             ],
    //             [
    //                 "MemoID"            => 4586,
    //                 "PRONumber"         => "210000008033",
    //                 "Quantity"          => 1,
    //                 "Created"           => "2021-11-30T13:06:32.927",
    //                 "CreatedBy"         => "prita",
    //                 "LastModified"      => "2021-11-30T13:06:32.927",
    //                 "LastModifiedBy"    => "prita"
    //             ]
    //         ],
    //         'memoComponent'     => [
    //             [
    //                 "MemoID"            => 4586,
    //                 "ComponentID"       => 711,
    //                 'bahan'             => 'GRADE 700', //TAMABAHAN
    //                 "PartNumber"        => "R9L003-B1171000",
    //                 "PartName"          => "PLATE",
    //                 "IsInHouse"         => true,
    //                 "Status"            => 0,
    //                 "Quantity"          => 1,
    //                 "Created"           => "2021-11-30T13:06:32.99",
    //                 "CreatedBy"         => "prita",
    //                 "LastModified"      => "2021-11-30T13:06:32.99",
    //                 "LastModifiedBy"    => "prita"
    //             ],
    //             [
    //                 "MemoID"            => 4586,
    //                 "ComponentID"       => 712,
    //                 'bahan'             => 'GRADE 700',
    //                 "PartNumber"        => "R9L003-B1172000",
    //                 "PartName"          => "PLATE",
    //                 "IsInHouse"         => true,
    //                 "Status"            => 0,
    //                 "Quantity"          => 1,
    //                 "Created"           => "2021-11-30T13:06:33.007",
    //                 "CreatedBy"         => "prita",
    //                 "LastModified"      => "2021-11-30T13:06:33.007",
    //                 "LastModifiedBy"    => "prita"
    //             ],
    //             [
    //                 "MemoID"            => 4586,
    //                 "ComponentID"       => 713,
    //                 'bahan'             => 'GRADE 700',
    //                 "PartNumber"        => "R9L003-B1173000",
    //                 "PartName"          => "PLATE",
    //                 "IsInHouse"         => true,
    //                 "Status"            => 0,
    //                 "Quantity"          => 2,
    //                 "Created"           => "2021-11-30T13:06:33.007",
    //                 "CreatedBy"         => "prita",
    //                 "LastModified"      => "2021-11-30T13:06:33.007",
    //                 "LastModifiedBy"    => "prita"
    //             ]
    //         ]
    //     ]);

    //     return $response;
    // }
}
