<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use App\Models\Table\PBEngine\TbMemoPPC;
use App\Models\Table\PBEngine\TbNotification;
use App\Models\Table\PBEngine\TbNotificationReader;
use App\Models\View\VwUserRoleGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    
    public function index()
    {
        $user = VwUserRoleGroup::where('user', Auth::user()->id)->where('apps', Auth::user()->accessed_app)->first();
        $notification = TbNotification::with('reader', 'reader.user')->where('role_name', 'ALL')->orWhere('role_name', $user->role_name)->get();
        // dd($notification);

        foreach ($notification as $n) {
            $readed = TbNotificationReader::where('notification_id', $n->id)->where('read_by', Auth::user()->id)->groupBy('notification_id')->count('id');
            $n->setAttribute('unread', $readed > 0 ? 0 : 1);
        }


        $notif = $notification->sortBy([
            ['created_at', 'desc'],
            ['unread', 'desc'],

        ]);

        $data = [
            'notification' => $notif
        ];

        return view('PBEngine/notification/index')->with('data', $data);
    }

    public function store($memo){
        // notifikasi saat buat memo kanban pulling
        TbNotification::create([
            'role_name' => 'ALL',
            'reference_id' => $memo->id, //id memo
            'remark' => 'New Memo Semifinish has been created by Warehouse, prepare semifinish immediately',
            'type' => 'MEMO SEMIFINISH',
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
        ]);

        // notifikasi saat buat ticket memo raw material
        TbNotification::create([
            'role_name' => 'ALL',
            'reference_id' => $memo->id, //id memo
            'remark' => 'Memo Raw Material has been created into a ticket',
            'type' => 'MEMO RAW MATERIAL',
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
        ]);
    }

    public function show($id)
    {
        $notif = TbNotification::where('id', $id)->first();

        switch ($notif->type) {
            case 'MEMO COMPONENT':
                $url = 'memo-ppc';
                break;
            case 'MEMO RAW MATERIAL':
                $url = 'memo-pb';
                break;
            case 'MEMO SEMIFINISH':
                $url = 'memo-warehouse';
                break;
        }

        $reader = TbNotificationReader::where('notification_id', $id)->where('read_by', Auth::user()->id)->first();
        if ($reader == null) {
            TbNotificationReader::create([
                'notification_id' => $id,
                'read_by' => Auth::user()->id
            ]);
        }

        return redirect($url . '/detail/' . $notif->reference_id);
    }
}
