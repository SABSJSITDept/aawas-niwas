<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardReport\ReportController;
use App\Http\Controllers\Api\DashboardReport\RoomReportController;
use App\Http\Controllers\Api\Registration\RegistrationController;
use App\Http\Controllers\Api\SadhuSadviController;



Route::prefix('dashboard-report')->group(function () {
    Route::get('summary', [ReportController::class, 'summary']);
    Route::get('today-check-in', [ReportController::class, 'todayCheckIn']);
    Route::get('today-check-out', [ReportController::class, 'todayCheckOut']);
    Route::get('next-seven-days-check-in', [ReportController::class, 'nextSevenDaysCheckIn']);
    Route::get('till-today-not-allotted', [ReportController::class, 'tillTodayNotAllotted']);
});

Route::get('dashboard_report/rooms', [RoomReportController::class, 'index']);

Route::get('bhojanshala/expected-members', [\App\Http\Controllers\Api\Bhojanshala\BhojanshalaController::class, 'expectedMembers']);


Route::prefix('registration')->group(function() {
    Route::get('bookings', [RegistrationController::class, 'index']);                // list (merged, pending only)
    Route::get('/export', [RegistrationController::class, 'export']);
    Route::get('{type}/{id}', [RegistrationController::class, 'show']);           // show full details, type = family|group
    Route::put('{type}/{id}', [RegistrationController::class, 'update']);         // update booking
    Route::delete('{type}/{id}', [RegistrationController::class, 'destroy']);     // delete booking + members
    Route::post('{type}/{id}/status', [RegistrationController::class, 'changeStatus']); // change status (approve/reject/checkout)
    Route::post('{type}/{id}/checkout', [RegistrationController::class, 'checkout']);
});

Route::get('/registration/completed-list', [\App\Http\Controllers\Api\Registration\RegistrationController::class, 'completedList']);
Route::get('/registration/completed-export', [\App\Http\Controllers\Api\Registration\RegistrationController::class, 'completedExport']);
Route::get('/registration/rejected-export', [\App\Http\Controllers\Api\Registration\RegistrationController::class, 'rejectedExport']);
Route::get('/registration/checkout-export', [\App\Http\Controllers\Api\Registration\RegistrationController::class, 'checkoutExport']);
Route::get('/registration/checkout-list', [\App\Http\Controllers\Api\Registration\RegistrationController::class, 'checkoutList']);
Route::get('/registration/rejected-list', [\App\Http\Controllers\Api\Registration\RegistrationController::class, 'rejectedList']);

// Sadhu Sadvi Routes
Route::apiResource('sadhu-sadvi', SadhuSadviController::class);

