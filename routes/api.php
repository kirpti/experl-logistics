<?php

use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PriceCalculatorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Experl Logistics API Routes
|--------------------------------------------------------------------------
| Version: v1
| Base URL: /api/v1
|
| Authentication: Laravel Sanctum Bearer tokens
| Rate Limiting: 60/min (public), 600/min (authenticated)
*/

Route::prefix('v1')->group(function () {

    // ─── Public Routes (no auth required) ────────────────────────────────────
    Route::prefix('public')->group(function () {
        /**
         * POST /api/v1/public/auth/login
         * Driver and customer authentication
         */
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        /**
         * GET /api/v1/public/track/{trackingNumber}
         * Public shipment tracking (no auth)
         */
        Route::get('/track/{trackingNumber}', [TrackingController::class, 'publicTrack'])
            ->middleware('throttle:30,1');

        /**
         * POST /api/v1/public/quote
         * Instant shipping price estimate
         */
        Route::post('/quote', [PriceCalculatorController::class, 'calculate'])
            ->middleware('throttle:20,1');
    });

    // ─── Authenticated Routes ─────────────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // ─── Driver Routes ────────────────────────────────────────────────────
        Route::middleware(['role:driver'])->prefix('driver')->group(function () {
            /**
             * GET  /api/v1/driver/trips          - List assigned trips
             * GET  /api/v1/driver/trips/{id}     - Trip detail
             * POST /api/v1/driver/trips/{id}/start    - Start trip
             * POST /api/v1/driver/trips/{id}/complete - Complete trip
             */
            Route::get('/trips', [TripController::class, 'index']);
            Route::get('/trips/{serviceId}', [TripController::class, 'show']);
            Route::post('/trips/{serviceId}/start', [TripController::class, 'startTrip']);
            Route::post('/trips/{serviceId}/complete', [TripController::class, 'completeTrip']);

            /**
             * GET  /api/v1/driver/deliveries              - Today's delivery list
             * POST /api/v1/driver/deliveries/{id}/status  - Update delivery status
             * POST /api/v1/driver/deliveries/{id}/pod     - Upload POD (photos + signature)
             */
            Route::get('/deliveries', [TripController::class, 'deliveries']);
            Route::post('/deliveries/{shipmentId}/status', [TripController::class, 'updateStatus']);
            Route::post('/deliveries/{shipmentId}/pod', [TripController::class, 'uploadPod']);

            /**
             * POST /api/v1/driver/location - Update live GPS location
             */
            Route::post('/location', [TripController::class, 'updateLocation']);
        });

        // ─── Branch Staff Routes ──────────────────────────────────────────────
        Route::middleware(['role:admin,branch_manager,branch_staff'])->group(function () {
            Route::apiResource('/shipments', ShipmentController::class);
            Route::post('/shipments/{id}/label', [ShipmentController::class, 'generateLabel']);
            Route::post('/shipments/{id}/cancel', [ShipmentController::class, 'cancel']);
            Route::get('/shipments/{id}/tracking', [TrackingController::class, 'internalTrack']);
        });

        // ─── Warehouse Routes ─────────────────────────────────────────────────
        Route::middleware(['role:admin,warehouse'])->prefix('warehouse')->group(function () {
            Route::post('/scan', [\App\Http\Controllers\Api\WarehouseController::class, 'scan']);
            Route::get('/loading-list/{serviceId}', [\App\Http\Controllers\Api\WarehouseController::class, 'loadingList']);
            Route::post('/load', [\App\Http\Controllers\Api\WarehouseController::class, 'loadPackage']);
        });

        // ─── Admin / Finance Routes ───────────────────────────────────────────
        Route::middleware(['role:admin,branch_manager'])->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index']);
            Route::get('/reports/profitability', [\App\Http\Controllers\AdminDashboardController::class, 'branchProfitability']);

            Route::prefix('finance')->group(function () {
                Route::get('/cari/{customerId}', [\App\Http\Controllers\Api\FinanceController::class, 'statement']);
                Route::post('/payment', [\App\Http\Controllers\Api\FinanceController::class, 'recordPayment']);
                Route::get('/aging', [\App\Http\Controllers\Api\FinanceController::class, 'aging']);
                Route::get('/invoices', [\App\Http\Controllers\Api\FinanceController::class, 'invoices']);
                Route::post('/invoices', [\App\Http\Controllers\Api\FinanceController::class, 'createInvoice']);
                Route::get('/invoices/{id}/pdf', [\App\Http\Controllers\Api\FinanceController::class, 'downloadPdf']);
                Route::get('/invoices/{id}/packing-list', [\App\Http\Controllers\Api\FinanceController::class, 'packingListPdf']);
            });
        });

        // ─── Admin Only ───────────────────────────────────────────────────────
        Route::middleware(['role:admin'])->prefix('admin')->group(function () {
            Route::apiResource('/branches', \App\Http\Controllers\Api\BranchController::class);
            Route::apiResource('/services', \App\Http\Controllers\Api\ServiceController::class);
            Route::apiResource('/users', \App\Http\Controllers\Api\UserController::class);
            Route::apiResource('/vehicles', \App\Http\Controllers\Api\VehicleController::class);

            // Live support toggle
            Route::post('/support/toggle', [\App\Http\Controllers\Api\SupportController::class, 'toggle']);
            Route::get('/support/status', [\App\Http\Controllers\Api\SupportController::class, 'status']);

            // Provider settings
            Route::post('/providers/test', [\App\Http\Controllers\Api\ProviderController::class, 'test']);
            Route::get('/providers/rates', [\App\Http\Controllers\Api\ProviderController::class, 'exchangeRates']);
            Route::post('/providers/rates', [\App\Http\Controllers\Api\ProviderController::class, 'updateExchangeRates']);
        });
    });
});
