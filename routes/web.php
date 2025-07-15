<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
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
use App\Http\Controllers\Manufacturer\DemandPrediction;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\ChatController;
// Add missing analyst controllers
use App\Http\Controllers\AnalystController;
use App\Http\Controllers\AnalystReportController;
use App\Http\Controllers\ManufacturerDashboardController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\CustomerDashboardController;


// Welcome page
Route::get('/', function () {
    return view('welcome');
}) ->name('home');
//


//
// Demand prediction route
Route::post('/predict-demand', [DemandPrediction::class, 'getDemandForecast']);


// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Handle login
Route::post('/login', function (Illuminate\Http\Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $email = $request->email;
    $password = $request->password;

    // Authenticate user by email and password only
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user || !password_verify($password, $user->password)) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    // Optionally check if user is approved
    if ($user->status !== 'approved') {
        return back()->withErrors([
            'email' => 'Your account is not approved yet.',
        ]);
    }
    // Log in the user with Laravel Auth
    Auth::login($user);
    // Store user info in session
    session([
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_role' => $user->role
    ]);
    // Redirect to role-specific dashboard
    switch ($user->role) {
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
});

// Register page
Route::get('/register', function () {
    $approvedManufacturers = \App\Models\User::where('role', 'manufacturer')->where('status', 'approved')->get();
    return view('auth.register', compact('approvedManufacturers'));
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
        'manufacturer_id' => 'nullable|exists:users,id',
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
        'manufacturer_id' => $request->role === 'vendor' ? $request->manufacturer_id : null,
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
Route::get('/reset-password/{token}', function ($token) {
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
})->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->name('dashboard');


// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Handle login
Route::post('/login', function (Illuminate\Http\Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $email = $request->email;
    $password = $request->password;

    // Authenticate user by email and password only
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user || !password_verify($password, $user->password)) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    // Optionally check if user is approved
    if ($user->status !== 'approved') {
        return back()->withErrors([
            'email' => 'Your account is not approved yet.',
        ]);
    }
    // Log in the user with Laravel Auth
    Auth::login($user);
    // Store user info in session
    session([
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_role' => $user->role
    ]);
    // Redirect to role-specific dashboard
    switch ($user->role) {
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
});

// Register page
Route::get('/register', function () {
    $approvedManufacturers = \App\Models\User::where('role', 'manufacturer')->where('status', 'approved')->get();
    return view('auth.register', compact('approvedManufacturers'));
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
        'manufacturer_id' => 'nullable|exists:users,id',
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
        'manufacturer_id' => $request->role === 'vendor' ? $request->manufacturer_id : null,
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
Route::get('/reset-password/{token}', function ($token) {
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
})->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->name('dashboard');

// Role-specific dashboard routes
Route::middleware(['admin', \App\Http\Middleware\PreventBackAfterLogout::class])->prefix('admin')->group(function () {
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
    Route::get('/chat', [DashboardController::class, 'chat'])->name('admin.chat');
    Route::get('/chat/messages/{userId}', [App\Http\Controllers\Admin\DashboardController::class, 'getChatMessages'])->name('admin.chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\Admin\DashboardController::class, 'sendChatMessage'])->name('admin.chat.send');

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

Route::get('/manufacturer/dashboard', [ManufacturerDashboardController::class, 'index'])
    ->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)
    ->name('manufacturer.dashboard');

Route::get('/supplier/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'supplier') {
        return redirect('/login');
    }
    
    return view('dashboards.supplier.index');
})->middleware(\App\Http\Middleware\PreventBackAfterLogout::class);

Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])
    ->middleware(\App\Http\Middleware\PreventBackAfterLogout::class);

Route::get('/retailer/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'retailer') {
        return redirect('/login');
    }
    
    return view('dashboards.retailer.index');
})->middleware(\App\Http\Middleware\PreventBackAfterLogout::class);

Route::get('/analyst/dashboard', function () {
    if (!session('user_id') || session('user_role') !== 'analyst') {
        return redirect('/login');
    }
    return view('dashboards.analyst.index');
})->name('analyst.dashboard');

// Analyst dashboard routes
Route::prefix('analyst')->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->group(function () {
    Route::get('/dashboard', function () { return view('dashboards.analyst.index'); })->name('analyst.dashboard');
    Route::get('/profile', function () { return view('dashboards.analyst.profile'); })->name('analyst.profile');
    Route::get('/settings', function () { return view('dashboards.analyst.settings'); })->name('analyst.settings');
    Route::get('/analyst/inventory-analysis', [AnalystController::class, 'inventoryAnalysis'])->name('analyst.inventory-analysis');
    Route::get('/analyst/trends', [AnalystController::class, 'trends'])->name('analyst.trends');
    Route::get('/analyst/reports', [AnalystReportController::class, 'index'])->name('analyst.reports');
    Route::get('/analyst/reports/generate', [AnalystReportController::class, 'create'])->name('analyst.reports.create');
    Route::post('/analyst/reports/generate', [AnalystReportController::class, 'store'])->name('analyst.reports.store');


});

// Logout route
Route::get('/logout', function () {
    // Clear all authentication guards
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    
    // Clear all session data
    session()->flush();
    session()->invalidate();
    session()->regenerateToken();
    
    // Clear any remember me cookies
    Cookie::queue(Cookie::forget('remember_web'));
    Cookie::queue(Cookie::forget('remember_admin'));
    
    // Add cache-busting headers to prevent browser back button access
    return redirect('/login')
        ->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT'
        ]);
})->name('logout');

// Admin login page
Route::get('/admin/login', function () {
    return view('auth.admin-login');
});

// Admin logout route
Route::get('/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

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
    $admin = $userMigrationService->authenticateUser($email, $password, 'admin');

    if ($admin) {
        // Store admin info in session
        session([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
            'user_role' => 'admin'
        ]);
        
        // Use the admin guard for authentication
        Auth::guard('admin')->login($admin);

        // Debug: Log the session data
        Log::info('Admin login successful', [
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
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

Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
    ->name('customer.dashboard');

Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'list'])->name('customer.list');
Route::get('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'show'])->name('customer.show');

Route::middleware(\App\Http\Middleware\EnsureUserIsAuthenticated::class)->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/customer/settings', function () {
    return view('dashboards.customer.settings');
})->name('customer.settings');

// Manufacturer dashboard routes
Route::prefix('manufacturer')->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->group(function () {
    // Route::get('/dashboard', function () { return view('dashboards.manufacturer.index'); })->name('manufacturer.dashboard'); // Removed to allow controller-based route
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
    Route::get('/products', function () { return view('dashboards.manufacturer.products'); })->name('manufacturer.products');
    Route::get('/orders', function () { return view('dashboards.manufacturer.orders'); })->name('manufacturer.orders');
});

// Supplier dashboard routes
Route::prefix('supplier')->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->group(function () {
    Route::get('/dashboard', function () { return view('dashboards.supplier.index'); })->name('supplier.dashboard');
    Route::get('/stock-management', [SupplierController::class, 'stockManagement'])->name('supplier.stock-management');
    Route::post('/stock-management/add', [SupplierController::class, 'addStock'])->name('supplier.stock.add');
    Route::get('/checklist-receipt',[SupplierController::class, 'checklistReceipt'])->name('supplier.checklist-receipt');
    Route::post('/checklist-receipt/fulfill/{id}', [SupplierController::class, 'fulfillChecklist'])->name('supplier.checklist.fulfill');
    Route::get('/delivery-history', [SupplierController::class, 'deliveryHistory'])->name('supplier.delivery-history');
    Route::get('/notifications', function () { return view('dashboards.supplier.notifications'); })->name('supplier.notifications');
    Route::get('/settings', function () { return view('dashboards.supplier.settings'); })->name('supplier.settings');
});

// Vendor dashboard routes
Route::prefix('vendor')->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->group(function () {
    Route::get('/dashboard', function () { return view('dashboards.vendor.index'); })->name('vendor.dashboard');
    Route::get('/warehouse', function () { return view('dashboards.vendor.warehouse'); })->name('vendor.warehouse');
    Route::get('/delivery', function () { return view('dashboards.vendor.delivery'); })->name('vendor.delivery');
    Route::get('/tracking', function () { return view('dashboards.vendor.tracking'); })->name('vendor.tracking');
    Route::get('/settings', function () { return view('dashboards.vendor.settings'); })->name('vendor.settings');
    Route::get('/products', function () { return view('dashboards.vendor.products'); })->name('vendor.products');
    Route::get('/orders', function () { return view('dashboards.vendor.orders'); })->name('vendor.orders');
    Route::get('/analytics', [\App\Http\Controllers\VendorAnalyticsController::class, 'dashboard'])->name('vendor.analytics');
    // Vendor chat route (now renders the Blade view directly and passes $users)
    Route::get('/chats', function () {
        $currentUser = Auth::user();
        
        // Get users from the users table
        $usersFromUsersTable = \App\Models\User::where('id', '!=', $currentUser->id)
            ->whereIn('role', ['admin', 'manufacturer', 'retailer'])
            ->get();
        
        // Get admin users from the admins table
        $adminUsers = \App\Models\Admin::where('is_active', true)
            ->get()
            ->map(function($admin) {
                return (object) [
                    'id' => 'admin_' . $admin->id, // Prefix to avoid ID conflicts
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => 'admin',
                    'profile_photo' => $admin->profile_photo,
                    'company' => $admin->company,
                    'phone' => $admin->phone,
                    'address' => $admin->address,
                    'documents' => collect(), // Empty collection for documents
                ];
            });
        
        // Combine and shuffle the results
        $users = $usersFromUsersTable->concat($adminUsers)->shuffle();
        
        return view('dashboards.vendor.chat', compact('users'));
    })->name('chats.index');
    Route::get('/chats/create', [\App\Http\Controllers\ChatController::class, 'create'])->name('chats.create');
    Route::get('/chats/{chat}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chats.show');
    Route::get('/chats/{chat}/edit', [\App\Http\Controllers\ChatController::class, 'edit'])->name('chats.edit');
    Route::delete('/chats/{chat}', [\App\Http\Controllers\ChatController::class, 'destroy'])->name('chats.destroy');
    Route::get('/chats/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'getChatMessages'])->name('chats.messages');
    Route::post('/chats/send', [\App\Http\Controllers\ChatController::class, 'sendChatMessage'])->name('chats.send');
});

// Retailer dashboard routes
Route::prefix('retailer')->middleware(\App\Http\Middleware\PreventBackAfterLogout::class)->group(function () {
    Route::get('/dashboard', [RetailerController::class, 'dashboard'])->name('retailer.dashboard');

    Route::get('/stock-overview', [RetailerController::class, 'stockOverview'])->name('retailer.stock-overview');
    Route::post('/stock/accept/{id}', [RetailerController::class, 'acceptStock'])->name('retailer.stock.accept');
    Route::post('/stock/reject/{id}', [RetailerController::class, 'rejectStock'])->name('retailer.stock.reject');

    Route::get('/sales-update', [RetailerController::class, 'salesForm'])->name('retailer.sales-update');
    Route::post('/sales-update', [RetailerController::class, 'submitSale'])->name('retailer.sales-update.submit');

    Route::get('/orders', [RetailerController::class, 'orderForm'])->name('retailer.order-placement');
    Route::post('/orders', [RetailerController::class, 'submitOrder'])->name('retailer.orders.submit');

    Route::get('/notifications', function () {
        return view('dashboards.retailer.notifications');
    })->name('retailer.notifications');
});

// Chat routes
Route::middleware(['user_or_admin'])->group(function () {
    // Route::resource('chats', ChatController::class); // Disabled to prevent redirects and enforce AJAX-only chat
    Route::post('chats/{chat}/messages', [ChatController::class, 'storeMessage'])->name('chats.storeMessage');
    Route::get('chats/order/{orderId}', [ChatController::class, 'getOrderChats'])->name('chats.getOrderChats');
    Route::get('chats/unread', [ChatController::class, 'getUnreadMessages'])->name('chats.getUnreadMessages');
    Route::post('chats/{chatId}/read', [ChatController::class, 'markAsRead'])->name('chats.markAsRead');
    Route::get('chats/messages/{message}/edit', [ChatController::class, 'editMessage'])->name('chats.editMessage');
    Route::put('chats/messages/{message}', [ChatController::class, 'updateMessage'])->name('chats.updateMessage');
    Route::delete('chats/messages/{message}', [ChatController::class, 'destroyMessage'])->name('chats.destroyMessage');
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'chat'])->name('user.chat'); // Enable main chat page
    Route::get('/chats/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'getChatMessages'])->name('chats.getMessages');
    Route::post('/chats/send', [\App\Http\Controllers\ChatController::class, 'sendChatMessage'])->name('chats.send');
});

Route::post('/vendor/orders/create', [App\Http\Controllers\VendorOrderController::class, 'store'])->name('vendor.orders.create');
