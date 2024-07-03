<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Table\PBEngine\TbRawMaterial;

class RawMaterialImport implements ToModel
{
    public function model(array $row)
    {
        return new TbRawMaterial([
            //
        ]);
    }
}
