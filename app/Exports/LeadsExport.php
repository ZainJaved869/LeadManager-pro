<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Lead::where('tenant_id', auth()->user()->tenant_id)
            ->select('name', 'email', 'phone', 'company', 'source', 'value', 'stage_id', 'assigned_to')
            ->with(['stage:id,name', 'assignedTo:id,name'])
            ->get()
            ->map(function ($lead) {
                return [
                    'Name' => $lead->name,
                    'Email' => $lead->email,
                    'Phone' => $lead->phone,
                    'Company' => $lead->company,
                    'Source' => $lead->source,
                    'Value' => $lead->value,
                    'Stage' => $lead->stage->name ?? '',
                    'Assigned To' => $lead->assignedTo->name ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Company', 'Source', 'Value', 'Stage', 'Assigned To'];
    }
}