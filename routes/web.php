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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->name('dashboard');
    Route::get('/timeline', [\App\Http\Controllers\DashboardController::class , 'timeline'])->name('timeline');
    Route::get('/search', [\App\Http\Controllers\SearchController::class , 'index'])->name('search');

    // Client Portal (Public)
    Route::get('/portal/{client:uuid}', [\App\Http\Controllers\ClientPortalController::class , 'show'])->name('portal.show');
    Route::get('/portal/{client:uuid}/print', [\App\Http\Controllers\ClientPortalController::class , 'downloadPdf'])->name('portal.print');
    Route::post('/portal/{client:uuid}/quotation/{quotation}/approve', [\App\Http\Controllers\ClientPortalController::class , 'approveQuotation'])->name('portal.quotation.approve');

    // All authenticated routes continued
    // Create — admin and editor only (MUST be before {client} wildcard)
    Route::middleware('role:admin,editor')->group(function () {
            Route::get('/clients/create', [ClientController::class , 'create'])->name('clients.create');
            Route::post('/clients', [ClientController::class , 'store'])->name('clients.store');
        }
        );

        // --- Team Chat Routes --- (Allowed for sales)
        Route::middleware('role:admin,editor,sales')->group(function () {
            Route::get('/chat', [\App\Http\Controllers\ChatController::class , 'index'])->name('chat.index');
            Route::get('/chat/fetch', [\App\Http\Controllers\ChatController::class , 'fetch'])->name('chat.fetch');
            Route::post('/chat/send', [\App\Http\Controllers\ChatController::class , 'store'])->name('chat.store');
            Route::post('/chat/{message}/pin', [\App\Http\Controllers\ChatController::class , 'togglePin'])->name('chat.pin');
            Route::post('/chat/{message}/decision', [\App\Http\Controllers\ChatController::class , 'toggleDecision'])->name('chat.decision');
            Route::post('/chat/{message}/link-task', [\App\Http\Controllers\ChatController::class , 'linkTask'])->name('chat.link-task');
            Route::post('/chat/{message}/react', [\App\Http\Controllers\ChatController::class , 'addReaction'])->name('chat.react');
        }
        );

        // View routes — restricted from sales
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::get('/clients', [ClientController::class , 'index'])->name('clients.index');
            Route::get('/clients/{client}', [ClientController::class , 'show'])->name('clients.show');
            Route::get('/clients/{client}/print', [ClientController::class , 'print'])->name('clients.print');
        }
        );

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

            // System Backups
            Route::prefix('backups')->name('backups.')->group(function () {
                    Route::get('/', [\App\Http\Controllers\BackupController::class , 'index'])->name('index');
                    Route::post('/create', [\App\Http\Controllers\BackupController::class , 'create'])->name('create');
                    Route::post('/upload', [\App\Http\Controllers\BackupController::class , 'upload'])->name('upload');
                    Route::get('/stream', [\App\Http\Controllers\BackupController::class , 'streamLog'])->name('stream');
                    Route::get('/download/{file_name}', [\App\Http\Controllers\BackupController::class , 'download'])->name('download');
                    Route::delete('/{file_name}', [\App\Http\Controllers\BackupController::class , 'destroy'])->name('destroy');
                    Route::post('/restore/{file_name}', [\App\Http\Controllers\BackupController::class , 'restore'])->name('restore');
                }
                );
            }
            );

            // Attendance Actions (All Authenticated Users except sales)
            Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::get('/attendance/status', [AttendanceController::class , 'status'])->name('attendance.status');
            Route::post('/attendance/check-in', [AttendanceController::class , 'checkIn'])->name('attendance.check-in');
            Route::post('/attendance/check-out', [AttendanceController::class , 'checkOut'])->name('attendance.check-out');
        }
        );

        // Profile routes (Auto-allowed for all roles via auth)
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

        // Payment Routes (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::get('/payments/{payment}/receipt', [\App\Http\Controllers\PaymentController::class , 'receipt'])->name('payments.receipt');
        }
        );

        // Inventory Catalog (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::resource('inventory', \App\Http\Controllers\InventoryController::class);
        }
        );

        // Site Materials (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::post('/project-materials', [\App\Http\Controllers\ProjectMaterialController::class , 'store'])->name('project-materials.store');
            Route::patch('/project-materials/{material}/status', [\App\Http\Controllers\ProjectMaterialController::class , 'updateStatus'])->name('project-materials.updateStatus');
            Route::delete('/project-materials/{material}', [\App\Http\Controllers\ProjectMaterialController::class , 'destroy'])->name('project-materials.destroy');
        }
        );

        // Payment Request Routes (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::patch('/payment-requests/{payment_request}/status', [\App\Http\Controllers\PaymentRequestController::class , 'updateStatus'])->name('payment-requests.updateStatus');
            Route::resource('payment-requests', \App\Http\Controllers\PaymentRequestController::class);
        }
        );

        // Quotation Routes (Allowed for sales)
        Route::middleware('role:admin,editor,sales')->group(function () {
            Route::patch('/quotations/{quotation}/status', [\App\Http\Controllers\QuotationController::class , 'updateStatus'])->name('quotations.updateStatus');
            Route::post('/quotations/{quotation}/approve', [\App\Http\Controllers\QuotationController::class , 'approve'])->name('quotations.approve');
            Route::post('/quotations/{quotation}/convert', [\App\Http\Controllers\QuotationController::class , 'convertToProject'])->name('quotations.convertToProject');
            Route::resource('quotations', \App\Http\Controllers\QuotationController::class);
        }
        );

        // --- Pitching Phase Features --- (Allowed for sales)
        Route::middleware('role:admin,editor,sales,viewer')->group(function () {
            // Estimate Builder
            Route::get('/estimate-builder', [\App\Http\Controllers\EstimateBuilderController::class , 'index'])->name('estimate-builder.index');
            Route::post('/estimate-builder/calculate', [\App\Http\Controllers\EstimateBuilderController::class , 'calculate'])->name('estimate-builder.calculate');

            // Portfolio Board
            Route::get('/portfolio', [\App\Http\Controllers\PortfolioController::class , 'index'])->name('portfolio.index');
        }
        );

        // --- Execution Phase Features --- (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::post('/clients/{client}/dpr', [\App\Http\Controllers\ExecutionController::class , 'storeDPR'])->name('execution.dpr.store');
            Route::post('/clients/{client}/change-requests', [\App\Http\Controllers\ExecutionController::class , 'storeChangeRequest'])->name('execution.change-request.store');
            Route::patch('/change-requests/{changeRequest}/status', [\App\Http\Controllers\ExecutionController::class , 'updateChangeRequestStatus'])->name('execution.change-request.update');
        }
        );

        // --- Financial Management Features --- (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::get('/finance', [\App\Http\Controllers\FinanceController::class , 'summary'])->name('finance.summary');
            Route::post('/clients/{client}/expenses', [\App\Http\Controllers\FinanceController::class , 'storeExpense'])->name('finance.expense.store');
            Route::get('/clients/{client}/analytics', [\App\Http\Controllers\FinanceController::class , 'analytics'])->name('finance.analytics');
            Route::post('/payment-requests/{paymentRequest}/reminder', [\App\Http\Controllers\FinanceController::class , 'sendReminder'])->name('finance.reminder.send');

            // Financial Control Room Routes
            Route::post('/clients/{client}/vendor-payments', [\App\Http\Controllers\FinanceController::class , 'storeVendorPayment'])->name('finance.vendor.store');
            Route::post('/clients/{client}/client-payments', [\App\Http\Controllers\FinanceController::class , 'storeClientPayment'])->name('finance.client.payment.store');
            Route::post('/vendors', [\App\Http\Controllers\FinanceController::class , 'storeVendor'])->name('finance.vendor.create');
            Route::post('/clients/{client}/material-inwards', [\App\Http\Controllers\FinanceController::class , 'storeMaterialInward'])->name('finance.material-inward.store');
            Route::post('/clients/{client}/material-payments', [\App\Http\Controllers\FinanceController::class , 'storeMaterialPayment'])->name('finance.material-payment.store');
            Route::post('/clients/{client}/profit-lock', [\App\Http\Controllers\FinanceController::class , 'toggleLock'])->name('finance.profit.lock');
            Route::get('/clients/{client}/ledger', [\App\Http\Controllers\FinanceController::class , 'downloadLedger'])->name('finance.ledger.download');

            // Delete Routes for Financials
            Route::delete('/vendor-payments/{payment}', [\App\Http\Controllers\FinanceController::class , 'destroyVendorPayment'])->name('finance.vendor.destroy');
            Route::delete('/material-inwards/{inward}', [\App\Http\Controllers\FinanceController::class , 'destroyMaterialInward'])->name('finance.material-inward.destroy');
            Route::delete('/expenses/{expense}', [\App\Http\Controllers\FinanceController::class , 'destroyExpense'])->name('finance.expense.destroy');
        }
        );

        // --- Daily Reports (DPR) ---
        Route::resource('work-orders', \App\Http\Controllers\WorkOrderController::class);
        Route::get('/clients/{client}/reports', [\App\Http\Controllers\DailyReportController::class , 'index'])->name('reports.index');
        Route::post('/clients/{client}/reports', [\App\Http\Controllers\DailyReportController::class , 'store'])->name('reports.store');
        Route::put('/reports/{report}', [\App\Http\Controllers\DailyReportController::class , 'update'])->name('reports.update');
        Route::delete('/reports/{report}', [\App\Http\Controllers\DailyReportController::class , 'destroy'])->name('reports.destroy');

        // --- Task Management ---
        Route::get('/tasks', [\App\Http\Controllers\TaskManagementController::class , 'index'])->name('tasks.index');
        Route::patch('/tasks/{task}/status', [\App\Http\Controllers\TaskManagementController::class , 'updateStatus'])->name('tasks.status.update');

        // --- Handover & Feedback --- (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::post('/clients/{client}/handover/items', [\App\Http\Controllers\HandoverController::class , 'storeChecklistItem'])->name('handover.item.store');
            Route::patch('/handover-items/{item}', [\App\Http\Controllers\HandoverController::class , 'updateChecklistStatus'])->name('handover.item.update');
            Route::post('/clients/{client}/handover/complete', [\App\Http\Controllers\HandoverController::class , 'completeHandover'])->name('handover.complete');
            Route::post('/clients/{client}/feedback', [\App\Http\Controllers\HandoverController::class , 'storeFeedback'])->name('feedback.store');
        }
        );

        // --- Scope of Work --- (Restricted from sales)
        Route::middleware('role:admin,editor,viewer,client')->group(function () {
            Route::post('/clients/{client}/scope', [\App\Http\Controllers\ScopeOfWorkController::class , 'store'])->name('scope.store');
            Route::post('/scope/{scope}/item', [\App\Http\Controllers\ScopeOfWorkController::class , 'storeItem'])->name('scope.item.store');
        }
        );

        Route::get('/test-notification', function () {
            auth()->user()->notify(new \App\Notifications\SystemTestNotification('Test Notification at ' . now()->format('h:i A')));
            return back()->with('success', 'Test notification sent to your bell icon!');
        }
        )->name('test.notification');

        Route::post('/notifications/mark-all-read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        }
        )->name('notifications.markAllRead');


        // --- Lead Management ---
        Route::middleware('role:admin,editor,sales')->group(function () {
            Route::get('/leads', [\App\Http\Controllers\LeadController::class , 'index'])->name('leads.index');
            Route::get('/pipeline', [\App\Http\Controllers\PipelineController::class , 'index'])->name('pipeline.index');
            Route::post('/leads', [\App\Http\Controllers\LeadController::class , 'store'])->name('leads.store');
            Route::put('/leads/{lead}', [\App\Http\Controllers\LeadController::class , 'update'])->name('leads.update');
            Route::patch('/leads/{lead}/status', [\App\Http\Controllers\LeadController::class , 'updateStatus'])->name('leads.updateStatus');
            Route::post('/leads/{lead}/note', [\App\Http\Controllers\LeadController::class , 'addNote'])->name('leads.addNote');
            Route::post('/leads/{lead}/follow-up', [\App\Http\Controllers\LeadController::class , 'setFollowUp'])->name('leads.setFollowUp');
            Route::post('/leads/{lead}/requirements', [\App\Http\Controllers\LeadController::class , 'saveRequirements'])->name('leads.saveRequirements');
            Route::get('/leads/{lead}/requirements/print', [\App\Http\Controllers\LeadController::class , 'printRequirements'])->name('leads.printRequirements');
            Route::get('/leads/{lead}/requirements/export', [\App\Http\Controllers\LeadController::class , 'exportRequirements'])->name('leads.exportRequirements');
            Route::delete('/leads/{lead}', [\App\Http\Controllers\LeadController::class , 'destroy'])->name('leads.destroy');
            Route::post('/leads/sync', [\App\Http\Controllers\LeadController::class , 'sync'])->name('leads.sync');
        }
        );

        // --- Lead & Project Pitch Module (Plug-in) ---
        if (env('PITCH_MODULE_ENABLED', false)) {
            Route::middleware(['auth'])->prefix('pitch')->name('pitch.')->group(function () {
                    Route::get('/leads', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'index'])->name('leads.index');
                    Route::get('/leads/create', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'create'])->name('leads.create');
                    Route::post('/leads', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'store'])->name('leads.store');
                    Route::get('/leads/{lead}', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'show'])->name('leads.show');
                    Route::patch('/leads/{lead}/status', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'updateStatus'])->name('leads.updateStatus');
                    Route::post('/leads/{lead}/sites', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'storeSite'])->name('leads.sites.store');
                    Route::post('/leads/{lead}/visits', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'storeVisit'])->name('leads.visits.store');
                    Route::post('/leads/{lead}/convert', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'convert'])->name('leads.convert');

                    // Design & Concept Routes
                    Route::post('/leads/{lead}/concepts', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'storeDesignConcept'])->name('leads.concepts.store');
                    Route::post('/concepts/{concept}/assets', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'storeDesignAsset'])->name('concepts.assets.store');
                    Route::post('/assets/{asset}/feedback', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'storeDesignFeedback'])->name('assets.feedback.store');
                    Route::patch('/concepts/{concept}/status', [\App\Http\Controllers\Pitch\PitchLeadController::class , 'updateDesignStatus'])->name('concepts.updateStatus');
                }
                );
            }
        });


require __DIR__ . '/auth.php';