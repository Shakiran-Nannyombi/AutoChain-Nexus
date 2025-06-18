<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\RetailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplyChainController;

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

Route::get('/application-status', function () {
    return view('dashboard_application_status', [
        'user' => Auth::user(),
    ]);
})->middleware(['auth'])->name('application-status');

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/analyst', function () {
        return view('pages.analyst-dashboard');
    })->name('analyst');

    Route::get('/manufacturer', function () {
        return view('pages.manufacturer-dashboard');
    })->name('manufacturer');

    Route::get('/supplier', function () {
        return view('pages.supplier-dashboard');
    })->name('supplier');

    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [App\Http\Controllers\InventoryController::class, 'store'])->name('inventory.store');

    Route::get('/supply-chain', [App\Http\Controllers\SupplyChainController::class, 'index'])->name('supply-chain');
    Route::post('/purchase-orders', [App\Http\Controllers\SupplyChainController::class, 'store'])->name('purchase-orders.store');

    Route::get('/manufacturing', function () {
        return view('pages.manufacturing');
    })->name('manufacturing');

    Route::get('/retail', function () {
        return view('pages.retail');
    })->name('retail');

    Route::get('/vendors', function () {
        return view('pages.vendors');
    })->name('vendors');

    Route::get('/communications', function () {
        return view('pages.communications');
    })->name('communications');

    Route::get('/analytics', function () {
        return view('pages.analytics');
    })->name('analytics');

    Route::get('/reports', function () {
        return view('pages.reports');
    })->name('reports');

    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');

    // Activity Logs Route
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Profile routes
    Route::view('/profile', 'profile.edit')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/users', [App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('users.store');
});

Route::middleware(['auth'])->prefix('supplier')->group(function () {
    Route::post('/raw-material/add', [SupplierController::class, 'addRawMaterial']);
    Route::post('/deliver', [SupplierController::class, 'deliverRawMaterial']);
    Route::get('/deliveries', [SupplierController::class, 'myDeliveries']);
});

Route::middleware(['auth'])->prefix('manufacturer')->group(function () {
    Route::post('/checklist', [ManufacturerController::class, 'createChecklist']);
    Route::get('/checklist/status', [ManufacturerController::class, 'checklistStatus']);
    Route::post('/production/start', [ManufacturerController::class, 'startProduction']);
    Route::put('/production/advance/{id}', [ManufacturerController::class, 'advanceProduction']);
    Route::post('/production/send/{id}', [ManufacturerController::class, 'sendToWarehouse']);
});

Route::get('/warehouses', [WarehouseController::class, 'index']);
Route::get('/warehouses/{id}', [WarehouseController::class, 'show']);
Route::post('/warehouses/create', [WarehouseController::class, 'create']); // Admin only
Route::post('/warehouses/store-car', [WarehouseController::class, 'storeCar']);

Route::prefix('distribution')->group(function () {
    Route::post('/assign-transport', [DistributionController::class, 'assignTransport']);
    Route::get('/track-shipment/{id}', [DistributionController::class, 'trackShipment']);
    Route::put('/update-shipment/{id}', [DistributionController::class, 'updateShipmentStatus']);
});

Route::prefix('retail')->group(function () {
    Route::post('/receive-shipment', [RetailController::class, 'receiveShipment']);
    Route::post('/record-purchase', [RetailController::class, 'recordPurchase']);
});


// 404 Page Route - Must be last
Route::fallback(function () {
    return response()->view('not-found', [], 404);
});

require __DIR__.'/auth.php';
