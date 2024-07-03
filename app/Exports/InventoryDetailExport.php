<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Table\PBEngine\TbSemifinishLog;
use App\Models\Table\PBEngine\TbSemifinishInventory;

class InventoryDetailExport implements FromView
{
    protected $componentId;

    public function __construct($componentId){
        $this->componentId = $componentId;
    }

    public function view(): View
    {
        $semifinishLog = TbSemifinishLog::where('component_id', $this->componentId)->orderBy('created_at', 'desc')->get();
        $component = TbSemifinishInventory::where('component_id', $this->componentId)->first();
        $data = [
            'semifinishLog' => $semifinishLog,
            'component' => $component
        ];
        return view('PBEngine.exports.inventory-detail', $data);
    }
}
