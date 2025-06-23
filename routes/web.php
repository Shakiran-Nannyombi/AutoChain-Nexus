<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\UserMigrationService;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ValidationCriteriaController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SearchController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Handle login
Route::post('/login', function (Illuminate\Http\Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'required|in:admin,manufacturer,supplier,vendor,retailer,analyst',
    ]);

    $email = $request->email;
    $password = $request->password;
    $role = $request->role;

    // Use the UserMigrationService to authenticate
    $userMigrationService = new UserMigrationService();
    $user = $userMigrationService->authenticateUser($email, $password, $role);

    if ($user) {
        // For non-admin users, ensure they are migrated to role table
        if ($role !== 'admin' && $user instanceof User) {
            try {
                $userMigrationService->migrateUserToRoleTable($user);
            } catch (\Exception $e) {
                Log::error("Migration failed for {$email} on login: " . $e->getMessage());
                return back()->withErrors(['email' => 'An error occurred during account setup. Please contact support.']);
            }
            // Log in the user with Laravel Auth
            Auth::login($user);
        }

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

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('verify-email', \App\Http\Controllers\Auth\EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Auth\VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::put('password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

// Dashboard (for testing)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

// Role-specific dashboard routes
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/search', [SearchController::class, 'search'])->name('admin.search');
    Route::get('/system-flow', [DashboardController::class, 'systemFlow'])->name('admin.system-flow');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('admin.reports');
    Route::post('/reports', [DashboardController::class, 'scheduleReport'])->name('admin.reports.schedule');
    Route::delete('/reports/{report}', [DashboardController::class, 'destroyReport'])->name('admin.reports.destroy');
    Route::get('/inventory-overview', [DashboardController::class, 'inventoryOverview'])->name('admin.inventory.overview');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
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
    Route::post('/users/{user}/validate', [UserController::class, 'runValidation'])->name('admin.user.validate');

    // Validation criteria routes
    Route::get('/validation-criteria', [ValidationCriteriaController::class, 'index'])->name('admin.validation-criteria');
    Route::post('/validation-criteria', [ValidationCriteriaController::class, 'store'])->name('admin.validation.store');
    Route::put('/validation-criteria/{rule}', [ValidationCriteriaController::class, 'update'])->name('admin.validation.update');
    Route::delete('/validation-criteria/{rule}', [ValidationCriteriaController::class, 'destroy'])->name('admin.validation.destroy');
    Route::post('/validation-sync', [ValidationCriteriaController::class, 'syncRules'])->name('admin.validation.sync');

    // Visit scheduling routes
    Route::get('/visit-scheduling', [VisitController::class, 'index'])->name('admin.visit-scheduling');
    Route::post('/visits/{visit}/approve', [VisitController::class, 'approve'])->name('admin.visits.approve');
    Route::post('/visits/{visit}/reject', [VisitController::class, 'reject'])->name('admin.visits.reject');
    Route::post('/visits/{visit}/confirm', [VisitController::class, 'sendConfirmationEmail'])->name('admin.visits.confirm');
    Route::post('/visits/{visit}/reschedule', [VisitController::class, 'reschedule'])->name('admin.visits.reschedule');
    Route::post('/visits/{visit}/complete', [VisitController::class, 'complete'])->name('admin.visits.complete');

    // Backup management routes
    Route::get('/backups', [BackupController::class, 'index'])->name('admin.backups');
    Route::post('/backups/create', [BackupController::class, 'create'])->name('admin.backups.create');
    Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('admin.backups.download');
    Route::delete('/backups/{filename}', [BackupController::class, 'delete'])->name('admin.backups.delete');
    Route::post('/backups/{filename}/restore', [BackupController::class, 'restore'])->name('admin.backups.restore');
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
})->name('logout');

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

        // Debug: Log the session data
        Log::info('Admin login successful', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'session_data' => session()->all()
        ]);

        return redirect('/admin/dashboard');
    }

    // Debug: Log failed login attempt
    Log::info('Admin login failed', [
        'email' => $email,
        'attempted_at' => now()
    ]);

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('admin.login.submit');

Route::middleware(\App\Http\Middleware\EnsureUserIsAuthenticated::class)->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Manufacturer dashboard routes
Route::prefix('manufacturer')->group(function () {
    Route::get('/dashboard', function () { return view('dashboards.manufacturer.index'); })->name('manufacturer.dashboard');
    Route::get('/production-lines', function () { return view('dashboards.manufacturer.production-lines'); })->name('manufacturer.production-lines');
    Route::get('/machine-health', function () { return view('dashboards.manufacturer.machine-health'); })->name('manufacturer.machine-health');
    Route::get('/quality-control', function () { return view('dashboards.manufacturer.quality-control'); })->name('manufacturer.quality-control');
    Route::get('/maintenance', function () { return view('dashboards.manufacturer.maintenance'); })->name('manufacturer.maintenance');
    Route::get('/inventory-status', function () { return view('dashboards.manufacturer.inventory-status'); })->name('manufacturer.inventory-status');
    Route::get('/scheduling', function () { return view('dashboards.manufacturer.scheduling'); })->name('manufacturer.scheduling');
    Route::get('/checklists', function () { return view('dashboards.manufacturer.checklists'); })->name('manufacturer.checklists');
    Route::get('/material-receipt', function () { return view('dashboards.manufacturer.material-receipt'); })->name('manufacturer.material-receipt');
    Route::get('/workflow', function () { return view('dashboards.manufacturer.workflow'); })->name('manufacturer.workflow');
    Route::get('/production-analytics', function () { return view('dashboards.manufacturer.production-analytics'); })->name('manufacturer.production-analytics');
    Route::get('/production-reports', function () { return view('dashboards.manufacturer.production-reports'); })->name('manufacturer.production-reports');
    Route::get('/demand-prediction', function () { return view('dashboards.manufacturer.demand-prediction'); })->name('manufacturer.demand-prediction');
    Route::get('/chat', function () { return view('dashboards.manufacturer.chat'); })->name('manufacturer.chat');
    Route::get('/settings', function () { return view('dashboards.manufacturer.settings'); })->name('manufacturer.settings');
});

// Supplier dashboard routes
Route::prefix('supplier')->group(function () {
    Route::get('/dashboard', function () { return view('dashboards.supplier.index'); })->name('supplier.dashboard');
    Route::get('/stock-management', function () { return view('dashboards.supplier.stock-management'); })->name('supplier.stock-management');
    Route::get('/checklist-receipt', function () { return view('dashboards.supplier.checklist-receipt'); })->name('supplier.checklist-receipt');
    Route::get('/delivery-history', function () { return view('dashboards.supplier.delivery-history'); })->name('supplier.delivery-history');
    Route::get('/chat', function () { return view('dashboards.supplier.chat'); })->name('supplier.chat');
    Route::get('/notifications', function () { return view('dashboards.supplier.notifications'); })->name('supplier.notifications');
    Route::get('/settings', function () { return view('dashboards.supplier.settings'); })->name('supplier.settings');
});

// Vendor dashboard routes
Route::prefix('vendor')->group(function () {
    Route::get('/dashboard', function () { return view('dashboards.vendor.index'); })->name('vendor.dashboard');
    Route::get('/warehouse', function () { return view('dashboards.vendor.warehouse'); })->name('vendor.warehouse');
    Route::get('/delivery', function () { return view('dashboards.vendor.delivery'); })->name('vendor.delivery');
    Route::get('/tracking', function () { return view('dashboards.vendor.tracking'); })->name('vendor.tracking');
    Route::get('/chat', function () { return view('dashboards.vendor.chat'); })->name('vendor.chat');
    Route::get('/notifications', function () { return view('dashboards.vendor.notifications'); })->name('vendor.notifications');
    Route::get('/settings', function () { return view('dashboards.vendor.settings'); })->name('vendor.settings');
});