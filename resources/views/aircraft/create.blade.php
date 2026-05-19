<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.aircraft') : route('services.aircraft') }}" class="text-gray-400 hover:text-caa-medium-blue mr-3 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            {{ isset($aircraft) ? 'Edit Aircraft Registration' : 'Register New Aircraft' }}
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
                <h3 class="text-xl font-bold text-caa-dark-navy">{{ isset($aircraft) ? 'Edit Aircraft Details' : 'Aircraft Registration Form' }}</h3>
                <p class="text-sm text-gray-500 mt-1">Please fill in all the required information to register a new aircraft.</p>
            </div>
            
            <form action="{{ isset($aircraft) ? route('admin.aircraft.update', $aircraft->id) : route('apply.aircraft.store') }}" method="POST" class="p-8">
                @csrf
                @if (isset($aircraft))
                    @method('PUT')
                @endif
                
                <!-- Section 1: Aircraft Details -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">1. Aircraft Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Manufacturer <span class="text-red-500">*</span></label>
                            <input type="text" name="manufacturer" value="{{ old('manufacturer', $aircraft->manufacturer ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. Boeing, Airbus">
                            @error('manufacturer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Model <span class="text-red-500">*</span></label>
                            <input type="text" name="model" value="{{ old('model', $aircraft->model ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. 737-800">
                            @error('model') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Serial Number <span class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" value="{{ old('serial_number', $aircraft->serial_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Serial No.">
                            @error('serial_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year of Manufacture <span class="text-red-500">*</span></label>
                            <input type="number" name="year" value="{{ old('year', $aircraft->year ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="YYYY">
                            @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Registration Mark Requested</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 py-2 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    5Y-
                                </span>
                                <input type="text" name="registration_mark" value="{{ old('registration_mark', $aircraft->registration_mark ?? '') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-none rounded-r-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue uppercase" placeholder="XXX" maxlength="10">
                            </div>
                            @error('registration_mark') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Leave blank if you want the registry to assign a random mark.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Ownership -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">2. Ownership Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Owner Name (Individual or Company) <span class="text-red-500">*</span></label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', $aircraft->owner_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Full Legal Name">
                            @error('owner_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Physical Address <span class="text-red-500">*</span></label>
                            <input type="text" name="owner_address" value="{{ old('owner_address', $aircraft->owner_address ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Street Address, City, Country">
                            @error('owner_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="owner_email" value="{{ old('owner_email', $aircraft->owner_email ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="email@example.com">
                            @error('owner_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="owner_phone" value="{{ old('owner_phone', $aircraft->owner_phone ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="+256 ...">
                            @error('owner_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Administrative Fields (Admin Only) -->
                @if (auth()->check() && auth()->user()->role === 'admin' && isset($aircraft))
                    <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-xl">
                        <h4 class="text-lg font-semibold text-caa-dark-navy border-b border-gray-200 pb-2 mb-4">3. Registration Status (Admin Only)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                    <option value="pending" {{ old('status', $aircraft->status) === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="active" {{ old('status', $aircraft->status) === 'active' ? 'selected' : '' }}>Active / Approved</option>
                                    <option value="suspended" {{ old('status', $aircraft->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Issue Date</label>
                                <input type="date" name="issue_date" value="{{ old('issue_date', (isset($aircraft) && $aircraft->issue_date) ? $aircraft->issue_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                                @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.aircraft') : route('services.aircraft') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        {{ isset($aircraft) ? 'Update Registration' : 'Submit Registration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
