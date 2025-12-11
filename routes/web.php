<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;

Route::controller(EventController::class)->group(function () {
    Route::get('/discover', 'discover')->name('discover');
    Route::get('/events/{id}', 'getDetail')->name('events.show');
    Route::get('/create/events', 'getCreate')->name('create.events');
    Route::post('/events/store', 'store')->name('events.store');
    Route::get('/events/{id}/edit', 'getEdit')->name('edit.events');
    Route::put('/events/{id}', 'update')->name('events.update');
    Route::delete('/events/{id}', 'destroy')->name('events.destroy');
    Route::get('/my-events', 'getMyEvents')->name('my.events');
    Route::post('/events/{eventId}/invite', 'sendInvite')->name('events.sendInvite');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth', 'getAuth')->name('auth');
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(InvitationController::class)->group(function () {
    Route::get('/invitations', 'getInvitations')->name('invitations');
    Route::post('/invitations/{eventId}', 'registerInvitation')->name('invititation.register');
    Route::post('/invitations/{invitation}/respond', 'respondInvitation')->name('invitations.respond');
});

Route::controller(UserController::class)->group(function () {
    Route::put('/profile/image/update', 'updateProfileImage')->name('profile.image.update');
    Route::put('/profile/update', 'updateProfileInfo')->name('profile.update');
    Route::put('/profile/password/update', 'updatePassword')->name('profile.password.update');
});

Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile');
Route::get('/', [DashboardController::class, 'getMyData'])->name('dashboard');