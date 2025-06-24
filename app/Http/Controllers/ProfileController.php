<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Admin;
use App\Http\Requests\AdminProfileUpdateRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        if (session('user_role') === 'admin') {
            $user = Admin::find(session('user_id'));
            return view('profile.admin-edit', ['user' => $user]);
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        if (session('user_role') === 'admin') {
            $adminRequest = new AdminProfileUpdateRequest();
            $validated = $request->validate($adminRequest->rules());

            $user = Admin::find(session('user_id'));
            $user->fill($validated);

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $user->profile_photo = $path;
            }

            $user->save();
            session(['user_name' => $user->name, 'user_profile_photo' => $user->profile_photo_path]);
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        // The following part is for non-admin users, so we use ProfileUpdateRequest
        Log::info('Profile update request', [
            'hasFile' => $request->hasFile('profile_photo'),
            'file' => $request->file('profile_photo'),
            'file_mime' => $request->hasFile('profile_photo') ? $request->file('profile_photo')->getMimeType() : null,
            'file_size' => $request->hasFile('profile_photo') ? $request->file('profile_photo')->getSize() : null,
        ]);
        $validated = $request->validate((new \App\Http\Requests\ProfileUpdateRequest())->rules());
        $user = $request->user();
        $user->fill($validated);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
            // Update or create user_documents entry for profile_picture
            \App\Models\UserDocument::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'document_type' => 'profile_picture',
                ],
                [
                    'file_path' => $path,
                ]
            );
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        session(['user_name' => $user->name, 'user_profile_photo' => $user->profile_photo_path]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
