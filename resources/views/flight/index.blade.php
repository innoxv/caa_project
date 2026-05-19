<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-caa-dark-navy">Personnel Licensing</h2>
    </x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Controls Row -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-50">
            <!-- Left: Search form & Register Button -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full md:w-auto">
                <form action="{{ route('admin.flight') }}" method="GET" class="w-full sm:w-80 flex">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, nationality, or category..." class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-1 focus:ring-caa-medium-blue focus:border-caa-medium-blue text-sm">
                    <button type="submit" class="px-4 py-2 bg-caa-medium-blue hover:bg-caa-dark-blue text-white text-sm font-semibold rounded-r-lg transition">
                        Search
                    </button>
                </form>

                <a href="{{ route('apply.flight') }}" class="px-4 py-2 bg-caa-medium-blue hover:bg-caa-dark-blue text-white text-sm font-semibold rounded-lg shadow-sm transition text-center whitespace-nowrap">
                    + New Application
                </a>
            </div>

            <!-- Right: Status Filters -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.flight', ['search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ !request('status') ? 'bg-caa-dark-navy text-white' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                    All
                </a>
                <a href="{{ route('admin.flight', ['status' => 'active', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                    Active
                </a>
                <a href="{{ route('admin.flight', ['status' => 'pending', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                    Pending
                </a>
                <a href="{{ route('admin.flight', ['status' => 'expired', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('status') === 'expired' ? 'bg-red-600 text-white' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                    Expired
                </a>
            </div>
        </div>

        <!-- Table Listing -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-500 text-sm border-b border-gray-100">
                        <th class="py-3 px-6 font-semibold">Licensee Name</th>
                        <th class="py-3 px-6 font-semibold">Category</th>
                        <th class="py-3 px-6 font-semibold">Type</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold">Hours</th>
                        <th class="py-3 px-6 font-semibold">Medical Cert</th>
                        <th class="py-3 px-6 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($flights as $f)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <div class="font-semibold text-gray-800">{{ $f->first_name }} {{ $f->last_name }}</div>
                                <div class="text-xs text-gray-400">Nationality: {{ $f->nationality }} | DOB: {{ $f->dob ? $f->dob->format('d M Y') : 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-700">
                                {{ $f->license_category }}
                            </td>
                            <td class="py-4 px-6 text-gray-600">
                                {{ $f->application_type }}
                            </td>
                            <td class="py-4 px-6">
                                @if ($f->status === 'active')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                                @elseif ($f->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Expired</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-500 font-semibold">
                                {{ $f->total_hours !== null ? number_format($f->total_hours) : 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-semibold">
                                    {{ $f->medical_cert }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.flight.edit', $f->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-caa-medium-blue hover:text-white rounded-lg text-xs font-semibold transition text-gray-600">
                                    Edit
                                </a>
                                <form action="{{ route('admin.flight.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this licensing record?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg text-xs font-semibold transition text-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center text-gray-400 font-medium bg-gray-50">
                                No personnel licenses found matching your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($flights->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $flights->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
