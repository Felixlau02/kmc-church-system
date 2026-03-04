<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// ADMIN Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RoomBookingController;
use App\Http\Controllers\Admin\SermonController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\VolunteerActivityController;

// USER Controllers
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserMemberController;
use App\Http\Controllers\User\UserRoomBookingController;
use App\Http\Controllers\User\UserEventController;
use App\Http\Controllers\User\UserSermonController;
use App\Http\Controllers\User\UserVolunteerActivityController;
use App\Http\Controllers\User\UserDonationController;
use App\Http\Controllers\User\UserAttendanceController;
use App\Http\Controllers\User\UserSupportTicketController;
use App\Http\Controllers\User\UserNotificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

// ==========================
// AUTHENTICATED ROUTES
// ==========================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - This will redirect based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Shared Profile Routes (accessible by both admin and user)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
    });
});

// ==========================
// ADMIN ROUTES
// ==========================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/home', [AdminController::class, 'home'])->name('home');
    
    // Calendar data API endpoint
    Route::get('/calendar-data', [AdminController::class, 'getCalendarData'])->name('calendar.data');

    // Admin Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('show');
    });

    // Members
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/create', [MemberController::class, 'create'])->name('create');
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::get('/statistics', [MemberController::class, 'statistics'])->name('statistics');
        Route::get('/export', [MemberController::class, 'export'])->name('export');
        Route::get('/{member}', [MemberController::class, 'show'])->name('show');
        Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
        Route::put('/{member}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
    });

    // Room Bookings
    Route::prefix('roombooking')->name('roombooking.')->group(function () {
        Route::get('/', [RoomBookingController::class, 'index'])->name('index');
        Route::get('/history', [RoomBookingController::class, 'history'])->name('history');
        Route::get('/create-direct', [RoomBookingController::class, 'createDirect'])->name('create-direct');
        Route::post('/direct', [RoomBookingController::class, 'storeDirect'])->name('store-direct');
        Route::get('/{roomBooking}/edit', [RoomBookingController::class, 'edit'])->name('edit');
        Route::put('/{roomBooking}', [RoomBookingController::class, 'update'])->name('update');
        Route::delete('/{roomBooking}', [RoomBookingController::class, 'destroy'])->name('destroy');
        Route::patch('/{roomBooking}/approve', [RoomBookingController::class, 'approve'])->name('approve');
        Route::patch('/{roomBooking}/reject', [RoomBookingController::class, 'reject'])->name('reject');
        Route::patch('/{roomBooking}/archive', [RoomBookingController::class, 'archive'])->name('archive');
    });

    // Events
    Route::get('event/history', [EventController::class, 'history'])->name('event.history');
    Route::resource('event', EventController::class);

    // Sermons
    Route::resource('sermon', SermonController::class);
    Route::prefix('sermon')->name('sermon.')->group(function () {
        Route::get('{id}/status', [SermonController::class, 'processingStatus'])->name('status');
        Route::post('{id}/reprocess', [SermonController::class, 'reprocess'])->name('reprocess');
        Route::get('{id}/transcript/download', [SermonController::class, 'downloadTranscript'])->name('transcript.download');
    });

    // Volunteer Activities
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/', [VolunteerActivityController::class, 'index'])->name('index');
        Route::post('/add-team', [VolunteerActivityController::class, 'addTeam'])->name('add-team');
        Route::get('/team/{teamId}/manage', [VolunteerActivityController::class, 'manageTeam'])->name('manage-team');
        Route::delete('/team/{teamId}', [VolunteerActivityController::class, 'deleteTeam'])->name('delete-team');
        Route::post('/team/{teamId}/add-member', [VolunteerActivityController::class, 'addMember'])->name('add-member');
        Route::delete('/member/{memberId}', [VolunteerActivityController::class, 'removeMember'])->name('remove-member');
        Route::post('/request/{registrationId}/approve', [VolunteerActivityController::class, 'approveRequest'])->name('approve-request');
        Route::delete('/request/{registrationId}/reject', [VolunteerActivityController::class, 'rejectRequest'])->name('reject-request');
    });

    // Donations
    Route::prefix('donation')->name('donation.')->group(function () {
        Route::get('/', [DonationController::class, 'index'])->name('index');
        Route::get('/create', [DonationController::class, 'create'])->name('create');
        Route::post('/store', [DonationController::class, 'store'])->name('store');
        Route::get('/view-evidence/{id}', [DonationController::class, 'viewEvidence'])->name('view-evidence');
        Route::delete('/delete/{id}', [DonationController::class, 'destroy'])->name('delete');
        Route::get('/manage-qr', [DonationController::class, 'manageQR'])->name('manage-qr');
        Route::post('/upload-qr', [DonationController::class, 'storeQR'])->name('upload-qr');
        Route::delete('/delete-qr/{id}', [DonationController::class, 'deleteQR'])->name('delete-qr');
        Route::get('/report', [DonationController::class, 'generateReport'])->name('report');
    });

    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'overview'])->name('index');
        Route::get('/{event}/track', [AttendanceController::class, 'track'])->name('track');
        Route::post('/{event}/store', [AttendanceController::class, 'store'])->name('store');
    });

    // Support Tickets
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [SupportTicketController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/response', [SupportTicketController::class, 'addResponse'])->name('add-response');
        Route::patch('/{id}/response', [SupportTicketController::class, 'updateResponse'])->name('update-response');
        Route::delete('/{id}/response', [SupportTicketController::class, 'deleteResponse'])->name('delete-response');
        Route::delete('/{id}', [SupportTicketController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-update', [SupportTicketController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Notifications Management (ADMIN)
    Route::prefix('notification')->name('notification.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/create', [NotificationController::class, 'create'])->name('create');
        Route::post('/store', [NotificationController::class, 'store'])->name('store');
        Route::get('/{notification}/edit', [NotificationController::class, 'edit'])->name('edit');
        Route::put('/{notification}', [NotificationController::class, 'update'])->name('update');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/{notification}/toggle', [NotificationController::class, 'toggle'])->name('toggle');
        
        // Notification Actions for Admin
        Route::get('/count', [NotificationController::class, 'count'])->name('count');
        Route::get('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    });
});

// ==========================
// USER ROUTES
// ==========================
Route::prefix('user')->middleware(['auth'])->name('user.')->group(function () {

    // Dashboard with calendar
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/calendar-data', [UserController::class, 'getCalendarData'])->name('dashboard.calendar-data');

    // User Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    });

    // Members
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/', [UserMemberController::class, 'index'])->name('index');
        Route::get('/{id}', [UserMemberController::class, 'show'])->name('show');
    });

    // Room Bookings
    Route::prefix('roombooking')->name('roombooking.')->group(function () {
        Route::get('/', [UserRoomBookingController::class, 'index'])->name('index');
        Route::get('/history', [UserRoomBookingController::class, 'history'])->name('history');
        Route::get('/create', [UserRoomBookingController::class, 'create'])->name('create');
        Route::post('/', [UserRoomBookingController::class, 'store'])->name('store');
        Route::post('/{id}/archive', [UserRoomBookingController::class, 'archive'])->name('archive');
        Route::post('/{id}/restore', [UserRoomBookingController::class, 'restore'])->name('restore');
        Route::post('/auto-archive', [UserRoomBookingController::class, 'autoArchivePastBookings'])->name('auto-archive');
    });

    // Events
    Route::prefix('events')->name('event.')->group(function () {
        Route::get('/', [UserEventController::class, 'index'])->name('index');
        Route::get('/my-events', [UserEventController::class, 'myEvents'])->name('my-events');
        Route::get('/{event}', [UserEventController::class, 'show'])->name('show');
        Route::post('/{event}/register', [UserEventController::class, 'register'])->name('register');
        Route::post('/{event}/cancel', [UserEventController::class, 'cancel'])->name('cancel');
    });

    // Sermons
    Route::prefix('sermons')->name('sermon.')->group(function () {
        Route::get('/', [UserSermonController::class, 'index'])->name('index');
        Route::get('/{sermon}', [UserSermonController::class, 'show'])->name('show');
        Route::get('/{sermon}/status', [UserSermonController::class, 'processingStatus'])->name('status');
    });

    // Volunteers
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/', [UserVolunteerActivityController::class, 'index'])->name('index');
        Route::get('/sunday-service', [UserVolunteerActivityController::class, 'sundayService'])->name('sunday-service');
        Route::post('/team/{teamId}/request-join', [UserVolunteerActivityController::class, 'requestJoinTeam'])->name('request-join-team');
        Route::delete('/team/{teamId}/cancel-request', [UserVolunteerActivityController::class, 'cancelTeamRequest'])->name('cancel-team-request');
        Route::post('/{id}/register', [UserVolunteerActivityController::class, 'register'])->name('register');
        Route::delete('/{id}/unregister', [UserVolunteerActivityController::class, 'unregister'])->name('unregister');
    });

    // Donations
    Route::prefix('donations')->name('donation.')->group(function () {
        Route::get('/', [UserDonationController::class, 'index'])->name('index');
        Route::get('/create', [UserDonationController::class, 'create'])->name('create');
        Route::post('/', [UserDonationController::class, 'store'])->name('store');
    });

    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [UserAttendanceController::class, 'index'])->name('index');
    });

    // Support
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [UserSupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [UserSupportTicketController::class, 'create'])->name('create');
        Route::post('/', [UserSupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [UserSupportTicketController::class, 'show'])->name('show');
        Route::delete('/{id}', [UserSupportTicketController::class, 'destroy'])->name('destroy');
    });

    // Notifications (USER)
    Route::prefix('notification')->name('notification.')->group(function () {
        Route::get('/count', [UserNotificationController::class, 'count'])->name('count');
        Route::get('/{id}/mark-as-read', [UserNotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-as-read', [UserNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    });
});