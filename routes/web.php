<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\RetailController;
use Illuminate\Support\Facades\Route;

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
    return view('registration');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/application-status', function () {
    return view('application-status');
})->name('application-status');

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('index');
    })->name('dashboard');

    Route::get('/admin', function () {
        return view('admin-dashboard');
    })->name('admin');

    Route::get('/manufacturer', function () {
        return view('manufacturer-dashboard');
    })->name('manufacturer');

    Route::get('/supplier', function () {
        return view('supplier-dashboard');
    })->name('supplier');

    Route::get('/inventory', function () {
        return view('inventory');
    })->name('inventory');

    Route::get('/supply-chain', function () {
        return view('supply-chain');
    })->name('supply-chain');

    Route::get('/manufacturing', function () {
        return view('manufacturing');
    })->name('manufacturing');

    Route::get('/retail', function () {
        return view('retail');
    })->name('retail');

    Route::get('/vendors', function () {
        return view('vendors');
    })->name('vendors');

    Route::get('/communications', function () {
        return view('communications');
    })->name('communications');

    Route::get('/analytics', function () {
        return view('analytics');
    })->name('analytics');

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
