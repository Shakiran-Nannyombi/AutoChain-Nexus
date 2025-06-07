<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'max:20'],
                'company_name' => ['required', 'string', 'max:255'],
                'company_address' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'in:supplier,manufacturer,vendor,retailer,admin'],
                'documents.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'], // 50MB max
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'company_name' => $validated['company_name'],
                'company_address' => $validated['company_address'],
                'role' => $validated['role'],
                'status' => 'pending',
            ]);

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = $document->store('application_docs/' . $user->id, 'public');
                    
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

            event(new Registered($user));

            // Redirect to the application status page after successful registration
            return redirect()->route('application-status', ['id' => $user->id])->with('status', 'Registration successful! Your application is pending admin approval.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the exception details
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 