<?php

namespace App\Imports;

use App\Models\Area;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class AreaImport implements ToModel
{

    public function model(array $row)
    {
        return new Area([
            'name'     => $row[0],
            'city'    => $row[1],
            'state'    => $row[2],
            'pincode'    => $row[3],
        ]);
    }
}

?>
