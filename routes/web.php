<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('clients.index');
})->middleware(['auth'])->name('dashboard');

// All client routes require authentication
Route::middleware(['auth'])->group(function () {

    // Create — admin and editor only (MUST be before {client} wildcard)
    Route::middleware('role:admin,editor')->group(function () {
            Route::get('/clients/create', [ClientController::class , 'create'])->name('clients.create');
            Route::post('/clients', [ClientController::class , 'store'])->name('clients.store');
        }
        );

        // View routes — all authenticated users
        Route::get('/clients', [ClientController::class , 'index'])->name('clients.index');
        Route::get('/clients/{client}', [ClientController::class , 'show'])->name('clients.show');
        Route::get('/clients/{client}/print', [ClientController::class , 'print'])->name('clients.print');

        // Edit — admin and editor only
        Route::middleware('role:admin,editor')->group(function () {
            Route::get('/clients/{client}/edit', [ClientController::class , 'edit'])->name('clients.edit');
            Route::put('/clients/{client}', [ClientController::class , 'update'])->name('clients.update');
        }
        );

        // Delete — admin only
        Route::middleware('role:admin')->group(function () {
            Route::delete('/clients/{client}', [ClientController::class , 'destroy'])->name('clients.destroy');
        }
        );

        // User Management — admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [UserController::class , 'index'])->name('users.index');
            Route::patch('/users/{user}/role', [UserController::class , 'updateRole'])->name('users.updateRole');
        }
        );

        // Audit Logs — admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/audit-logs', [AuditLogController::class , 'index'])->name('audit-logs.index');
            Route::get('/audit-logs/latest', [AuditLogController::class , 'latest'])->name('audit-logs.latest');
        }
        );

        // Profile routes (from Breeze)
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
    });

require __DIR__ . '/auth.php';