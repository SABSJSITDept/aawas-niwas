<?php
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FamilyBookingController;
use App\Http\Controllers\GroupBookingController;
use App\Http\Controllers\HotelController;

use App\Models\FamilyMember;    
use Illuminate\Http\Request;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomAllotmentController;
use App\Exports\FamilyBookingExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\NewsController;
use App\Models\FamilyBooking;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\RoomReportController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DailyRoomReportController;
use App\Http\Controllers\CheckinStatsController;
use App\Http\Controllers\ParivahanReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CombinedBookingController;
use App\Http\Controllers\Api\DashboardReport\ReportController;
use App\Http\Controllers\ParkingController;


// ============================================================
// PUBLIC ROUTES (No login required)
// ============================================================

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public forms
Route::get('/other_form', function () {
    return view('other_form');
})->name('other_form');
Route::post('/submit-form', [FormController::class, 'store']);
Route::post('/submit-travel-form', [FormController::class, 'storeTravel']);
Route::post('/check-duplicate', [FormController::class, 'checkDuplicate']);

// Public booking forms
Route::get('/group-bookings', [GroupBookingController::class, 'index'])->name('group.booking');
Route::post('/group-booking/store', [GroupBookingController::class, 'store'])->name('group.booking.store');
Route::get('/family-booking/create', [FamilyBookingController::class, 'create'])->name('family-booking.create');
Route::post('/family-booking/store', [FamilyBookingController::class, 'store'])->name('family-booking.store');

// Thank you pages (public)
Route::get('/thank-you/{booking_id?}', [GroupBookingController::class, 'thankyou'])->name('thankyou');
Route::get('/thankyou/family/{id}', function ($id) {    
    $booking = FamilyBooking::find($id);
    if (!$booking) {
        abort(404, 'Booking not found');
    }
    return view('thankyou_family', ['booking' => $booking]);
})->name('family.thankyou');

// Public feedback
Route::get('/feedback', [FeedbackController::class, 'showForm'])->name('feedback.form');
Route::post('/feedback', [FeedbackController::class, 'submitForm'])->name('feedback.submit');

// Sadhu/Sadvi Public Page
Route::get('/sadhu-sadvi', function () {
    return view('sadhu-sadvi.public');
})->name('sadhu-sadvi.public');

// Parking Page
Route::get('/parking', [ParkingController::class, 'index'])->name('parking');

// Location page
Route::get('/location', function () {
    return view('location');
})->name('location.show');

// Helpline numbers
Route::get('/helpline-numbers', function () {
    return view('helpline-numbers');
})->name('helpline-numbers');

// Public Bhojanshala page
Route::get('/bhojanshala', [App\Http\Controllers\BhojanshalaController::class, 'index'])->name('bhojanshala');

// Aadhar check (public API)
Route::post('/check-aadhar', [FamilyBookingController::class, 'checkAadhar'])->name('check.aadhar');
Route::get('/check-aadhar', [GroupBookingController::class, 'checkAadhar'])->name('check.aadhar');

// Booking PDF (public)
Route::view('/booking-pdf', 'user-pdf-form')->name('user.booking.form');
Route::get('/generate-booking-pdf', [App\Http\Controllers\BookingPdfController::class, 'generate'])->name('user.booking.pdf');

// Dashboard Report API (used by dashboard JS)
Route::prefix('dashboard-report')->group(function () {
    Route::get('summary', [ReportController::class, 'summary']);
    Route::get('today-check-in', [ReportController::class, 'todayCheckIn']);
    Route::get('today-check-out', [ReportController::class, 'todayCheckOut']);
    Route::get('next-seven-days-check-in', [ReportController::class, 'nextSevenDaysCheckIn']);
    Route::get('till-today-not-allotted', [ReportController::class, 'tillTodayNotAllotted']);
});

Route::get('/test-api-dashboard', function(){
    return response()->json(['ok'=> 'web-route-works']);
});


// ============================================================
// PROTECTED ROUTES (Login required - auth middleware)
// ============================================================

Route::middleware('auth')->group(function () {

    // --- Dashboard ---
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // --- Checkout / Status Changes ---
    Route::delete('/family-booking/checkout/{id}', [FamilyBookingController::class, 'checkout'])->name('family-booking.checkout');
    Route::delete('/group-booking/checkout/{id}', [GroupBookingController::class, 'checkout'])->name('group-booking.checkout');
    Route::post('/vip-checkout/{id}', [FormController::class, 'checkout'])->name('vip.checkout');

    // --- Admin News ---
    Route::get('/admin/news/create', [NewsController::class, 'create'])->name('admin.news.create');
    Route::post('/admin/news/store', [NewsController::class, 'store'])->name('admin.news.store');
    Route::patch('/admin/news/toggle/{id}', [NewsController::class, 'toggle'])->name('admin.news.toggle');
    Route::get('/admin/news/{id}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/admin/news/{id}', [NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/admin/news/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

    // --- Booking Management ---

    // --- Family Members API ---
    Route::get('/get-family-members/{id}', function ($id) {
        $members = FamilyMember::where('family_id', $id)->get();
        return response()->json($members);
    });

    // --- Admin Forms ---
    Route::get('/export-forms', [FormController::class, 'exportForms'])->name('forms.export');
    Route::get('/admin/forms', [FormController::class, 'index'])->name('admin.forms');
    Route::get('/formsreport', [FormController::class, 'index'])->name('forms.index');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{id}/edit', [FormController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{id}', [FormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('forms.destroy');

    // --- Group Booking Management ---
    Route::get('/group-booking/create', [GroupBookingController::class, 'create'])->name('group.booking.create');
    Route::get('/group-bookings/{id}/edit', [GroupBookingController::class, 'edit'])->name('group.booking.edit');
    Route::put('/group-bookings/{id}', [GroupBookingController::class, 'update'])->name('group.booking.update');
    Route::delete('/group-bookings/{id}', [GroupBookingController::class, 'destroy'])->name('group.booking.destroy');
    Route::get('/admin/group-booking/members/{id}', [GroupBookingController::class, 'showMembers'])->name('group.booking.members');
    Route::get('/admin/group-booking/{id}/members', [GroupBookingController::class, 'getMembers']);

    // --- Family Booking Management ---
    Route::post('/parse-excel', [FamilyBookingController::class, 'parseExcel'])->name('parse-excel');
    Route::post('/admin/family-booking/save-excel', [FamilyBookingController::class, 'saveExcelData'])->name('admin.save-excel-data');
    Route::post('/admin/family-booking/download-failed-rows', [FamilyBookingController::class, 'downloadFailedRows'])->name('admin.download-failed-rows');
    Route::get('/download-excel-template', [FamilyBookingController::class, 'downloadExcelTemplate'])->name('download-excel-template');
    Route::get('/admin/family-booking/excel-upload', [FamilyBookingController::class, 'excelUploadPage'])->name('admin.family-booking.excel-upload');
    Route::get('/family-booking/{id}/edit', [FamilyBookingController::class, 'edit'])->name('family-booking.edit');
    Route::get('/family-booking', [FamilyBookingController::class, 'index'])->name('family-booking.index');
    Route::put('/family-booking/{id}', [FamilyBookingController::class, 'update'])->name('family-booking.update');
    Route::delete('/family-booking/{id}', [FamilyBookingController::class, 'destroy'])->name('family-booking.destroy');



    // --- Hotels ---
    Route::get('/hotels', [HotelController::class, 'index'])->name('hotel.index');
    Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotel.create');
    Route::post('/hotels/store', [HotelController::class, 'store'])->name('hotel.store');
    Route::get('/hotels/{id}', [HotelController::class, 'show'])->name('hotel.show');
    Route::get('/hotels/{id}/edit', [HotelController::class, 'edit'])->name('hotel.edit');
    Route::put('/hotels/{id}', [HotelController::class, 'update'])->name('hotel.update');
    Route::delete('/hotels/{id}', [HotelController::class, 'destroy'])->name('hotel.destroy');
    Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'create'])->name('room.create');
    Route::post('/hotels/rooms/store', [RoomController::class, 'store'])->name('room.store');
    Route::get('/hotel/{id}/actions', [HotelController::class, 'actions'])->name('hotel.actions');
    Route::get('/hotel/{id}/availability', [RoomController::class, 'availability'])->name('room.availability');
    Route::post('/hotel/{id}/toggle-status', [HotelController::class, 'toggleStatus'])->name('hotel.toggleStatus');
    Route::get('/hotel-rooms/{hotel_id}', [HotelController::class, 'showHotelRooms'])->name('hotel.availability');
    Route::get('/select-hotel', [HotelController::class, 'showHotelForm'])->name('select.hotel');
    Route::get('/get-hotel-details/{id}', [HotelController::class, 'getHotelDetails'])->name('get.hotel.details');
    Route::get('/get-all-hotels', [HotelController::class, 'getAllHotels']);
    Route::get('hotels/download-excel', [HotelController::class, 'exportHotelsExcel'])->name('hotels.download.excel');

    // --- Room Allotment ---
    Route::post('/allot-room-process', [RoomAllotmentController::class, 'processAllotment'])->name('allot.room.process');
    Route::get('/room-allotment', [RoomAllotmentController::class, 'showRoomAllotmentPage'])->name('room.allotment.page');
    Route::post('/get-rooms', [RoomAllotmentController::class, 'getHotelRooms'])->name('get.hotel.rooms');
    Route::get('/alot-room', [RoomAllotmentController::class, 'show'])->name('alot.room');
    Route::post('/alot-room_store', [RoomAllotmentController::class, 'store'])->name('alot.room.store');

    // --- Room Details & Categories ---
    Route::get('/room-details/{id}', [RoomController::class, 'getRoomDetails'])->name('room.details');
    Route::get('/room-category/edit/{id}', [RoomController::class, 'edit'])->name('room-category.edit');
    Route::put('/room-category/update/{id}', [RoomController::class, 'update'])->name('room-category.update');
    Route::post('/room-category/delete-room/{id}', [RoomController::class, 'deleteRoom'])->name('room-category.delete-room');
    Route::delete('/room-category/delete/{id}', [RoomController::class, 'destroy'])->name('room-category.delete');
    Route::get('/room-features/edit/{category_id}/{hotel_id}', [RoomController::class, 'editRoomFeatures'])->name('room-features.edit');
    Route::post('/room-features/update', [RoomController::class, 'updateRoomFeatures'])->name('room-features.update');

    // --- Room Exports & Dashboard ---
    Route::get('/rooms/export/{hotel_id}', [RoomController::class, 'export'])->name('rooms.export');
    Route::get('/rooms/export-all', [RoomController::class, 'exportAll'])->name('rooms.export.all');
    Route::get('/rooms/dashboard', [RoomController::class, 'dashboard'])->name('rooms.dashboard');
    Route::get('/rooms/export-pdf-all', [RoomController::class, 'exportPdfAll'])->name('rooms.export.all.pdf');

    // --- Admin Room Features ---
    Route::get('/admin/room-features-page', [AdminController::class, 'showRoomFeatureOnlyPage']);
    Route::get('/admin/room-features/{hotelId}', [AdminController::class, 'hotelRoomFeatures']);
    Route::get('/admin/export-room-features/{hotelId}', [AdminController::class, 'exportRoomFeatures']);

    // --- Reports ---
    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');
    Route::get('/admin/room-report', [App\Http\Controllers\Admin\RoomReportController::class, 'index'])->name('admin.room.report');
    Route::get('/admin/room-report/export', [App\Http\Controllers\Admin\RoomReportController::class, 'export'])->name('admin.room.report.export');
    Route::get('/admin/room-booking-report', [RoomReportController::class, 'roomBookingReport'])->name('admin.room.booking.report');
    Route::get('/admin/room-booking-report/pdf', [RoomReportController::class, 'roomBookingReportPdf'])->name('admin.room.booking.report.pdf');
    Route::get('/room-checkin-report', [RoomReportController::class, 'index'])->name('room.checkin.report');
    Route::post('/room-report/fetch', [RoomReportController::class, 'fetch'])->name('room.report.fetch');
    Route::get('/room-report/pdf', [RoomReportController::class, 'downloadPdf'])->name('room.report.pdf');
    Route::get('/admin/daily-report', [DailyReportController::class, 'index'])->name('daily.report');
    Route::get('/admin/daily-report/pdf', [DailyReportController::class, 'downloadPDF'])->name('daily.report.pdf');
    Route::get('/admin/daily-room-report', [DailyRoomReportController::class, 'show'])->name('admin.daily-room-report');
    Route::get('/admin/daily-room-report/download', [DailyRoomReportController::class, 'download'])->name('admin.daily-room-report.download');
    Route::get('/daily-room-report/notallotted-pdf', [DailyRoomReportController::class, 'notAllottedPdf'])->name('admin.daily-room-report.notallotted-pdf');
    Route::get('/admin/checkin-report', [CheckinStatsController::class, 'index'])->name('admin.checkin.report');
    Route::get('/admin/checkin-report/pdf', [CheckinStatsController::class, 'downloadPdf'])->name('admin.checkin.report.pdf');
    Route::get('/admin/room-booking-summary', [RoomReportController::class, 'roomBookingSummaryReport'])->name('admin.room.booking.summary');
    Route::get('admin/parivahan/datewise-report', [ParivahanReportController::class, 'datewiseStayReport'])->name('admin.parivahan.datewise.report');
    Route::get('admin/parivahan/datewise-report/pdf', [ParivahanReportController::class, 'downloadPdf'])->name('admin.parivahan.datewise.report.pdf');

    // --- Export Reports ---
    Route::get('/export-family-booking', [FamilyBookingController::class, 'export'])->name('family-booking.export');
    Route::get('/export-group-bookings', [GroupBookingController::class, 'export'])->name('export.group.bookings');
    Route::get('/export/family-members', [FamilyBookingController::class, 'exportFamilyWithMembers'])->name('family.members.export');
    Route::get('/export/group-members', [GroupBookingController::class, 'exportGroupWithMembers'])->name('group.members.export');

    // --- Admin Feedback ---
    Route::get('/admin/feedback', [FeedbackController::class, 'index'])->name('admin.feedback.index');

    // --- Combined Bookings ---
    Route::get('/bookings/combined', [CombinedBookingController::class, 'index'])->name('bookings.combined');
    Route::get('/registration/completed-list', [CombinedBookingController::class, 'completedList'])->name('registration.completed_list');

    // --- BhojanShala Report ---
    Route::get('/bhojanshala/report', function () {
        return view('bhojanshala.BhojanShalaReport');
    })->name('bhojanshala.report');

    // --- Registration Lists ---
    Route::get('/registration/list', function () {
        return view('registration.list');
    })->name('registration.list');
    Route::get('/registration/completed-list-api', function () {
        return view('registration.completed_list_api');
    })->name('registration.completed_list_api');
    Route::get('/registration/checkout-list-api', function () {
        return view('registration.checkout_list_api');
    })->name('registration.checkout_list_api');
    Route::get('/registration/rejected-list-api', function () {
        return view('registration.rejected_list_api');
    })->name('registration.rejected_list_api');
    Route::get('/registration/all-list-api', function () {
        return view('registration.all_list_api');
    })->name('registration.all_list_api');

    // --- Sadhu/Sadvi Admin ---
    Route::get('/admin/sadhu-sadvi', function () {
        return view('admin.sadhu-sadvi.index');
    })->name('admin.sadhu-sadvi.index');
    Route::get('/admin/sadhu-sadvi/create', function () {
        return view('admin.sadhu-sadvi.create');
    })->name('admin.sadhu-sadvi.create');
    Route::get('/admin/sadhu-sadvi/show', function () {
        return view('admin.sadhu-sadvi.show');
    })->name('admin.sadhu-sadvi.show');

}); // END auth middleware group
require __DIR__.'/auth.php';
