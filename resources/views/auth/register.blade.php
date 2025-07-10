@section('title', 'Register')
<x-guest-layout>
    <nav class="register-navbar">
        <div class="navbar-logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            </a>
            <span class="navbar-brand">Autocahin Nexus</span>
        </div>
        <div class="navbar-links">
            <a href="/">Home</a>
            <a href="/application-status">Application Status</a>
            <a href="/login">Login</a>
        </div>
    </nav>
    <div class="register-split-container">
        <!-- Left: illustration -->
        <div class="register-split-left">
            <div class="register-illustration-wrapper">
                <div class="register-illustration">
                    <div class="register-illustration-text">Join the Autochain Nexus network</div>
                    <img src="{{ asset('images/register.png') }}" alt="Register Illustration" style="max-width: 520px; width: 100%; height: auto; display: block; margin: 2rem auto 0 auto;">
                </div>
            </div>
        </div>
        <!-- Right: Form -->
        <div class="register-split-right">
            <div class="register-form-box">
                <div class="register-title">Register for free Account </div>
                <div class="register-desc">Join our automotive supply chain network</div>
                <form class="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom:1rem;">
                            <ul style="margin:0; padding-left:1.2rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="register-form-columns">
                        <div class="register-form-left">
                            <div class="form-group">
                                <label for="name">Full Name <span class="required-asterisk">*</span></label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address <span class="required-asterisk">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required-asterisk">*</span></label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="required-asterisk">*</span></label>
                                <input id="password" type="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password <span class="required-asterisk">*</span></label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role <span class="required-asterisk">*</span></label>
                                <select name="role" id="role" required>
                                    <option value="">Select Role</option>
                                    <option value="manufacturer">Manufacturer</option>
                                    <option value="supplier">Supplier</option>
                                    <option value="vendor">Vendor</option>
                                    <option value="retailer">Retailer</option>
                                    <option value="analyst">Analyst</option>
                                </select>
                            </div>
                            <div class="form-group" id="manufacturer-select-group" style="display:none;">
                                <label for="manufacturer_id">Select Manufacturer to Visit</label>
                                <select name="manufacturer_id" id="manufacturer_id">
                                    <option value="">Select Manufacturer</option>
                                    @foreach($approvedManufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->company ?? $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="register-form-divider"></div>
                        <div class="register-form-right">
                            <div class="form-group">
                                <label for="company_name">Company Name <span class="required-asterisk">*</span></label>
                                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Company Address <span class="required-asterisk">*</span></label>
                                <input id="address" type="text" name="address" value="{{ old('address') }}" required>
                            </div>
                            <div class="form-group-full">
                                <label style="font-weight:700; color:var(--text); margin-bottom:0.5rem;">Supporting Documents <span class="required-asterisk">*</span></label>
                                <div class="custom-upload-box">
                                    <input type="file" id="supporting_documents" name="supporting_documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display:none;">
                                    <div class="upload-label">
                                        <div class="upload-text">Upload documents</div>
                                        <div class="upload-desc">
                                            Please upload certifications, Business License, Identification Card, and financial records, or other relevant documents (PDF, DOC format, jpg, png).
                                        </div>
                                    </div>
                                    <ul class="selected-files-list" style="margin-top: 0.5rem; color: #333; font-size: 0.95em; list-style: none; padding: 0;"></ul>
                                </div>
                            </div>
                            <div class="form-group profile-picture-group">
                                <label for="profile_picture" class="profile-picture-label">Upload Profile Picture <span class="required-asterisk">*</span></label>
                                <input id="profile_picture" type="file" name="profile_picture" accept=".jpg,.jpeg,.png" required class="profile-picture-input">
                            </div>
                        </div>
                    </div>
                    <div class="form-group-full">
                        <button type="submit" class="btn-register">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modern multi-file upload with preview and remove
            const uploadBox = document.querySelector('.custom-upload-box');
            const fileInput = uploadBox.querySelector('input[type="file"]');
            const fileListUl = uploadBox.querySelector('.selected-files-list');
            let selectedFiles = [];
            // Fix: clicking anywhere in the box triggers file input
            uploadBox.addEventListener('click', function(e) {
                if (e.target !== fileInput) {
                fileInput.click();
                }
            });
            fileInput.addEventListener('change', function(e) {
                let added = false;
                for (let i = 0; i < fileInput.files.length; i++) {
                    const file = fileInput.files[i];
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified)) {
                        selectedFiles.push(file);
                        added = true;
                    }
                }
                renderFileList();
                if (added) {
                    fileInput.value = '';
                }
            });
            function renderFileList() {
                fileListUl.innerHTML = '';
                selectedFiles.forEach((file, idx) => {
                    const li = document.createElement('li');
                    li.style.display = 'flex';
                    li.style.alignItems = 'center';
                    li.style.marginBottom = '0.25rem';
                    li.innerHTML = `<span>${file.name}</span> <button type="button" class="remove-file-btn" data-idx="${idx}" style="margin-left: 0.5rem; color: #fff; background: #e3342f; border: none; border-radius: 3px; padding: 0 8px; cursor: pointer; font-size: 0.9em;">Remove</button>`;
                    fileListUl.appendChild(li);
                });
            }
            fileListUl.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file-btn')) {
                    const idx = parseInt(e.target.getAttribute('data-idx'));
                    selectedFiles.splice(idx, 1);
                    renderFileList();
                }
            });
            document.querySelector('.register-form').addEventListener('submit', function(e) {
                if (selectedFiles.length === 0) {
                    e.preventDefault();
                    alert('Please upload at least one supporting document.');
                    return;
                }
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
            });
            const roleSelect = document.getElementById('role');
            const manufacturerGroup = document.getElementById('manufacturer-select-group');
            roleSelect.addEventListener('change', function() {
                if (this.value === 'vendor') {
                    manufacturerGroup.style.display = '';
                } else {
                    manufacturerGroup.style.display = 'none';
                }
            });
            if (roleSelect.value === 'vendor') {
                manufacturerGroup.style.display = '';
            }
        });
    </script>
</x-guest-layout>
<footer class="register-footer">
    ©2024 Autocahin Nexus. All rights reserved.
</footer>
