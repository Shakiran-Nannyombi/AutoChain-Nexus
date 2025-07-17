<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\VendorActivity;
use App\Models\User;

class VendorProfileController extends Controller
{
    // Show profile edit form
    public function edit()
    {
        $user = Auth::user();
        return view('dashboards.vendor.profile', compact('user'));
    }

    // Update profile info
    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);
        $user->update($validated);
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Updated profile',
            'details' => 'Profile info updated',
        ]);
        return back()->with('success', 'Profile updated.');
    }

    // Change password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Changed password',
            'details' => null,
        ]);
        return back()->with('success', 'Password changed.');
    }

    // Upload profile picture and documents
    public function uploadDocuments(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
        ]);
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
            $profilePicture->storeAs('public/profile_pictures', $profilePictureName);
            // Save to user_documents table
            \App\Models\UserDocument::create([
                'user_id' => $user->id,
                'document_type' => 'profile_picture',
                'file_path' => 'profile_pictures/' . $profilePictureName
            ]);
        }
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $doc) {
                $docName = time() . '_' . $doc->getClientOriginalName();
                $doc->storeAs('public/supporting_documents', $docName);
                \App\Models\UserDocument::create([
                    'user_id' => $user->id,
                    'document_type' => 'supporting_document',
                    'file_path' => 'supporting_documents/' . $docName
                ]);
            }
        }
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Uploaded documents',
            'details' => 'Profile picture or supporting documents uploaded',
        ]);
        return back()->with('success', 'Documents uploaded.');
    }
} 