<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.medical') : route('services.medical') }}" class="text-gray-400 hover:text-caa-medium-blue mr-3 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            {{ isset($medical) ? 'Edit Medical Certificate' : 'Apply for Medical Certificate' }}
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
                <h3 class="text-xl font-bold text-caa-dark-navy">{{ isset($medical) ? 'Edit Certificate Details' : 'Medical Certification Form' }}</h3>
                <p class="text-sm text-gray-500 mt-1">Please provide correct medical screening and personal registration data.</p>
            </div>
            
            <form action="{{ isset($medical) ? route('admin.medical.update', $medical->id) : route('apply.medical.store') }}" method="POST" class="p-8">
                @csrf
                @if (isset($medical))
                    @method('PUT')
                @endif
                
                <!-- Section 1: Certificate & Applicant details -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">1. Certification & Applicant Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Legal Name <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $medical->full_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="John Doe">
                            @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilot License Number (if held)</label>
                            <input type="text" name="license_number" value="{{ old('license_number', $medical->license_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. PL-82931">
                            @error('license_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Certificate Class <span class="text-red-500">*</span></label>
                            <select name="medical_class" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                <option value="Class 1" {{ old('medical_class', $medical->medical_class ?? '') === 'Class 1' ? 'selected' : '' }}>Class 1 (Commercial/Airline Transport Pilots)</option>
                                <option value="Class 2" {{ old('medical_class', $medical->medical_class ?? '') === 'Class 2' ? 'selected' : '' }}>Class 2 (Private Pilots/Cabin Crew)</option>
                                <option value="Class 3" {{ old('medical_class', $medical->medical_class ?? '') === 'Class 3' ? 'selected' : '' }}>Class 3 (Air Traffic Controllers)</option>
                            </select>
                            @error('medical_class') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="dob" value="{{ old('dob', (isset($medical) && $medical->dob) ? $medical->dob->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                            @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Administrative Fields (Admin Only) -->
                @if (auth()->check() && auth()->user()->role === 'admin' && isset($medical))
                    <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-xl">
                        <h4 class="text-lg font-semibold text-caa-dark-navy border-b border-gray-200 pb-2 mb-4">2. Certification Status (Admin Only)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                    <option value="pending" {{ old('status', $medical->status) === 'pending' ? 'selected' : '' }}>Pending Evaluation</option>
                                    <option value="approved" {{ old('status', $medical->status) === 'approved' ? 'selected' : '' }}>Approved / Issued</option>
                                    <option value="rejected" {{ old('status', $medical->status) === 'rejected' ? 'selected' : '' }}>Rejected / Denied</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Issue</label>
                                <input type="date" name="issue_date" value="{{ old('issue_date', (isset($medical) && $medical->issue_date) ? $medical->issue_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                                @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.medical') : route('services.medical') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        {{ isset($medical) ? 'Update Certificate' : 'Submit Application' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
