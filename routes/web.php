<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\OrganizerDashboardController;
use App\Http\Controllers\OrganizerEventController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/organizers/guide', 'organizers.guide')->name('organizers.guide');

// Public event pages (views: event.index, event.show)
Route::controller(EventController::class)->prefix('events')->name('events.')->group(function () {
    Route::get('/', 'index')->name('index');        // GET /events
    Route::get('/{event}', 'show')->name('show');   // GET /events/{event}
});

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile');
        Route::put('/profile', 'update')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
    });

    // Admin area: only users with is_admin = true (Gate "admin", see AppServiceProvider)
    Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');   // GET /admin

        // admin.categories.index | create | store | edit | update | destroy
        Route::resource('categories', AdminCategoryController::class)->except('show');
    });

    // Organizer area: users with is_admin = false (Gate "organizer", see AppServiceProvider)
    Route::prefix('organizer')->name('organizer.')->middleware('can:organizer')->group(function () {

        Route::get('/', [OrganizerDashboardController::class, 'index'])->name('dashboard');   // GET /organizer

        // organizer.events.create | store | edit | update | destroy
        Route::resource('events', OrganizerEventController::class)->except(['index', 'show']);
    });

});