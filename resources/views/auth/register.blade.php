@section('title', 'Register')
<x-guest-layout>
    <div class="register-container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div class="register-title">Register for Autochain Nexus</div>
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

            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>
             <div class="form-group">
                <label for="phone">Phone Number</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="">Select Role</option>
                    <option value="manufacturer">Manufacturer</option>
                    <option value="supplier">Supplier</option>
                    <option value="vendor">Vendor</option>
                    <option value="retailer">Retailer</option>
                    <option value="analyst">Analyst</option>
                </select>
            </div>
            <div class="form-group">
                <label for="company">Company Name</label>
                <input id="company" type="text" name="company" value="{{ old('company') }}" required>
            </div>
            <div class="form-group">
                <label for="address">Company Address</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}" required>
            </div>
            <!-- Supporting Documents Upload -->
            <div class="form-group-full">
                <label style="font-weight:700; color:var(--deep-purple); margin-bottom:0.5rem;">Supporting Documents</label>
                <div class="custom-upload-box">
                    <input type="file" id="supporting_documents" name="supporting_documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required style="display:none;">
                    <label for="supporting_documents" class="upload-label">
                        <div class="upload-icon">&#8679;</div>
                        <div class="upload-text">Upload documents</div>
                        <div class="upload-desc">
                            Please upload certifications, Business License, Identification Card, and financial records, or other relevant documents (PDF, DOC format, jpg, png).
                        </div>
                    </label>
                </div>
            </div>
            <div class="form-group profile-picture-group">
                <label for="profile_picture" class="profile-picture-label">Upload Profile Picture</label>
                <input id="profile_picture" type="file" name="profile_picture" accept=".jpg,.jpeg,.png" required class="profile-picture-input">
            </div>
            <div class="form-group-full">
                <button type="submit" class="btn-register">Register</button>
            </div>
        </form>
    </div>
    @push('scripts')
    <script>
        // Enhance file input click for custom upload box
        document.querySelectorAll('.custom-upload-box').forEach(box => {
            const input = box.querySelector('input[type="file"]');
            const label = box.querySelector('label.upload-label');
            if (label && input) {
                label.addEventListener('click', function(e) {
                    e.stopPropagation();
                    input.click();
                });
            }
        });

        // Prevent form submission if any required field is empty
        document.querySelector('.register-form').addEventListener('submit', function(e) {
            let valid = true;
            this.querySelectorAll('input[required], select[required]').forEach(field => {
                if (
                    (field.type === 'file' && field.files.length === 0) ||
                    (field.type !== 'file' && !field.value)
                ) {
                    valid = false;
                    field.classList.add('input-error');
                } else {
                    field.classList.remove('input-error');
                }
            });
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
    @endpush
</x-guest-layout>
