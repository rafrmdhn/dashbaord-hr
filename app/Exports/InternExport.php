<?php

namespace App\Exports;

use App\Models\Intern;
use Maatwebsite\Excel\Concerns\FromCollection;

class InternExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Intern::all();
    }
}
