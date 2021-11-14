<?php

namespace App\LeadExport;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Status implements FromQuery, WithHeadings
{
    use Exportable;

    public function whereStatus(String $status)
    {
        $this->status = $status;

        return $this;
    }

    public function headings(): array
    {
        return [
            ['ID', 'FullName', 'Email Address', 'Phone Number', 'State', 'Status', 'Message', 'UpdatedDate', 'CreatedDate']
        ];
    }

    public function query()
    {
        return Lead::query()->where('status', $this->status);     
    }


}
