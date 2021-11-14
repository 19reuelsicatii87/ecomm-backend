<?php

namespace App\LeadExport;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StageTerm implements FromQuery, WithHeadings
{
    use Exportable;

    public function whereStage(String $stage)
    {
        $this->stage = $stage;

        return $this;
    }

    public function whereTerm(String $term)
    {
        $this->term = $term;

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
        return Lead::query()
        ->where('stage', $this->stage)
        ->where('fullname', $this->term)
        ->orWhere('emailaddress', $this->term);
    }


}
