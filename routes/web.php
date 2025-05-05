<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PaymentController;


// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Register
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Plan selection
    Route::get('plans', [RegisterController::class, 'showPlanSelectionForm'])->name('plans');
    Route::post('plans', [RegisterController::class, 'selectPlan'])->name('plans.select');

});

// Routes de paiement
Route::middleware('auth')->group(function () {
    Route::get('/payment/{plan}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // Simulation PayPal
    Route::get('/payment/paypal/redirect', [PaymentController::class, 'redirectToPaypal'])->name('payment.paypal.redirect');
    Route::get('/payment/paypal/success', [PaymentController::class, 'handlePaypalSuccess'])->name('payment.paypal.success');
    Route::get('/payment/paypal/cancel', [PaymentController::class, 'handlePaypalCancel'])->name('payment.paypal.cancel');

    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
});
