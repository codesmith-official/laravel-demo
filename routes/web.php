<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ForgotPasswordController;
use App\Http\Controllers\Web\OtpVerificationController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ResetPasswordController;
use App\Http\Controllers\Web\UserPageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['passport.guest'])->group(function (): void {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/login', [AuthController::class, 'storeLogin'])->name('login.store');

    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'storeSignup'])->name('signup.store');

    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('otp.resend');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForgotten'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetForgotten'])->name('password.update');
});

Route::middleware(['passport.auth'])->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/users', UserPageController::class)->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/reset-password', [ResetPasswordController::class, 'showAuthenticated'])->name('profile.password.edit');
    Route::put('/reset-password', [ResetPasswordController::class, 'resetAuthenticated'])->name('profile.password.update');
});
