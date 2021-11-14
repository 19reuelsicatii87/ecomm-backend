<?php

namespace App\LeadExport;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class All implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Lead::all();
    }

    public function headings(): array
    {
        return [
            ['ID', 'FullName', 'Email Address', 'Phone Number', 'State', 'Status', 'Message', 'UpdatedDate', 'CreatedDate' ]
        ];
    }
}
