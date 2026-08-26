<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here you can register API routes for your application. These routes are
| loaded by within the middleware group that is applied to the API
| router group. Create something great!
|
| Most of these API routes are protected by Sanctum authentication.
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes (no authentication required)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);
    Route::get('/events/{event}', [\App\Http\Controllers\Api\EventController::class, 'show']);
    Route::get('/events/{event}/feedback', [\App\Http\Controllers\Api\EventController::class, 'feedback']);
    Route::get('/gallery', [\App\Http\Controllers\Api\MediaGalleryController::class, 'index']);
    Route::post('/contact', [\App\Http\Controllers\Api\ContactController::class, 'store']);
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/verify-otp', [\App\Http\Controllers\Api\AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [\App\Http\Controllers\Api\AuthController::class, 'resendOtp']);
    Route::get('/sanctum/csrf-cookie', function (Request $request) {
        return null;
    })->withoutMiddleware('throttle');
});

// Participant routes (authenticated with role: participant)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events/{event}/register', [\App\Http\Controllers\Api\RegistrationController::class, 'store']);
    Route::delete('/registrations/{registration}', [\App\Http\Controllers\Api\RegistrationController::class, 'cancel']);
    Route::get('/me/registrations', [\App\Http\Controllers\Api\EventController::class, 'myRegistrations']);
    Route::get('/me/certificates', [\App\Http\Controllers\Api\CertificateController::class, 'index']);
    Route::post('/certificates/{event}/pay-fee', [\App\Http\Controllers\Api\CertificateController::class, 'payFee']);
    Route::post('/events/{event}/feedback', [\App\Http\Controllers\Api\FeedbackController::class, 'store']);
    Route::post('/bookmarks/{event}', [\App\Http\Controllers\Api\EventController::class, 'bookmark']);
    Route::delete('/bookmarks/{event}', [\App\Http\Controllers\Api\EventController::class, 'bookmark']);
    Route::post('/saved-media/{media}', [\App\Http\Controllers\Api\MediaGalleryController::class, 'save']);
    Route::get('/me/profile', [\App\Http\Controllers\Api\EventController::class, 'index']); // placeholder
});

// Organizer routes (authenticated with role: organizer)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events', [\App\Http\Controllers\Api\EventController::class, 'store']);
    Route::patch('/events/{event}', [\App\Http\Controllers\Api\EventController::class, 'update']);
    Route::delete('/events/{event}', [\App\Http\Controllers\Api\EventController::class, 'destroy']);
    Route::get('/organizer/events', [\App\Http\Controllers\Api\EventController::class, 'organizerEvents']);
    Route::get('/events/{event}/registrations', [\App\Http\Controllers\Api\EventController::class, 'eventRegistrations']);
    Route::post('/attendance/check-in', [\App\Http\Controllers\Api\RegistrationController::class, 'checkIn']);
    Route::post('/events/{event}/media', [\App\Http\Controllers\Api\MediaGalleryController::class, 'index']);
    Route::post('/certificates/{event}/issue', [\App\Http\Controllers\Api\CertificateController::class, 'issue']);
    Route::post('/events/{event}/announce', [\App\Http\Controllers\Api\NotificationController::class, 'store']);
});

// Admin routes (authenticated with role: admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/events', [\App\Http\Controllers\Api\EventController::class, 'adminEvents']);
    Route::patch('/admin/events/{event}/approve', [\App\Http\Controllers\Api\EventController::class, 'approve']);
    Route::patch('/admin/events/{event}/reject', [\App\Http\Controllers\Api\EventController::class, 'reject']);
    Route::get('/admin/users', [\App\Http\Controllers\Api\EventController::class, 'adminUsers']);
    Route::patch('/admin/users/{user}/role', [\App\Http\Controllers\Api\EventController::class, 'updateUserRole']);
    Route::patch('/admin/users/{user}/suspend', [\App\Http\Controllers\Api\EventController::class, 'suspendUser']);
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\Api\EventController::class, 'deleteUser']);
    Route::post('/admin/announcements', [\App\Http\Controllers\Api\NotificationController::class, 'storeAnnouncement']);
    Route::get('/admin/reports/participation', [\App\Http\Controllers\Api\EventController::class, 'participationReport']);
    Route::get('/admin/reports/feedback', [\App\Http\Controllers\Api\EventController::class, 'feedbackReport']);
    Route::get('/admin/reports/users', [\App\Http\Controllers\Api\EventController::class, 'usersReport']);
    Route::get('/admin/reports/certificates', [\App\Http\Controllers\Api\EventController::class, 'certificatesReport']);
    Route::get('/admin/analytics', [\App\Http\Controllers\Api\EventController::class, 'analytics']);
    Route::delete('/admin/media/{media}', [\App\Http\Controllers\Api\MediaGalleryController::class, 'delete']);
    Route::delete('/admin/feedback/{feedback}', [\App\Http\Controllers\Api\EventController::class, 'deleteFeedback']);
});

// Notifications routes (all roles)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markRead']);
    Route::patch('/notifications/{notification}/unread', [\App\Http\Controllers\Api\NotificationController::class, 'markUnread']);
    Route::patch('/notifications/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllRead']);
});