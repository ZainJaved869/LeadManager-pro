<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $tenantId = auth()->user()->tenant_id;
        foreach ($rows as $row) {
            // Find or create stage by name
            $stage = null;
            if (isset($row['stage'])) {
                $stage = PipelineStage::firstOrCreate(
                    ['tenant_id' => $tenantId, 'name' => $row['stage']],
                    ['order' => 0]
                );
            }

            // Find assigned user by name
            $assignedTo = null;
            if (isset($row['assigned_to'])) {
                $assignedTo = User::where('tenant_id', $tenantId)
                    ->where('name', $row['assigned_to'])
                    ->value('id');
            }

            Lead::create([
                'tenant_id' => $tenantId,
                'name' => $row['name'] ?? 'Unknown',
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'company' => $row['company'] ?? null,
                'source' => $row['source'] ?? null,
                'value' => $row['value'] ?? null,
                'stage_id' => $stage?->id,
                'assigned_to' => $assignedTo,
                'created_by' => auth()->id(),
            ]);
        }
    }
}