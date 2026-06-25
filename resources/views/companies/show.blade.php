@extends('layouts.app')

@section('title', 'Company Details')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $company->name }}</h1>
            <p class="text-slate-500 text-sm">Company details and contacts.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Company Info -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h3 class="text-slate-700 font-semibold">Company Information</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-slate-500 font-medium">Email</span><p class="text-slate-800">{{ $company->email ?? '-' }}</p></div>
            <div><span class="text-sm text-slate-500 font-medium">Phone</span><p class="text-slate-800">{{ $company->phone ?? '-' }}</p></div>
            <div><span class="text-sm text-slate-500 font-medium">Website</span><p class="text-slate-800">{{ $company->website ?? '-' }}</p></div>
            <div><span class="text-sm text-slate-500 font-medium">Industry</span><p class="text-slate-800">{{ $company->industry ?? '-' }}</p></div>
            <div class="col-span-2"><span class="text-sm text-slate-500 font-medium">Address</span><p class="text-slate-800">{{ $company->address ?? '-' }}</p></div>
        </div>
    </div>

    <!-- Contacts Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200/60 flex justify-between items-center">
            <h3 class="text-slate-700 font-semibold">
                <i class="fas fa-users mr-2 text-yellow-500"></i> Contacts
            </h3>
            <a href="{{ route('contacts.create', $company) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-plus mr-1"></i> Add Contact
            </a>
        </div>
        <div class="p-6">
            @if($company->contacts->count() > 0)
                <div class="space-y-3">
                    @foreach($company->contacts as $contact)
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 last:border-0">
                            <div>
                                <p class="font-medium text-slate-800">{{ $contact->full_name }}</p>
                                <p class="text-sm text-slate-500">{{ $contact->position ?? '' }} {{ $contact->email ? '· ' . $contact->email : '' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($contact->is_primary)
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Primary</span>
                                @endif
                                <a href="{{ route('contacts.edit', [$company, $contact]) }}" class="text-yellow-600 hover:text-yellow-800"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('contacts.destroy', [$company, $contact]) }}" method="POST" onsubmit="return confirm('Delete this contact?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent border-0 cursor-pointer"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-sm">No contacts for this company. <a href="{{ route('contacts.create', $company) }}" class="text-yellow-600 hover:underline">Add one</a></p>
            @endif
        </div>
    </div>

    <!-- ====== COMMUNICATIONS SECTION ====== -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 flex justify-between items-center">
            <h3 class="text-slate-700 font-semibold">
                <i class="fas fa-history mr-2 text-yellow-500"></i> Communication History
            </h3>
        </div>
        <div class="p-6">
            <!-- Add Communication Form -->
            <form method="POST" action="{{ route('communications.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                @csrf
                <input type="hidden" name="communicable_type" value="company">
                <input type="hidden" name="communicable_id" value="{{ $company->id }}">

                <select name="type" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="call">📞 Call</option>
                    <option value="email">✉️ Email</option>
                    <option value="meeting">🤝 Meeting</option>
                    <option value="note">📝 Note</option>
                </select>
                <input type="text" name="subject" placeholder="Subject (optional)" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                <input type="date" name="date" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                <input type="time" name="time" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                <input type="text" name="duration" placeholder="Duration (e.g., 15 min)" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                <input type="text" name="direction" placeholder="Direction (incoming/outgoing)" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                <textarea name="body" rows="2" placeholder="Details..." class="md:col-span-3 rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500"></textarea>
                <button type="submit" class="md:col-span-3 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Log Communication
                </button>
            </form>

            <!-- Communications List -->
            <div class="space-y-3">
                @forelse($company->communications->sortByDesc('date') as $comm)
                    <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $comm->type_color }}">
                                    {{ $comm->type_label }}
                                </span>
                                <span class="text-sm font-medium text-slate-800">{{ $comm->subject }}</span>
                                <span class="text-xs text-slate-400">{{ $comm->date ? $comm->date->format('M d, Y') : '' }}</span>
                            </div>
                            <p class="text-sm text-slate-600 mt-1">{{ $comm->body }}</p>
                            <p class="text-xs text-slate-400">{{ $comm->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('communications.destroy', $comm) }}" onsubmit="return confirm('Delete this communication?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm bg-transparent border-0 cursor-pointer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm">No communication history yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    <!-- ====== END COMMUNICATIONS SECTION ====== -->
</div>
@endsection