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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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


require __DIR__.'/auth.php';
