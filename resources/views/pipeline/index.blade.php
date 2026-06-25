@extends('layouts.app')

@section('title', 'Pipeline')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sales Pipeline</h1>
            <p class="text-slate-500 text-sm">Drag and drop leads to move them through stages.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('pipeline.stages.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-cog mr-2"></i> Manage Stages
            </a>
            <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
                <i class="fas fa-plus mr-2"></i> New Lead
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Kanban Board - Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="kanban-board">
        <!-- Unassigned Leads Column -->
        @if($unassignedLeads->count() > 0)
            <div class="bg-slate-100 rounded-xl p-3 shadow-sm">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-slate-700">Unassigned</h3>
                    <span class="text-xs bg-slate-300 text-slate-700 px-2 py-1 rounded-full">{{ $unassignedLeads->count() }}</span>
                </div>
                <div class="space-y-2 min-h-[100px] drop-zone" data-stage-id="">
                    @foreach($unassignedLeads as $lead)
                        <div class="bg-white rounded-lg shadow-sm p-3 border border-slate-200 cursor-move lead-card hover:shadow transition" data-lead-id="{{ $lead->id }}">
                            <p class="font-medium text-slate-800">{{ $lead->name }}</p>
                            <p class="text-xs text-slate-500">{{ $lead->email ?? '' }}</p>
                            <div class="flex justify-between items-center mt-2 text-xs text-slate-400">
                                <span>${{ number_format($lead->value ?? 0, 2) }}</span>
                                <span>{{ $lead->assignedTo->name ?? 'Unassigned' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Stage Columns -->
        @forelse($stages as $stage)
            <div class="bg-slate-100 rounded-xl p-3 shadow-sm">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background:{{ $stage->color }}"></span>
                        <h3 class="font-semibold text-slate-700">{{ $stage->name }}</h3>
                    </div>
                    <span class="text-xs bg-slate-300 text-slate-700 px-2 py-1 rounded-full">{{ $stage->leads->count() }}</span>
                </div>
                <div class="space-y-2 min-h-[100px] drop-zone" data-stage-id="{{ $stage->id }}">
                    @foreach($stage->leads as $lead)
                        <div class="bg-white rounded-lg shadow-sm p-3 border border-slate-200 cursor-move lead-card hover:shadow transition" data-lead-id="{{ $lead->id }}">
                            <p class="font-medium text-slate-800">{{ $lead->name }}</p>
                            <p class="text-xs text-slate-500">{{ $lead->email ?? '' }}</p>
                            <div class="flex justify-between items-center mt-2 text-xs text-slate-400">
                                <span>${{ number_format($lead->value ?? 0, 2) }}</span>
                                <span>{{ $lead->assignedTo->name ?? 'Unassigned' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-slate-400">
                <i class="fas fa-inbox text-4xl block mb-3"></i>
                No stages found. 
                <a href="{{ route('pipeline.stages.create') }}" class="text-yellow-600 hover:underline font-medium">Create your first stage</a>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZones = document.querySelectorAll('.drop-zone');
        dropZones.forEach(zone => {
            new Sortable(zone, {
                group: 'leads',
                animation: 200,
                ghostClass: 'bg-yellow-100/50',
                onEnd: function(evt) {
                    const leadId = evt.item.dataset.leadId;
                    const stageId = evt.to.dataset.stageId;

                    evt.item.style.opacity = '0.5';

                    fetch('{{ route("pipeline.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            lead_id: leadId,
                            stage_id: stageId || null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => location.reload(), 400);
                        } else {
                            alert('Failed to move lead.');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        location.reload();
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection