<?php

namespace Database\Seeders;

use App\Models\PipelineStage;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PipelineStageSeeder extends Seeder
{
    public function run()
    {
        $stages = [
            'New Lead',
            'Contacted',
            'Qualified',
            'Proposal Sent',
            'Negotiation',
            'Won',
            'Lost'
        ];

        $colors = [
            '#3B82F6', // Blue
            '#8B5CF6', // Purple
            '#EC4899', // Pink
            '#F59E0B', // Amber
            '#EF4444', // Red
            '#10B981', // Green
            '#6B7280'  // Gray
        ];

        foreach (Tenant::all() as $tenant) {
            foreach ($stages as $index => $stage) {
                PipelineStage::create([
                    'tenant_id' => $tenant->id,
                    'name' => $stage,
                    'order' => $index + 1,
                    'color' => $colors[$index] ?? '#6B7280',
                ]);
            }
        }
    }
}