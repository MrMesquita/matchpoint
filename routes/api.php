<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArenaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\CourtTimetableController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\AuthSystemMiddleware;
use App\Http\Middleware\AuthAdminOrSystemMiddleware;
use App\Http\Middleware\AuthCustomerOrSystemMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/status', fn() => json_response(['status' => "It's running"]));

Route::prefix("/v1")->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgotPassword');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.resetPassword');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('admins', AdminController::class)->middleware(AuthSystemMiddleware::class);
        Route::apiResource('customers', CustomerController::class)->middleware(AuthSystemMiddleware::class);

        Route::prefix('/arenas')->group(function () {
            Route::get('', [ArenaController::class, 'index'])->name('arenas.index');
            Route::get('/{arena}', [ArenaController::class, 'show'])->name('arenas.show');
            Route::get('/{arena}/courts', [ArenaController::class, 'courts'])->name('arenas.courts');

            Route::middleware(AuthAdminOrSystemMiddleware::class)->group(function () {
                Route::post('', [ArenaController::class, 'store'])->name('arenas.store');
                Route::put('/{arena}', [ArenaController::class, 'update'])->name('arenas.update');
                Route::delete('/{arena}', [ArenaController::class, 'destroy'])->name('arenas.destroy');
            });
        });

        Route::prefix('/courts')->group(function () {
            Route::get('', [CourtController::class, 'index'])->name('courts.index');
            Route::get('/{court}', [CourtController::class, 'show'])->name('courts.show');

            Route::middleware(AuthAdminOrSystemMiddleware::class)->group(function () {
                Route::post('', [CourtController::class, 'store'])->name('courts.store');
                Route::put('/{court}', [CourtController::class, 'update'])->name('courts.update');
                Route::delete('/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');
            });

            Route::prefix('/{court}/timetables')->group(function () {
                Route::get('', [CourtTimetableController::class, 'index'])->name('timetables.index');

                Route::middleware(AuthAdminOrSystemMiddleware::class)->group(function () {
                    Route::post('', [CourtTimetableController::class, 'store'])->name('timetables.store');
                    Route::delete('/{timetable}', [CourtTimetableController::class, 'destroy'])->name('timetables.destroy');
                });
            });
        });

        Route::prefix('/reservations')->group(function () {
            Route::get('', [ReservationController::class, 'index'])->name('reservations.index');
            Route::post('', [ReservationController::class, 'store'])->name('reservations.store')->middleware(AuthCustomerOrSystemMiddleware::class);

            Route::prefix('/{reservation}')->group(function () {
                Route::get('', [ReservationController::class, 'show'])->name('reservations.show');
                Route::put('', [ReservationController::class, 'update'])->name('reservations.update');
                Route::post('', [ReservationController::class, 'confirmReservation'])->name('reservations.confirmReservation')->middleware(AuthAdminOrSystemMiddleware::class);
                Route::delete('', [ReservationController::class, 'destroy'])->name('reservations.destroy');
            });
        });

        Route::prefix('/profiles')->group(function () {
            Route::get('', [ProfileController::class, 'profile'])->name('profiles.profile');
            Route::put('', [ProfileController::class, 'updateProfile'])->name('profiles.updateProfile');
        });
    });
});
