<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AircraftController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\MedicalController;
use App\Http\Controllers\MroController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/success', function () { return view('success'); })->name('success');

// Public Service Pages
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/aircraft', function () { return view('services.aircraft'); })->name('aircraft');
    Route::get('/mro', function () { return view('services.mro'); })->name('mro');
    Route::get('/flight', function () { return view('services.flight'); })->name('flight');
    Route::get('/medical', function () { return view('services.medical'); })->name('medical');
    Route::get('/organization', function () { return view('services.organization'); })->name('organization');
});

// Public Application Forms
Route::prefix('apply')->name('apply.')->group(function () {
    Route::get('/aircraft', [AircraftController::class, 'create'])->name('aircraft');
    Route::post('/aircraft', [AircraftController::class, 'store'])->name('aircraft.store');
    
    Route::get('/mro', [MroController::class, 'create'])->name('mro');
    Route::post('/mro', [MroController::class, 'store'])->name('mro.store');
    
    Route::get('/flight', [FlightController::class, 'create'])->name('flight');
    Route::post('/flight', [FlightController::class, 'store'])->name('flight.store');
    
    Route::get('/medical', [MedicalController::class, 'create'])->name('medical');
    Route::post('/medical', [MedicalController::class, 'store'])->name('medical.store');
    
    Route::get('/organization', [OrganizationController::class, 'create'])->name('organization');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('organization.store');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        // Aircraft CRUD
        Route::get('/aircraft', [AircraftController::class, 'index'])->name('aircraft');
        Route::get('/aircraft/{aircraft}/edit', [AircraftController::class, 'edit'])->name('aircraft.edit');
        Route::put('/aircraft/{aircraft}', [AircraftController::class, 'update'])->name('aircraft.update');
        Route::delete('/aircraft/{aircraft}', [AircraftController::class, 'destroy'])->name('aircraft.destroy');

        // MRO CRUD
        Route::get('/mro', [MroController::class, 'index'])->name('mro');
        Route::get('/mro/{mro}/edit', [MroController::class, 'edit'])->name('mro.edit');
        Route::put('/mro/{mro}', [MroController::class, 'update'])->name('mro.update');
        Route::delete('/mro/{mro}', [MroController::class, 'destroy'])->name('mro.destroy');

        // Flight CRUD
        Route::get('/flight', [FlightController::class, 'index'])->name('flight');
        Route::get('/flight/{flight}/edit', [FlightController::class, 'edit'])->name('flight.edit');
        Route::put('/flight/{flight}', [FlightController::class, 'update'])->name('flight.update');
        Route::delete('/flight/{flight}', [FlightController::class, 'destroy'])->name('flight.destroy');

        // Medical CRUD
        Route::get('/medical', [MedicalController::class, 'index'])->name('medical');
        Route::get('/medical/{medical}/edit', [MedicalController::class, 'edit'])->name('medical.edit');
        Route::put('/medical/{medical}', [MedicalController::class, 'update'])->name('medical.update');
        Route::delete('/medical/{medical}', [MedicalController::class, 'destroy'])->name('medical.destroy');

        // Organization CRUD
        Route::get('/organization', [OrganizationController::class, 'index'])->name('organization');
        Route::get('/organization/{organization}/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
        Route::put('/organization/{organization}', [OrganizationController::class, 'update'])->name('organization.update');
        Route::delete('/organization/{organization}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
    });
});

require __DIR__.'/auth.php';

