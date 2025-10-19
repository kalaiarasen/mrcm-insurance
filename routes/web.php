<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PolicyHolderController;
use App\Http\Controllers\Api\PolicySubmissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::view('layout-light', 'starter_kit.color_version.layout_light')->name('layout_light');
Route::view('layout-dark', 'starter_kit.color_version.layout_dark')->name('layout_dark');
Route::view('box-layout', 'starter_kit.page_layout.box_layout')->name('box_layout');
Route::view('rtl-layout', 'starter_kit.page_layout.rtl_layout')->name('rtl_layout');
Route::view('hide-menu-on-scroll', 'starter_kit.hide_menu_on_scroll')->name('hide_menu_on_scroll');
Route::view('footer-light', 'starter_kit.footers.footer_light')->name('footer_light');
Route::view('footer-dark', 'starter_kit.footers.footer_dark')->name('footer_dark');
Route::view('footer-fixed', 'starter_kit.footers.footer_fixed')->name('footer_fixed');

Route::get('/', function () {
    // Ensure the 'login' route exists and is named 'login'
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::view('for-your-action', 'pages.your-action.index')->middleware(['auth', 'verified'])->name('for-your-action');

// Policy Holders routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('policy-holders', [PolicyHolderController::class, 'index'])->name('policy-holder');
    Route::get('policy-holders/{user}/edit', [PolicyHolderController::class, 'edit'])->name('policy-holders.edit');
    Route::get('policy-holders/{user}', [PolicyHolderController::class, 'show'])->name('policy-holders.show');
    Route::put('policy-holders/{user}', [PolicyHolderController::class, 'update'])->name('policy-holders.update');
});

Route::view('claims', 'pages.claim.index')->middleware(['auth', 'verified'])->name('claim');

Route::get('new-policy', [PolicyController::class, 'newPolicy'])->name('new-policy');

// Policy Submission Route (Web route with CSRF - no auth required for new applicants)
Route::post('policies/submit', [PolicySubmissionController::class, 'submit'])
    ->name('policies.submit');

Route::resource('announcements', AnnouncementController::class)->middleware(['auth', 'verified']);
Route::resource('users', UserController::class)->middleware(['auth', 'verified']);
Route::resource('roles', RoleController::class)->middleware(['auth', 'verified']);
Route::resource('agents', AgentController::class)->middleware(['auth', 'verified']);


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
