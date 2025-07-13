<section>
    <header>
        <h3>
            <i class="fas fa-user-edit"></i>
            Profile Settings
        </h3>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="settings-form-grid">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" />
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" name="address" type="text" value="{{ old('address', $user->address) }}" />
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input id="role" type="text" value="{{ old('department', $user->department) }}" disabled />
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input id="department" name="department" type="text" value="{{ old('department', $user->department) }}" />
            </div>
            <div class="form-group">
                <label for="profile_photo">Profile Photo</label>
                <input type="file" name="profile_photo" id="profile_photo" accept="image/*">
                @if($user->profile_photo)
                    <div class="mt-2">
                        <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" width="100" class="rounded-full border">
                    </div>
                @endif
                @error('profile_photo')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4" style="margin-top: 2rem;">
            <button type="submit" class="btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> 