<x-guest-layout>
    <!-- Content within the white card container provided by guest layout -->

    <div class="flex flex-col items-center text-center mb-6">
        <h2 class="text-2xl font-bold text-[#171d3f] mt-0">Join AUTOCHAIN NEXUS</h2>
        <p class="text-black text-sm">Apply to become a verified partner in our supply chain network</p>
    </div>

    {{-- Message display area (Optional, using session status) --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="w-full" enctype="multipart/form-data">
        @csrf
        {{-- CSRF token input is handled by @csrf --}}

        <!-- Personal Information Section -->
        <h3 class="text-lg font-semibold text-[#171d3f] mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <x-input-label for="name" :value="__('Full Name *')" class="!text-[#171d3f] text-sm"/>
                <x-text-input id="name" class="block mt-1 w-full text-sm text-black !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" :value="__('Email Address *')" class="!text-[#171d3f] text-sm"/>
                <x-text-input id="email" class="block mt-1 w-full text-sm text-black !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="phone" :value="__('Phone Number *')" class="!text-[#171d3f] text-sm"/>
                <x-text-input id="phone" class="block mt-1 w-full text-sm text-black !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="text" name="phone" :value="old('phone')" required autocomplete="tel" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password" :value="__('Password *')" class="!text-[#171d3f] text-sm"/>
                <x-text-input id="password" class="block mt-1 w-full text-sm text-black !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password *')" class="!text-[#171d3f] text-sm"/>
                <x-text-input id="password_confirmation" class="block mt-1 w-full text-sm text-black !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="role" :value="__('Desired Role *')" class="!text-[#171d3f] text-sm"/>
                <select id="role" name="role" class="block mt-1 w-full text-sm text-black border-black rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    <option value="">Select your role</option>
                    <option value="supplier">Supplier</option>
                    <option value="manufacturer">Manufacturer</option>
                    <option value="vendor">Vendor</option>
                    <option value="retailer">Retailer</option>
                    <option value="admin">Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
        </div>

        <!-- Company Information Section -->
        <h3 class="text-lg font-semibold text-[#171d3f] mb-4">Company Information</h3>
        <div class="mb-6">
             <div class="mb-4">
                <x-input-label for="company_name" :value="__('Company Name')" class="!text-[#171d3f] text-sm"/>
                <x-text-input id="company_name" class="block mt-1 w-full text-sm text-black !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="text" name="company_name" :value="old('company_name')" autocomplete="organization" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="company_address" :value="__('Business Address')" class="!text-[#171d3f] text-sm"/>
                <textarea id="company_address" name="company_address" class="block mt-1 w-full text-sm text-black placeholder-gray-400 border-black rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 input-text-color" autocomplete="street-address">{{ old('company_address') }}</textarea>
                <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
            </div>
        </div>

        <!-- Supporting Documents Section -->
        <h3 class="text-lg font-semibold text-[#171d3f] mb-4">Supporting Documents</h3>
        <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            <label for="documents" class="mt-2 block text-sm font-medium text-blue-600 cursor-pointer hover:text-blue-800">Click to upload documents</label>
            <input id="documents" name="documents[]" type="file" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png">
            <p class="mt-1 text-xs text-gray-500">Please upload certifications, financial records, or other relevant documents (PDF, JPG, PNG format, max 50MB)</p>
            <div id="selected-files" class="mt-4 text-left text-sm"></div>
            <x-input-error :messages="$errors->get('documents')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-[#171d3f] hover:bg-[#2c8ac9] text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline text-sm">
                {{ __('Submit Application') }}
            </button>
        </div>
    </form>

    <script>
        // File upload handling (kept for displaying selected files)
        const fileInput = document.getElementById('documents');
        const selectedFilesDiv = document.getElementById('selected-files');

        fileInput.addEventListener('change', function() {
            selectedFilesDiv.innerHTML = ''; // Clear previous files
            
            if (this.files.length > 0) {
                const fileList = document.createElement('ul');
                fileList.className = 'space-y-2';
                
                Array.from(this.files).forEach(file => {
                    const listItem = document.createElement('li');
                    listItem.className = 'flex items-center text-gray-700';
                    
                    // File icon based on type
                    let icon = '📄';
                    if (file.type.includes('pdf')) icon = '📑';
                    if (file.type.includes('image')) icon = '🖼️';
                    
                    listItem.innerHTML = `
                        <span class="mr-2">${icon}</span>
                        <span class="flex-1">${file.name}</span>
                        <span class="text-xs text-gray-500">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                    `;
                    
                    fileList.appendChild(listItem);
                });
                
                selectedFilesDiv.appendChild(fileList);
            } else {
                selectedFilesDiv.innerHTML = '<p class="text-gray-500">No files selected</p>';
            }
        });

        // Removed AJAX form submission script
    </script>
</x-guest-layout>
