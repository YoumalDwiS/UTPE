<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\Kanban\TbMemoComponentKanban;
use App\Models\Table\Kanban\TbMemoKanban;
use App\Models\Table\Kanban\TbTicketDeliveryComponentDetailKanban;
use App\Models\Table\Kanban\TbTicketDeliveryComponentKanban;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class MemoWarehouseController extends Controller
{
    public function __contruct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('memo-warehouse') == 0) {
                return redirect()->back()->with('err_message', 'Access Denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $data = array();

        $memo_kanban = TbMemoKanban::whereIn('id_proses', [500, 501, 502, 503])->orderBy('updated_at', 'DESC')->get();
        $data = [
            'memo_kanban' => $memo_kanban,
        ];

        return view('PBEngine/memo-warehouse/index')->with([
            'data' => $data
        ]);
        try {
            if ($this->PermissionActionMenu('memo-pb')->r == 1) {
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

        $data = array();

        $memo = TbMemoKanban::where('id', $id)->first();
        if ($memo->id_proses == 500) {

            TbMemoKanban::where('id', $id)->update([
                'id_proses' => 502
            ]);
        }
        $memo_component = TbMemoComponentKanban::where('id_memo', $id)->get();
        $ticket_delivery = TbTicketDeliveryComponentKanban::where('id_memo', $id)->get();
        // dd($ticket_delivery);
        $data = [
            'memo' => $memo,
            'memo_component' => $memo_component,
            'ticket_delivery' => $ticket_delivery,
        ];

        return view('PBEngine/memo-warehouse/detail')->with("data", $data);

        try {
            if ($this->PermissionActionMenu('memo-pb')->v == 1) {
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back();
        }
    }

    //------------------------------------------------------------------------------ ajax function

    public function getDetailTicket($id)
    {
        $ticket = TbTicketDeliveryComponentKanban::where('id', $id)->first();
        $ticket_detail = TbTicketDeliveryComponentDetailKanban::with('memoComponent')->where('id_ticket', $id)->get();

        $receiver = $ticket->accepted_date != null ? User::where('id', $ticket->accepted_by)->pluck('name')->first() : "-";
        $shipper = $ticket->delivered_by != null ? User::where('id', $ticket->delivered_by)->pluck('name')->first() : "-";
        $ticket->setAttribute('accepted_by_name', $receiver);
        $ticket->setAttribute('delivered_by_name', $shipper);
        $ticket->accepted_date = $ticket->accepted_date == null ? "-" : $ticket->accepted_date;

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

        return $data;
    }
}
