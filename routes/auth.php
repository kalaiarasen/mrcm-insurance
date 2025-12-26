<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController;
// use App\Livewire\Auth\ConfirmPassword;
// use App\Livewire\Auth\ForgotPassword;
// use App\Livewire\Auth\Login;
// use App\Livewire\Auth\Register;
// use App\Livewire\Auth\ResetPassword;
// use App\Livewire\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;

// Traditional Blade + Controller routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('register-agent', [AuthController::class, 'showAgentRegisterForm'])->name('agent.register');
    Route::post('register-agent', [AuthController::class, 'registerAgent'])->name('agent.register.submit');
    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Livewire routes (commented out)
// Route::middleware('guest')->group(function () {
//     Route::get('login', Login::class)->name('login');
//     Route::get('register', Register::class)->name('register');
//     Route::get('forgot-password', ForgotPassword::class)->name('password.request');
//     Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
// });

Route::middleware('auth')->group(function () {
    // Route::get('verify-email', VerifyEmail::class)
    //     ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Route::get('confirm-password', ConfirmPassword::class)
    //     ->name('password.confirm');
});

// Logout routes
// Route::post('logout', App\Livewire\Actions\Logout::class)->name('logout');
//Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
