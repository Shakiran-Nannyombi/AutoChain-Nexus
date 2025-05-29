<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:supplier,manufacturer,retailer'],
            'documents.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'role' => $request->role,
            'status' => 'pending',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('documents/' . $user->id);
                
                UserDocument::create([
                    'user_id' => $user->id,
                    'document_type' => $document->getClientOriginalExtension(),
                    'file_path' => $path,
                    'original_filename' => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType(),
                    'file_size' => $document->getSize(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Registration successful. Please wait for admin approval.',
            'user' => $user
        ], 201);
    }
} 