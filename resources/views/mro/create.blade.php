<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.mro') : route('services.mro') }}" class="text-gray-400 hover:text-caa-medium-blue mr-3 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            {{ isset($mro) ? 'Edit MRO Facility Approval' : 'Apply for MRO Facility Approval' }}
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
                <h3 class="text-xl font-bold text-caa-dark-navy">{{ isset($mro) ? 'Edit MRO Details' : 'MRO Facility Approval Form' }}</h3>
                <p class="text-sm text-gray-500 mt-1">Please fill in details for Maintenance Organization certification.</p>
            </div>
            
            <form action="{{ isset($mro) ? route('admin.mro.update', $mro->id) : route('apply.mro.store') }}" method="POST" class="p-8">
                @csrf
                @if (isset($mro))
                    @method('PUT')
                @endif
                
                <!-- Section 1: Facility Details -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">1. Facility & Corporate Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $mro->name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="e.g. AeroFix Engineering">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                            <input type="text" name="country" value="{{ old('country', $mro->country ?? 'Kenya') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Kenya">
                            @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Physical Hangar/Facility Address <span class="text-red-500">*</span></label>
                            <input type="text" name="address" value="{{ old('address', $mro->address ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Hangar 1, JKIA, Nairobi">
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Type <span class="text-red-500">*</span></label>
                            <select name="application_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                <option value="Initial Grant" {{ old('application_type', $mro->application_type ?? '') === 'Initial Grant' ? 'selected' : '' }}>Initial Grant</option>
                                <option value="Renewal" {{ old('application_type', $mro->application_type ?? '') === 'Renewal' ? 'selected' : '' }}>Renewal</option>
                                <option value="Variation / Amendment" {{ old('application_type', $mro->application_type ?? '') === 'Variation / Amendment' ? 'selected' : '' }}>Variation / Amendment</option>
                            </select>
                            @error('application_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Ratings Requested -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">2. Ratings Requested</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $selectedRatings = old('ratings', $mro->ratings ?? []);
                        @endphp
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="ratings[]" value="A1" {{ in_array('A1', $selectedRatings) ? 'checked' : '' }} class="rounded text-caa-medium-blue focus:ring-caa-medium-blue">
                            <span class="text-sm font-medium text-gray-700">A1 - Aeroplanes above 5700 kg</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="ratings[]" value="A2" {{ in_array('A2', $selectedRatings) ? 'checked' : '' }} class="rounded text-caa-medium-blue focus:ring-caa-medium-blue">
                            <span class="text-sm font-medium text-gray-700">A2 - Aeroplanes 5700 kg & below</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="ratings[]" value="A3" {{ in_array('A3', $selectedRatings) ? 'checked' : '' }} class="rounded text-caa-medium-blue focus:ring-caa-medium-blue">
                            <span class="text-sm font-medium text-gray-700">A3 - Helicopters</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="ratings[]" value="B1" {{ in_array('B1', $selectedRatings) ? 'checked' : '' }} class="rounded text-caa-medium-blue focus:ring-caa-medium-blue">
                            <span class="text-sm font-medium text-gray-700">B1 - Turbine Engines</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="ratings[]" value="B2" {{ in_array('B2', $selectedRatings) ? 'checked' : '' }} class="rounded text-caa-medium-blue focus:ring-caa-medium-blue">
                            <span class="text-sm font-medium text-gray-700">B2 - Piston Engines</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="ratings[]" value="C" {{ in_array('C', $selectedRatings) ? 'checked' : '' }} class="rounded text-caa-medium-blue focus:ring-caa-medium-blue">
                            <span class="text-sm font-medium text-gray-700">C - Components</span>
                        </label>
                    </div>
                </div>

                <!-- Section 3: Responsible Personnel -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">3. Management Personnel</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Accountable Manager <span class="text-red-500">*</span></label>
                            <input type="text" name="accountable_manager" value="{{ old('accountable_manager', $mro->accountable_manager ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Francis Mugisha">
                            @error('accountable_manager') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quality Manager <span class="text-red-500">*</span></label>
                            <input type="text" name="quality_manager" value="{{ old('quality_manager', $mro->quality_manager ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue" placeholder="Hellen Atwine">
                            @error('quality_manager') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Administrative Fields (Admin Only) -->
                @if (auth()->check() && auth()->user()->role === 'admin' && isset($mro))
                    <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-xl">
                        <h4 class="text-lg font-semibold text-caa-dark-navy border-b border-gray-200 pb-2 mb-4">4. Certification Status (Admin Only)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue bg-white">
                                    <option value="pending" {{ old('status', $mro->status) === 'pending' ? 'selected' : '' }}>Pending Audit / Review</option>
                                    <option value="approved" {{ old('status', $mro->status) === 'approved' ? 'selected' : '' }}>Approved / Active</option>
                                    <option value="expired" {{ old('status', $mro->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Number</label>
                                <input type="text" name="certificate_no" value="{{ old('certificate_no', $mro->certificate_no ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue uppercase" placeholder="e.g. MRO/UG/012">
                                @error('certificate_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                <input type="date" name="expiry_date" value="{{ old('expiry_date', (isset($mro) && $mro->expiry_date) ? $mro->expiry_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-caa-medium-blue focus:border-caa-medium-blue">
                                @error('expiry_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ (auth()->check() && auth()->user()->role === 'admin') ? route('admin.mro') : route('services.mro') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        {{ isset($mro) ? 'Update Approval' : 'Submit Application' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
