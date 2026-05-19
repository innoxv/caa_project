<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.flight') : route('services.flight') }}" class="text-gray-400 hover:text-caa-medium-blue mr-3 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            {{ isset($flight) ? 'Edit Personnel License' : 'Apply for Personnel License' }}
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
                <h3 class="text-xl font-bold text-caa-dark-navy">{{ isset($flight) ? 'Edit Application Details' : 'Personnel Licensing Form' }}</h3>
                <p class="text-sm text-gray-500 mt-1">Please provide accurate personal and flight credentials for licensing.</p>
            </div>
            
            <form action="{{ isset($flight) ? route('admin.flight.update', $flight->id) : route('apply.flight.store') }}" method="POST" class="p-8">
                @csrf
                @if (isset($flight))
                    @method('PUT')
                @endif
                
                <!-- Section 1: Personal Details -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">1. Personal Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', $flight->first_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Emma">
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', $flight->last_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Nsubuga">
                            @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="dob" value="{{ old('dob', (isset($flight) && $flight->dob) ? $flight->dob->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                            @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nationality <span class="text-red-500">*</span></label>
                            <input type="text" name="nationality" value="{{ old('nationality', $flight->nationality ?? 'Kenyan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. Kenyan">
                            @error('nationality') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Physical Address</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Street Address, City, Country">{{ old('address', $flight->address ?? '') }}</textarea>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: License Details -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">2. License Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Type <span class="text-red-500">*</span></label>
                            <select name="application_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                <option value="Initial Issue" {{ old('application_type', $flight->application_type ?? '') === 'Initial Issue' ? 'selected' : '' }}>Initial Issue</option>
                                <option value="Renewal" {{ old('application_type', $flight->application_type ?? '') === 'Renewal' ? 'selected' : '' }}>Renewal</option>
                                <option value="Validation of Foreign License" {{ old('application_type', $flight->application_type ?? '') === 'Validation of Foreign License' ? 'selected' : '' }}>Validation of Foreign License</option>
                                <option value="Addition of Rating" {{ old('application_type', $flight->application_type ?? '') === 'Addition of Rating' ? 'selected' : '' }}>Addition of Rating</option>
                            </select>
                            @error('application_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Category <span class="text-red-500">*</span></label>
                            <select name="license_category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                <option value="ATPL (Airline Transport Pilot License)" {{ old('license_category', $flight->license_category ?? '') === 'ATPL (Airline Transport Pilot License)' ? 'selected' : '' }}>ATPL (Airline Transport Pilot License)</option>
                                <option value="CPL (Commercial Pilot License)" {{ old('license_category', $flight->license_category ?? '') === 'CPL (Commercial Pilot License)' ? 'selected' : '' }}>CPL (Commercial Pilot License)</option>
                                <option value="PPL (Private Pilot License)" {{ old('license_category', $flight->license_category ?? '') === 'PPL (Private Pilot License)' ? 'selected' : '' }}>PPL (Private Pilot License)</option>
                                <option value="ATC (Air Traffic Controller)" {{ old('license_category', $flight->license_category ?? '') === 'ATC (Air Traffic Controller)' ? 'selected' : '' }}>ATC (Air Traffic Controller)</option>
                                <option value="AME (Aircraft Maintenance Engineer)" {{ old('license_category', $flight->license_category ?? '') === 'AME (Aircraft Maintenance Engineer)' ? 'selected' : '' }}>AME (Aircraft Maintenance Engineer)</option>
                            </select>
                            @error('license_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Flight Hours (if applicable)</label>
                            <input type="number" name="total_hours" value="{{ old('total_hours', $flight->total_hours ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. 1500">
                            @error('total_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid Medical Certificate <span class="text-red-500">*</span></label>
                            <select name="medical_cert" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                <option value="Class 1" {{ old('medical_cert', $flight->medical_cert ?? '') === 'Class 1' ? 'selected' : '' }}>Class 1 Medical Certificate</option>
                                <option value="Class 2" {{ old('medical_cert', $flight->medical_cert ?? '') === 'Class 2' ? 'selected' : '' }}>Class 2 Medical Certificate</option>
                                <option value="Class 3" {{ old('medical_cert', $flight->medical_cert ?? '') === 'Class 3' ? 'selected' : '' }}>Class 3 Medical Certificate</option>
                                <option value="Not Applicable" {{ old('medical_cert', $flight->medical_cert ?? '') === 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                            @error('medical_cert') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Administrative Fields (Admin Only) -->
                @if (auth()->check() && auth()->user()->role === 'admin' && isset($flight))
                    <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-xl">
                        <h4 class="text-lg font-semibold text-caa-dark-navy border-b border-gray-200 pb-2 mb-4">3. License Status (Admin Only)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                    <option value="pending" {{ old('status', $flight->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                    <option value="active" {{ old('status', $flight->status) === 'active' ? 'selected' : '' }}>Active / Approved</option>
                                    <option value="expired" {{ old('status', $flight->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Issue</label>
                                <input type="date" name="issue_date" value="{{ old('issue_date', (isset($flight) && $flight->issue_date) ? $flight->issue_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                                @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.flight') : route('services.flight') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        {{ isset($flight) ? 'Update License' : 'Submit Application' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
