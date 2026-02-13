<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->middleware(['auth'])->name('dashboard');

// Client Portal (Public)
Route::get('/portal/{client:uuid}', [\App\Http\Controllers\ClientPortalController::class , 'show'])->name('portal.show');
Route::get('/portal/{client:uuid}/print', [\App\Http\Controllers\ClientPortalController::class , 'downloadPdf'])->name('portal.print');

// All client routes require authentication
Route::middleware(['auth'])->group(function () {

    // Create — admin and editor only (MUST be before {client} wildcard)
    Route::middleware('role:admin,editor')->group(function () {
            Route::get('/clients/create', [ClientController::class , 'create'])->name('clients.create');
            Route::post('/clients', [ClientController::class , 'store'])->name('clients.store');
        }
        );

        // --- Team Chat Routes ---
        Route::get('/chat', [\App\Http\Controllers\ChatController::class , 'index'])->name('chat.index');
        Route::get('/chat/fetch', [\App\Http\Controllers\ChatController::class , 'fetch'])->name('chat.fetch');
        Route::post('/chat/send', [\App\Http\Controllers\ChatController::class , 'store'])->name('chat.store');

        // View routes — all authenticated users
        Route::get('/clients', [ClientController::class , 'index'])->name('clients.index');
        Route::get('/clients/{client}', [ClientController::class , 'show'])->name('clients.show');
        Route::get('/clients/{client}/print', [ClientController::class , 'print'])->name('clients.print');

        // Edit — admin and editor only
        Route::middleware('role:admin,editor')->group(function () {
            Route::get('/clients/{client}/edit', [ClientController::class , 'edit'])->name('clients.edit');
            Route::put('/clients/{client}', [ClientController::class , 'update'])->name('clients.update');
            Route::post('/clients/{client}/gallery', [\App\Http\Controllers\ProjectGalleryController::class , 'store'])->name('gallery.store');
        }
        );

        // Delete — admin only
        Route::middleware('role:admin')->group(function () {
            Route::delete('/clients/{client}', [ClientController::class , 'destroy'])->name('clients.destroy');
            Route::delete('/gallery/{gallery}', [\App\Http\Controllers\ProjectGalleryController::class , 'destroy'])->name('gallery.destroy');
        }
        );

        // User Management — admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [UserController::class , 'index'])->name('users.index');
            Route::post('/users', [UserController::class , 'store'])->name('users.store');
            Route::patch('/users/{user}/role', [UserController::class , 'updateRole'])->name('users.updateRole');
            Route::delete('/users/{user}', [UserController::class , 'destroy'])->name('users.destroy');
            Route::resource('roles', \App\Http\Controllers\RoleController::class);
        }
        );

        // Audit Logs — admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/audit-logs', [AuditLogController::class , 'index'])->name('audit-logs.index');
            Route::get('/audit-logs/latest', [AuditLogController::class , 'latest'])->name('audit-logs.latest');

            // Attendance Logs - Admin View
            Route::get('/attendances', [AttendanceController::class , 'index'])->name('attendances.index');
        }
        );

        // Attendance Actions (All Authenticated Users)
        Route::get('/attendance/status', [AttendanceController::class , 'status'])->name('attendance.status');
        Route::post('/attendance/check-in', [AttendanceController::class , 'checkIn'])->name('attendance.checkIn');
        Route::post('/attendance/check-out', [AttendanceController::class , 'checkOut'])->name('attendance.checkOut');

        // Profile routes (from Breeze)
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

        // Payment Routes
        Route::get('/payments/{payment}/receipt', [\App\Http\Controllers\PaymentController::class , 'receipt'])->name('payments.receipt');

        // Inventory Catalog
        Route::resource('inventory', \App\Http\Controllers\InventoryController::class);

        // Site Materials
        Route::post('/project-materials', [\App\Http\Controllers\ProjectMaterialController::class , 'store'])->name('project-materials.store');
        Route::patch('/project-materials/{material}/status', [\App\Http\Controllers\ProjectMaterialController::class , 'updateStatus'])->name('project-materials.updateStatus');
        Route::delete('/project-materials/{material}', [\App\Http\Controllers\ProjectMaterialController::class , 'destroy'])->name('project-materials.destroy');

        // Payment Request Routes
        Route::patch('/payment-requests/{payment_request}/status', [\App\Http\Controllers\PaymentRequestController::class , 'updateStatus'])->name('payment-requests.updateStatus');
        Route::resource('payment-requests', \App\Http\Controllers\PaymentRequestController::class);

        // Quotation Routes
        Route::patch('/quotations/{quotation}/status', [\App\Http\Controllers\QuotationController::class , 'updateStatus'])->name('quotations.updateStatus');
        Route::resource('quotations', \App\Http\Controllers\QuotationController::class);

        // --- Pitching Phase Features ---
        // Estimate Builder
        Route::get('/estimate-builder', [\App\Http\Controllers\EstimateBuilderController::class , 'index'])->name('estimate-builder.index');
        Route::post('/estimate-builder/calculate', [\App\Http\Controllers\EstimateBuilderController::class , 'calculate'])->name('estimate-builder.calculate');

        // Portfolio Board
        Route::get('/portfolio', [\App\Http\Controllers\PortfolioController::class , 'index'])->name('portfolio.index');

        // --- Execution Phase Features ---
        Route::post('/clients/{client}/dpr', [\App\Http\Controllers\ExecutionController::class , 'storeDPR'])->name('execution.dpr.store');
        Route::post('/clients/{client}/change-requests', [\App\Http\Controllers\ExecutionController::class , 'storeChangeRequest'])->name('execution.change-request.store');
        Route::patch('/change-requests/{changeRequest}/status', [\App\Http\Controllers\ExecutionController::class , 'updateChangeRequestStatus'])->name('execution.change-request.update');

        // --- Financial Management Features ---
        Route::post('/clients/{client}/expenses', [\App\Http\Controllers\FinanceController::class , 'storeExpense'])->name('finance.expense.store');
        Route::get('/clients/{client}/analytics', [\App\Http\Controllers\FinanceController::class , 'analytics'])->name('finance.analytics');
        Route::post('/payment-requests/{paymentRequest}/reminder', [\App\Http\Controllers\FinanceController::class , 'sendReminder'])->name('finance.reminder.send');

        // --- Handover & Feedback ---
        Route::post('/handovers/{handover}/items', [\App\Http\Controllers\HandoverController::class , 'storeChecklistItem'])->name('handover.item.store');
        Route::patch('/handover-items/{item}', [\App\Http\Controllers\HandoverController::class , 'updateChecklistStatus'])->name('handover.item.update');
        Route::post('/clients/{client}/handover/complete', [\App\Http\Controllers\HandoverController::class , 'completeHandover'])->name('handover.complete');
        Route::post('/clients/{client}/feedback', [\App\Http\Controllers\HandoverController::class , 'storeFeedback'])->name('feedback.store');

        // --- Scope of Work ---
        Route::post('/clients/{client}/scope', [\App\Http\Controllers\ScopeOfWorkController::class , 'store'])->name('scope.store');
        Route::post('/scope/{scope}/item', [\App\Http\Controllers\ScopeOfWorkController::class , 'storeItem'])->name('scope.item.store');

        Route::get('/test-notification', function () {
            auth()->user()->notify(new \App\Notifications\SystemTestNotification('Test Notification at ' . now()->format('h:i A')));
            return back()->with('success', 'Test notification sent to your bell icon!');
        })->name('test.notification');

        Route::post('/notifications/mark-all-read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.markAllRead');
    });


require __DIR__ . '/auth.php';