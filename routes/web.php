<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Test Report Routes
Route::get('/reports/individual/{student}', [App\Http\Controllers\TestReportController::class, 'showIndividual'])
    ->name('reports.individual');
Route::get('/reports/team', [App\Http\Controllers\TestReportController::class, 'showTeam'])
    ->name('reports.team');
Route::get('/reports/individual/{student}/new', [App\Http\Controllers\TestReportController::class, 'showIndividualNew'])
    ->name('reports.individual.new');

require __DIR__.'/auth.php';
