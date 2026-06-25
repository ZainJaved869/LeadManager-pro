@extends('layouts.app')

@section('title', 'Companies')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Companies</h1>
            <p class="text-slate-500 text-sm">Manage your customer companies.</p>
        </div>
        <a href="{{ route('companies.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> Add Company
        </a>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <form method="GET" action="{{ route('companies.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" placeholder="Search by name, email, or industry..." value="{{ request('search') }}"
                   class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500 w-64">
            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contacts</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($companies as $company)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $company->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $company->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $company->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $company->contacts_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('companies.show', $company) }}" class="text-indigo-600 hover:text-indigo-800" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('companies.edit', $company) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Delete this company?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent border-0 cursor-pointer"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-building text-3xl block mb-3 text-slate-300"></i>
                                No companies found. <a href="{{ route('companies.create') }}" class="text-yellow-600 hover:underline font-medium">Add your first company</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $companies->links() }}</div>
</div>
@endsection