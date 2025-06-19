<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Manufacturer\ManufacturerController;
use App\Http\Controllers\Warehouse\WarehouseController;
use App\Http\Controllers\SupplyChain\DistributionController;
use App\Http\Controllers\Retailer\RetailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ActivityLogController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\SupplyChain\SupplyChainController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Analysts\AnalyticsController;
use App\Http\Controllers\Analysts\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserApprovalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Application Status Route - Accessible by authenticated users
Route::get('/application-status', function () {
    return view('application-status', [
        'user' => Auth::user(),
    ]);
})->middleware(['auth'])->name('application-status');

// Protected Routes (Authenticated & Verified Users)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Routes (Role-based)
    Route::get('/analyst', function () {
        return view('pages.analyst-dashboard');
    })->name('analyst');

    Route::get('/manufacturer', [ManufacturerController::class, 'index'])->name('manufacturer');

    Route::get('/supplier', function () {
        return view('pages.supplier-dashboard');
    })->name('supplier');

    Route::get('/retail', [RetailController::class, 'index'])->name('retail');

    // Inventory Management Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');

    // Supply Chain Management Routes
    Route::get('/supply-chain', [SupplyChainController::class, 'index'])->name('supply-chain');
    Route::post('/purchase-orders', [SupplyChainController::class, 'store'])->name('purchase-orders.store');

    // Manufacturing Process Routes
    Route::get('/manufacturing', [ManufacturerController::class, 'index'])->name('manufacturing.index');
    Route::post('/work-orders', [ManufacturerController::class, 'store'])->name('work-orders.store');
    Route::post('/manufacturer/checklist', [ManufacturerController::class, 'createChecklist']);
    Route::get('/manufacturer/checklist/status', [ManufacturerController::class, 'checklistStatus']);
    Route::post('/manufacturer/production/start', [ManufacturerController::class, 'startProduction']);
    Route::put('/manufacturer/production/advance/{id}', [ManufacturerController::class, 'advanceProductionStage']); // Corrected method name
    Route::post('/manufacturer/production/send/{id}', [ManufacturerController::class, 'sendToWarehouse']);

    // Vendor Management Routes (Supplier)
    Route::get('/vendors', [SupplierController::class, 'index'])->name('vendors');
    Route::post('/vendors', [SupplierController::class, 'store'])->name('vendors.store');
    Route::post('/supplier/raw-material/add', [SupplierController::class, 'addRawMaterial']);
    Route::post('/supplier/deliver', [SupplierController::class, 'deliverRawMaterial']);
    Route::get('/supplier/deliveries', [SupplierController::class, 'myDeliveries']);

    // Communication Routes
    Route::get('/communications', [ChatController::class, 'index'])->name('communications');

    // Analytics & Reports Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Activity Logs Route
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard'); // Changed to dashboard to avoid conflict with /admin
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::patch('/users/{id}/approve', [UserApprovalController::class, 'approve'])->name('users.approve'); // Corrected controller and method
        Route::patch('/users/{id}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');   // Corrected controller and method
        // Route::get('/users/{id}/documents', [AdminDashboardController::class, 'viewUserDocuments'])->name('viewUserDocuments'); // This route seems to be using a method not defined in AdminDashboardController
        // Route::patch('/users/{id}/deactivate', [AdminDashboardController::class, 'deactivateUser'])->name('deactivateUser'); // This route seems to be using a method not defined in AdminDashboardController
    });

    // Warehouse Routes
    Route::prefix('warehouses')->group(function () {
        Route::get('/', [WarehouseController::class, 'index']);
        Route::get('/{id}', [WarehouseController::class, 'show']);
        Route::post('/create', [WarehouseController::class, 'create']); // Admin only
        Route::post('/store-car', [WarehouseController::class, 'storeCar']);
    });

    // Distribution Routes
    Route::prefix('distribution')->group(function () {
        Route::post('/assign-transport', [DistributionController::class, 'assignTransport']);
        Route::get('/track-shipment/{id}', [DistributionController::class, 'trackShipment']);
        Route::put('/update-shipment/{id}', [DistributionController::class, 'updateShipmentStatus']);
    });

    // Retail Operations Routes
    Route::prefix('retail')->group(function () {
        Route::post('/receive-shipment', [RetailController::class, 'receiveShipment']);
        Route::post('/record-purchase', [RetailController::class, 'recordPurchase']);
    });

});


// Auth Routes (Laravel Breeze default)
require __DIR__.'/auth.php';

// 404 Page Route - Must be last
Route::fallback(function () {
    return response()->view('not-found', [], 404);
});
