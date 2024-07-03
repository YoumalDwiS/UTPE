<?php

namespace App\Http\Livewire;

use App\Models\Table\PBEngine\TbSafetyFactorCapacity;
use Livewire\Component;

class SearchableDropdown extends Component
{
    public $search = '';
    public $selectedItem;

    public function render()
    {
        $items = TbSafetyFactorCapacity::where('sfc_value', 'like', '%' . $this->search . '%')->get(); // Ganti YourModel dengan model yang sesuai
        return view('livewire.searchable-dropdown', ['items' => $items]);
    }

    public function selectItem($id)
    {
        $this->selectedItem = $id;
    }
}
