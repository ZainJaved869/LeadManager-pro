@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Calendar</h1>
            <p class="text-slate-500 text-sm">View and manage your tasks on a calendar.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> New Task
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden p-4">
        <div id="calendar"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($events),
            eventClick: function(info) {
                const id = info.event.id;
                if (id) {
                    window.location.href = '/tasks/' + id;
                }
            },
            eventDidMount: function(info) {
                // Add tooltip or custom styling
            }
        });
        calendar.render();
    });
</script>
@endpush
@endsection