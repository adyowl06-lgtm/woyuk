<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - WOYuk!
|--------------------------------------------------------------------------
*/

// 1. GUEST ROUTES (Hanya bisa diakses jika BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('landing');
    })->name('landing');

    // Registration Routes
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'Auth\RegisterController@register');

    // Login Routes
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
});

// 2. GOOGLE OAUTH ROUTES
Route::get('/auth/google', 'Auth\GoogleController@redirectToGoogle')->name('auth.google');
Route::get('/auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

// 3. PROTECTED ROUTES (Hanya bisa diakses jika SUDAH login)
Route::middleware('auth')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/archive', 'ArchiveController@index')->name('archive.index');
    // API Route Ambil Koordinat Rute Histori
    Route::get('/api/activity/{id}/route', 'ActivityApiController@getRoutePoints')->name('activity.route.points');
    
    // Profile & Settings Routes
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update', 'ProfileController@updateProfile')->name('profile.update');
    Route::post('/settings/update', 'ProfileController@updateSettings')->name('settings.update');

    // Tracking Session Routes
    Route::post('/activity/start', 'ActivitySessionController@start')->name('activity.start');
    Route::get('/activity/test-start', 'ActivitySessionController@start')->name('activity.test');
    Route::post('/activity/{id}/stop', 'ActivitySessionController@stop')->name('activity.stop');

    // API Sync Route Realtime GPS
    Route::post('/api/activity/{id}/sync', 'ActivityApiController@syncPoints')->name('activity.sync');

    // Logout Route
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    // Search Routes (Baru)
    Route::get('/api/users/search', 'UserSearchController@search')->name('api.users.search');
    Route::get('/user/{public_id}', 'UserSearchController@showProfile')->name('user.profile.show');

});

// ADMIN ROUTES (Terproteksi Middleware Admin & Auth)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', 'AdminDashboardController@index')->name('admin.dashboard');
    Route::post('/users/create-admin', 'AdminDashboardController@storeAdmin')->name('admin.create');
});

// 4. FALLBACK ROUTE (/home bawaan laravel ke /dashboard)
Route::get('/home', function() {
    return redirect()->route('dashboard');
});