<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.organization') : route('services.organization') }}" class="text-gray-400 hover:text-caa-medium-blue mr-3 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            {{ isset($organization) ? 'Edit Aviation Organization' : 'Register Aviation Organization' }}
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Validation Summary -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-red-800">Please correct the errors in the form below.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                <h3 class="text-xl font-bold text-caa-dark-navy">{{ isset($organization) ? 'Edit Organization Details' : 'Organization Registration Form' }}</h3>
                <p class="text-sm text-gray-500 mt-1">Please provide accurate corporate registration details.</p>
            </div>
            
            <form action="{{ isset($organization) ? route('admin.organization.update', $organization->id) : route('apply.organization.store') }}" method="POST" class="p-8">
                @csrf
                @if (isset($organization))
                    @method('PUT')
                @endif
                
                <!-- Section 1: Organization Details -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">1. Organization Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $organization->name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. Kajjansi Flying School">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organization Type <span class="text-red-500">*</span></label>
                            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                <option value="Training Institute" {{ old('type', $organization->type ?? '') === 'Training Institute' ? 'selected' : '' }}>Training Institute (ATO)</option>
                                <option value="Service Provider" {{ old('type', $organization->type ?? '') === 'Service Provider' ? 'selected' : '' }}>Service Provider / Airline</option>
                                <option value="Other" {{ old('type', $organization->type ?? '') === 'Other' ? 'selected' : '' }}>Other Aviation Body</option>
                            </select>
                            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Administrative Fields (Admin Only) -->
                @if (auth()->check() && auth()->user()->role === 'admin' && isset($organization))
                    <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-xl">
                        <h4 class="text-lg font-semibold text-caa-dark-navy border-b border-gray-200 pb-2 mb-4">2. Registration Status (Admin Only)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                    <option value="pending" {{ old('status', $organization->status) === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="active" {{ old('status', $organization->status) === 'active' ? 'selected' : '' }}>Active / Registered</option>
                                    <option value="inactive" {{ old('status', $organization->status) === 'inactive' ? 'selected' : '' }}>Inactive / Expired</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Registration</label>
                                <input type="date" name="issue_date" value="{{ old('issue_date', (isset($organization) && $organization->issue_date) ? $organization->issue_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                                @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.organization') : route('services.organization') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        {{ isset($organization) ? 'Update Organization' : 'Submit Registration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
