<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserMigrationService;
use Illuminate\Support\Facades\Log;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Handle login
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'required|in:manufacturer,supplier,vendor,retailer,analyst'
    ]);

    $email = $request->email;
    $password = $request->password;
    $role = $request->role;

    // Check for user in the main users table first
    $unmigratedUser = User::where('email', $email)->first();

    if ($unmigratedUser && password_verify($password, $unmigratedUser->password)) {
        // Ensure the selected role matches the registered role
        if ($unmigratedUser->role !== $role) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        // Redirect to status page if pending or rejected
        if ($unmigratedUser->status === 'pending' || $unmigratedUser->status === 'rejected') {
            return redirect()->route('application.status', ['email' => $unmigratedUser->email]);
        }

        // If approved, migrate user on first login
        if ($unmigratedUser->status === 'approved') {
            $userMigrationService = new UserMigrationService();
            try {
                $userMigrationService->migrateUserToRoleTable($unmigratedUser);
            } catch (\Exception $e) {
                Log::error("Migration failed for {$email} on login: " . $e->getMessage());
                return back()->withErrors(['email' => 'An error occurred during account setup. Please contact support.']);
            }
        }
    }

    // Use the UserMigrationService to authenticate
    $userMigrationService = new UserMigrationService();
    $user = $userMigrationService->authenticateUser($email, $password, $role);

    if ($user) {
        // Store user info in session
        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $role
        ]);

        // Redirect to role-specific dashboard
        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'manufacturer':
                return redirect('/manufacturer/dashboard');
            case 'supplier':
                return redirect('/supplier/dashboard');
            case 'vendor':
                return redirect('/vendor/dashboard');
            case 'retailer':
                return redirect('/retailer/dashboard');
            case 'analyst':
                return redirect('/analyst/dashboard');
            default:
                return redirect('/dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

// Register page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Handle registration
Route::post('/register', function (Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:manufacturer,supplier,vendor,retailer,analyst',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'company_name' => 'required|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Handle file uploads
    $profilePicturePath = null;
    $supportingDocuments = [];

    if ($request->hasFile('profile_picture')) {
        $profilePicture = $request->file('profile_picture');
        $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
        $profilePicture->storeAs('public/profile_pictures', $profilePictureName);
        $profilePicturePath = 'profile_pictures/' . $profilePictureName;
    }

    if ($request->hasFile('supporting_documents')) {
        foreach ($request->file('supporting_documents') as $document) {
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->storeAs('public/supporting_documents', $documentName);
            $supportingDocuments[] = 'supporting_documents/' . $documentName;
        }
    }

    // Create user with pending status
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'company' => $request->company_name,
        'address' => $request->address,
        'profile_picture' => $profilePicturePath,
        'supporting_documents' => $supportingDocuments,
        'status' => 'pending',
    ]);

    // Redirect to status page
    return redirect()->route('application.status', ['email' => $user->email])
        ->with('status', 'Registration successful! Your application is now pending approval.');
});

// Application status page
Route::get('/status/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }
    
    return view('auth.application-status', ['user' => $user]);
})->name('application.status');

// Application status page (alternative URL pattern)
Route::get('/application-status', function (Request $request) {
    $email = $request->query('email');
    
    if (!$email) {
        return redirect()->route('login')->with('error', 'Email address is required.');
    }
    
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }
    
    return view('auth.application-status', ['user' => $user]);
});

//Reset password page
Route::get('/password.reset', function () {
    return view('auth.reset-password');
})->name('password.request');

// Password reset form
Route::get('/password.reset/{token}', function ($token) {
    return view('auth.reset-password-form', ['token' => $token]);
})->name('password.reset');

// Password token page
Route::get('/password/enter-token', function () {
    return view('auth.reset-password-token');
})->name('password.token');

Route::post('/password.email', function () {
    // Logic to send password reset link will go here
    // For now, we'll just redirect back with a message
    return back()->with('status', 'Password reset link sent!');
})->name('password.email');

Route::post('/password.reset', function () {
    // Logic to reset the password will go here
    // For now, we'll just redirect to login with a message
    return redirect()->route('login')->with('status', 'Password has been reset!');
})->name('password.update');

// Dashboard (for testing)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

// Role-specific dashboard routes
Route::get('/admin/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'admin') {
        return redirect('/login');
    }
    return view('dashboards.admin.index');
});

Route::get('/manufacturer/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'manufacturer') {
        return redirect('/login');
    }
    return view('dashboards.manufacturer.index');
});

Route::get('/supplier/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'supplier') {
        return redirect('/login');
    }
    return view('dashboards.supplier.index');
});

Route::get('/vendor/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'vendor') {
        return redirect('/login');
    }
    return view('dashboards.vendor.index');
});

Route::get('/retailer/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'retailer') {
        return redirect('/login');
    }
    return view('dashboards.retailer.index');
});

Route::get('/analyst/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'analyst') {
        return redirect('/login');
    }
    return view('dashboards.analyst.index');
});

// Logout route
Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});

// Admin login page
Route::get('/admin/login', function () {
    return view('auth.admin-login');
});

// Admin login route (separate from regular user login)
Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $email = $request->email;
    $password = $request->password;

    // Use the UserMigrationService to authenticate admin
    $userMigrationService = new \App\Services\UserMigrationService();
    $user = $userMigrationService->authenticateUser($email, $password, 'admin');

    if ($user) {
        // Store user info in session
        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => 'admin'
        ]);

        return redirect('/admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});