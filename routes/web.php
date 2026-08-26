<?php

use Illuminate\Support\Facades\Route;

function servePage(string $file) {
    return response(
        file_get_contents(public_path($file)),
        200,
        ['Content-Type' => 'text/html']
    );
}

Route::get('/', fn () => servePage('index.html'))->name('home');
Route::get('/about', fn () => servePage('about.html'));
Route::get('/events', fn () => servePage('events.html'));
Route::get('/gallery', fn () => servePage('gallery.html'));
Route::get('/faq', fn () => servePage('faq.html'));
Route::get('/contact', fn () => servePage('contact.html'));
Route::get('/intro', fn () => servePage('intro.html'));
Route::get('/notifications', fn () => servePage('notifications.html'));
Route::get('/register', fn () => servePage('register.html'));
Route::get('/login', fn () => servePage('login.html'));
Route::get('/verify-otp', fn () => servePage('verify-otp.html'));

Route::get('/dashboard', fn () => servePage('dashboard-student.html'));
Route::get('/dashboard/admin', fn () => servePage('dashboard-admin.html'));
Route::get('/dashboard/organizer', fn () => servePage('dashboard-organizer.html'));
Route::get('/dashboard/student', fn () => servePage('dashboard-student.html'));

require __DIR__.'/auth.php';
