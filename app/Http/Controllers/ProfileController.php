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
            session(['user_name' => $user->name]);
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        // The following part is for non-admin users, so we use ProfileUpdateRequest
        $userRequest = ProfileUpdateRequest::createFrom($request);
        $user = $request->user();
        $user->fill($userRequest->validated());

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

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
