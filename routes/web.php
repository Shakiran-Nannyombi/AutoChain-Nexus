<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserMigrationService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ValidationCriteriaController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'supporting_documents' => 'required|array|min:1',
        'supporting_documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
    ]);

    // Create user with pending status
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'company' => $request->company_name,
        'address' => $request->address,
        'status' => 'pending',
    ]);

    // Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        $profilePicture = $request->file('profile_picture');
        $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
        $profilePicture->storeAs('public/profile_pictures', $profilePictureName);
        $profilePicturePath = 'profile_pictures/' . $profilePictureName;
        
        // Save profile picture to user_documents table
        \App\Models\UserDocument::create([
            'user_id' => $user->id,
            'document_type' => 'profile_picture',
            'file_path' => $profilePicturePath
        ]);
    }

    // Handle supporting documents upload
    if ($request->hasFile('supporting_documents')) {
        foreach ($request->file('supporting_documents') as $document) {
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->storeAs('public/supporting_documents', $documentName);
            $documentPath = 'supporting_documents/' . $documentName;
            
            // Save each supporting document to user_documents table
            \App\Models\UserDocument::create([
                'user_id' => $user->id,
                'document_type' => 'supporting_document',
                'file_path' => $documentPath
            ]);
        }
    }

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

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/password/enter-token', [ForgotPasswordController::class, 'showTokenForm'])->name('password.token');
Route::post('/password/verify-token', [ForgotPasswordController::class, 'verifyToken'])->name('password.token.submit');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard (for testing)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

// Role-specific dashboard routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/system-flow', [DashboardController::class, 'systemFlow'])->name('admin.system-flow');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('admin.reports');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings');
    Route::get('/backups', [DashboardController::class, 'backups'])->name('admin.backups');
    Route::post('/backups/create', [DashboardController::class, 'createBackup'])->name('admin.backups.create');

    // User management specific routes
    Route::get('/user-management', [UserController::class, 'index'])->name('admin.user-management');
    Route::get('/user-management/user/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('/user-management/user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/user-management/user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user-management/user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    // User validation routes
    Route::get('/user-validation', [UserController::class, 'validation'])->name('admin.user-validation');
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('admin.users.approve');
    Route::post('/users/{user}/reject', [UserController::class, 'reject'])->name('admin.users.reject');
    
    // Validation criteria routes
    Route::get('/validation-criteria', [ValidationCriteriaController::class, 'index'])->name('admin.validation-criteria');
    Route::post('/validation-criteria', [ValidationCriteriaController::class, 'store'])->name('admin.validation-criteria.store');
    Route::put('/validation-criteria/{rule}', [ValidationCriteriaController::class, 'update'])->name('admin.validation-criteria.update');
    Route::delete('/validation-criteria/{rule}', [ValidationCriteriaController::class, 'destroy'])->name('admin.validation-criteria.destroy');

    // Visit scheduling routes
    Route::get('/visit-scheduling', [VisitController::class, 'index'])->name('admin.visit-scheduling');
    Route::post('/visit-scheduling', [VisitController::class, 'store'])->name('admin.visit-scheduling.store');
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

Auth::routes();